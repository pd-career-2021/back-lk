<?php

namespace App\Http\Controllers;

use App\Http\Library\ApiHelpers;
use App\Http\Resources\EmployerCollection;
use App\Http\Resources\EmployerResource;
use App\Models\CompanyType;
use App\Models\Employer;
use App\Models\Industry;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployerController extends Controller
{
    use ApiHelpers;

    public function index(): EmployerCollection
    {
        return new EmployerCollection(Employer::all());
    }

    public function show(Employer $employer): EmployerResource
    {
        return new EmployerResource($employer);
    }

    public function store(Request $request): EmployerResource
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:128',
            'short_name' => 'string|max:64',
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'user_id' => 'required|integer',
            'company_type_id' => 'required|integer',
            'industry_ids' => 'required|array',
            'industry_ids.*' => 'integer',
        ]);

        $user = User::findOrFail($validated['user_id']);
        if ($user->student()->exists() || $user->employer()->exists()) {
            return response()->json(['message' => 'User is already associated.'], 409);
        }

        $employer = new Employer($request->all());
        $employer->user()->associate($user);

        $companyType = CompanyType::findOrFail($validated['company_type_id']);
        $employer->companyType()->associate($companyType);

        $validatedIndustryIds = Industry::whereIn('id', $validated['industry_ids'])->pluck('id')->toArray();
        $employer->industries()->sync($validatedIndustryIds);

        if ($request->hasFile('image')) {
            $employer->img_path = $request->file('image')->store('img/e' . $employer->id, 'public');
        }

        $employer->save();

        return new EmployerResource($employer);
    }

    public function update(Request $request, Employer $employer): EmployerResource
    {
        $validated = $request->validate([
            'full_name' => 'string|max:128',
            'short_name' => 'string|max:64',
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'user_id' => 'integer',
            'company_type_id' => 'integer',
            'industry_ids' => 'array',
            'industry_ids.*' => 'integer',
        ]);

        $user = $request->user();


        if (!$this->isAdmin($user)) {
            if ($user->id !== $employer->user_id) {
                return response(['message' => 'You do not have permission to do this.'], 403);
            }
        } else {
            if ($request->has('user_id')) {
                $newUser = User::findOrFail($validated['user_id']);
                if ($newUser->student()->exists() || $newUser->employer()->exists()) {
                    return response()->json(['message' => 'User is already associated.'], 409);
                }
                $employer->user()->associate($newUser);
            }
        }

        $employer->update($validated);

        if ($request->has('company_type_id')) {
            $companyType = CompanyType::findOrFail($validated['company_type_id']);
            $employer->companyType()->associate($companyType);
        }

        if ($request->has('industry_ids')) {
            $validatedIndustryIds = Industry::whereIn('id', $validated['industry_ids'])->pluck('id')->toArray();
            $employer->industries()->sync($validatedIndustryIds);
        }

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($employer->img_path);
            $employer->img_path = $request->file('image')->store('img/e' . $employer->id, 'public');
        }

        $employer->save();

        return new EmployerResource($employer);
    }

    public function destroy(Employer $employer)
    {
        Storage::disk('public')->delete($employer->img_path);
        return $employer->delete();
    }
}
