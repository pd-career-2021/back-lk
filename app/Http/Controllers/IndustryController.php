<?php

namespace App\Http\Controllers;

use App\Http\Resources\IndustryCollection;
use App\Http\Resources\IndustryResource;
use Illuminate\Http\Request;
use App\Models\Industry;

class IndustryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): IndustryCollection
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
    public function store(Request $request): IndustryResource
    {
        $validated = $request->validate([
            'title' => 'required|string|max:45'
        ]);

        $industry = Industry::create($validated);

        return new IndustryResource($industry);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Industry $industry): IndustryResource
    {
        return new IndustryResource($industry);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @param  \Illuminate\Validation\Factory  $validator
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Industry $industry): IndustryResource
    {
        $validated = $request->validate([
            'title' => 'required|string|max:45'
        ]);
        
        $industry->update($validated);

        return new IndustryResource($industry);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Industry $industry)
    {
        return $industry->delete();
    }
}
