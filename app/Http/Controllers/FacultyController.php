<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FacultyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $faculties = Faculty::all();
        foreach ($faculties as $faculty) {
            $path = ($faculty->img_path) ? $faculty->img_path : 'img/blank.jpg';
            $faculty['image'] = asset('storage/' . $path);
        }

        return $faculties;
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
            'title' => 'required|string|max:128',
            'desc' => 'required|string|max:1000',
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);
        if ($validator->fails()) {
            return $validator->errors()->all();
        }

        $faculty = new Faculty($request->all());

        if ($request->hasFile('image')) {
            $faculty->img_path = $request->file('image')->store('img/faculty' . $faculty->id, 'public');
        }
        $faculty->save();
        $path = ($faculty->img_path) ? $faculty->img_path : 'img/blank.jpg';
        $faculty['image'] = asset('storage/' . $path);

        return $faculty;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $faculty = Faculty::find();
        $path = ($faculty->img_path) ? $faculty->img_path : 'img/blank.jpg';
        $faculty['image'] = asset('storage/' . $path);

        return $faculty;
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
            'title' => 'required|string|max:128',
            'desc' => 'required|string|max:1000',
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);
        if ($validator->fails()) {
            return $validator->errors()->all();
        }

        $faculty = Faculty::find($id);
        $faculty->update($request->all());

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($faculty->img_path);
            $faculty->img_path = $request->file('image')->store('img/faculty' . $faculty->id, 'public');
        }
        $faculty->save();
        $path = ($faculty->img_path) ? $faculty->img_path : 'img/blank.jpg';
        $faculty['image'] = asset('storage/' . $path);

        return $faculty;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Storage::disk('public')->delete(Faculty::find($id)->img_path);
        return Faculty::destroy($id);
    }
}
