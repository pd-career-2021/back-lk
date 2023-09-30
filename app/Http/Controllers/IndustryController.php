<?php

namespace App\Http\Controllers;

use App\Http\Resources\IndustryCollection;
use App\Http\Resources\IndustryResource;
use Illuminate\Http\Request;
use App\Models\Industry;
use Illuminate\Support\Facades\Validator;

class IndustryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new IndustryCollection(Industry::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Validation\Factory  $validator
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Factory $validator)
    {
        $validated = $validator->make($request->all(), [
            'title' => 'required|string|max:45'
        ])->validated();

        $industry = new Industry($validated);
        $industry->save();

        return new IndustryResource($industry);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new IndustryResource(Industry::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @param  \Illuminate\Validation\Factory  $validator
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, Factory $validator)
    {
        $validated = $validator->make($request->all(), [
            'title' => 'required|string|max:45'
        ])->validated();

        $industry = Industry::findOrFail($id);
        $industry->update($validated)->save();

        return new IndustryResource($industry);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Industry::destroy($id);
    }
}
