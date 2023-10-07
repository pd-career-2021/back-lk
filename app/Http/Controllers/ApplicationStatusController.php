<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApplicationStatusCollection;
use App\Http\Resources\ApplicationStatusResource;
use Illuminate\Http\Request;
use App\Models\ApplicationStatus;

class ApplicationStatusController extends Controller
{
    public function index(): ApplicationStatusCollection
    {
        return new ApplicationStatusCollection(ApplicationStatus::all());
    }

    public function show(ApplicationStatus $applicationStatus): ApplicationStatusResource
    {
        return new ApplicationStatusResource($applicationStatus);
    }

    public function store(Request $request): ApplicationStatusResource
    {
        $validated = $request->validate([
            'name' => 'required|string|max:45',
            'desc' => 'required|string|max:1000'
        ]);

        $applicationStatus = ApplicationStatus::create($validated);

        return new ApplicationStatusResource($applicationStatus);
    }

    public function update(Request $request, ApplicationStatus $applicationStatus): ApplicationStatusResource
    {
        $validated = $request->validate([
            'name' => 'required|string|max:45',
            'desc' => 'required|string|max:1000'
        ]);

        $applicationStatus->update($validated);

        return new ApplicationStatusResource($applicationStatus);
    }

    public function destroy(ApplicationStatus $applicationStatus)
    {
        return $applicationStatus->delete();
    }
}
