<?php

namespace App\Http\Controllers;

use App\Http\Resources\AudienceCollection;
use App\Http\Resources\AudienceResource;
use Illuminate\Http\Request;
use App\Models\Audience;

class AudienceController extends Controller
{
    public function index(): AudienceCollection
    {
        return new AudienceCollection(Audience::all());
    }

    public function show(Audience $audience): AudienceResource
    {
        return new AudienceResource($audience);
    }

    public function store(Request $request): AudienceResource
    {
        $validated = $request->validate([
            'name' => 'required|string|max:45'
        ]);

        $audience = Audience::create($validated);

        return new AudienceResource($audience);
    }

    public function update(Request $request, Audience $audience): AudienceResource
    {
        $validated = $request->validate([
            'name' => 'required|string|max:45'
        ]);

        $audience->update($validated);

        return new AudienceResource($audience);
    }

    public function destroy(Audience $audience)
    {
        return $audience->delete();
    }
}
