<?php

namespace App\Http\Controllers;

use App\Http\Library\ApiHelpers;
use App\Models\CompanyType;
use App\Models\Employer;
use App\Models\Industry;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EmployerController extends Controller
{
    use ApiHelpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employers = Employer::all();
        foreach ($employers as $employer) {
            $path = ($employer->img_path) ? $employer->img_path : 'img/blank.jpg';
            $employer['image'] = asset('storage/' . $path);
        }

        return $employers;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:128',
            'short_name' => 'string|max:64',
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'user_id' => 'required|integer',
            'company_type_id' => 'required|integer',
            'industry_ids' => 'required|array',
            'industry_ids.*' => 'integer',
        ]);
        if ($validator->fails()) {
            return $validator->errors()->all();
        }

        $employer = new Employer($request->all());
        $user = User::find($request->input('user_id'));
        if ($user) {
            if ($user->student()->exists() || $user->employer()->exists()) {
                return response([
                    'message' => 'User already associated.'
                ], 401);
            } else {
                $employer->user()->associate($user);
            }
        }

        $companyType = CompanyType::find($request->input('company_type_id'));
        if (!$companyType) {
            return response([
                'message' => 'Company type not found.'
            ], 401);
        } else {
            $employer->companyType()->associate($companyType);
        }
        $employer->save();

        $validated = array();
        foreach ($request->input('industry_ids') as $id) {
            if (Industry::find($id))
                array_push($validated, $id);
        }
        $employer->industries()->sync($validated);

        if ($request->hasFile('image')) {
            $employer->img_path = $request->file('image')->store('img/e' . $employer->id, 'public');
        }
        $employer->save();
        $path = ($employer->img_path) ? $employer->img_path : 'img/blank.jpg';
        $employer['image'] = asset('storage/' . $path);

        return $employer;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employer = Employer::find($id);
        $path = ($employer->img_path) ? $employer->img_path : 'img/blank.jpg';
        $employer['image'] = asset('storage/' . $path);
        $employer->companyType;
        $employer->industries;
        $employer->socials;

        return $employer;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'string|max:128',
            'short_name' => 'string|max:64',
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'user_id' => 'integer',
            'company_type_id' => 'integer',
            'industry_ids' => 'array',
            'industry_ids.*' => 'integer',
        ]);
        if ($validator->fails()) {
            return $validator->errors()->all();
        }

        $employer = Employer::find($id);
        $user = $request->user();

        if (!$this->isAdmin($user)) {
            if ($user->id != $employer->user_id) {
                return response([
                    'message' => 'You do not have permission to do this.'
                ], 401);
            }
        } else {
            if ($request->has('user_id')) {
                $user = User::find($request->input('user_id'));
                if ($user) {
                    if ($user->student()->exists() || $user->employer()->exists()) {
                        return response([
                            'message' => 'User already associated.'
                        ], 401);
                    } else {
                        $employer->user()->associate($user);
                    }
                }
            }
        }

        $employer->update($request->all());
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($employer->img_path);
            $employer->img_path = $request->file('image')->store('img/e' . $id, 'public');
        }

        if ($request->has('company_type_id')) {
            $companyType = CompanyType::find($request->input('company_type_id'));
            if (!$companyType) {
                return response([
                    'message' => 'Company type not found.'
                ], 401);
            } else {
                $employer->companyType()->associate($companyType);
            }
        }

        if ($request->has('industry_ids')) {
            $validated = array();
            foreach ($request->input('industry_ids') as $id) {
                if (Industry::find($id))
                    array_push($validated, $id);
            }
            $employer->industries()->sync($validated);
        }

        $employer->save();
        $path = ($employer->img_path) ? $employer->img_path : 'img/blank.jpg';
        $employer['image'] = asset('storage/' . $path);
        $employer['industries'] = $employer->industries()->get();
        $employer->companyType;
        $employer->industries;

        return $employer;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Storage::disk('public')->delete(Employer::find($id)->img_path);
        return Employer::destroy($id);
    }
}
