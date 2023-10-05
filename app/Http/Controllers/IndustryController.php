<?php

namespace App\Http\Controllers;

use App\Http\Resources\IndustryCollection;
use App\Http\Resources\IndustryResource;
use Illuminate\Http\Request;
use App\Models\Industry;

class IndustryController extends Controller
{
    public function index(): IndustryCollection
    {
        return new IndustryCollection(Industry::all());
    }

    public function show(Industry $industry): IndustryResource
    {
        return new IndustryResource($industry);
    }

    public function store(Request $request): IndustryResource
    {
        $validated = $request->validate([
            'title' => 'required|string|max:45'
        ]);

        $industry = Industry::create($validated);

        return new IndustryResource($industry);
    }

    public function update(Request $request, Industry $industry): IndustryResource
    {
        $validated = $request->validate([
            'title' => 'required|string|max:45'
        ]);
        
        $industry->update($validated);

        return new IndustryResource($industry);
    }

    public function destroy(Industry $industry)
    {
        return $industry->delete();
    }
}
