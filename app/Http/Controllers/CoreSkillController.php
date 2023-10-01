<?php

namespace App\Http\Controllers;

use App\Http\Resources\CoreSkillCollection;
use App\Http\Resources\CoreSkillResource;
use Illuminate\Http\Request;
use App\Models\CoreSkill;

class CoreSkillController extends Controller
{
    public function index(): CoreSkillCollection
    {
        $coreSkills = CoreSkill::all();
        
        return new CoreSkillCollection($coreSkills);
    }

    public function show(CoreSkill $coreSkill): CoreSkillResource
    {
        return new CoreSkillResource($coreSkill);
    }

    public function store(Request $request): CoreSkillResource
    {
        $validated = $request->validate([
            'title' => 'required|string|max:64'
        ]);

        $coreSkill = CoreSkill::create($validated);

        return new CoreSkillResource($coreSkill);
    }

    public function update(Request $request, CoreSkill $coreSkill): CoreSkillResource
    {
        $validated = $request->validate([
            'title' => 'required|string|max:64'
        ]);
        
        $coreSkill->update($validated);

        return new CoreSkillResource($coreSkill);
    }

    public function destroy(CoreSkill $coreSkill)
    {
        return $coreSkill->delete();
    }
}
