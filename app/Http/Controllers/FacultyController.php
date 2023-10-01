<?php

namespace App\Http\Controllers;

use App\Http\Resources\FacultyCollection;
use App\Http\Resources\FacultyResource;
use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FacultyController extends Controller
{
    public function index(): FacultyCollection
    {
        return new FacultyCollection(Faculty::all());
    }

    public function show(Faculty $faculty): FacultyResource
    {
        return new FacultyResource($faculty);
    }

    public function store(Request $request): FacultyResource
    {
        $validated = $request->validate([
            'title' => 'required|string|max:128',
            'desc' => 'required|string|max:1000',
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        $faculty = Faculty::create($validated);

        if ($request->hasFile('image')) {
            $faculty->img_path = $request->file('image')->store('img/faculty' . $faculty->id, 'public');
            $faculty->save();
        }

        return new FacultyResource($faculty);
    }

    public function update(Request $request, Faculty $faculty): FacultyResource
    {
        $validated = $request->validate([
            'title' => 'required|string|max:128',
            'desc' => 'required|string|max:1000',
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        $faculty->update($validated);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($faculty->img_path);
            $faculty->img_path = $request->file('image')->store('img/faculty' . $faculty->id, 'public');
            $faculty->save();
        }

        return new FacultyResource($faculty);
    }

    public function destroy(Faculty $faculty)
    {
        Storage::disk('public')->delete($faculty->img_path);

        return $faculty->delete();
    }
}
