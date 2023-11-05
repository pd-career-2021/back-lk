<?php

namespace App\Http\Controllers;

use App\Http\Library\ApiHelpers;
use App\Http\Resources\StudentCollection;
use App\Http\Resources\StudentResource;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    use ApiHelpers;

    public function index(): StudentCollection
    {
        return new StudentCollection(Student::paginate(10));
    }

    public function show(Student $student): StudentResource
    {
        return new StudentResource($student);
    }

    public function store(Request $request): StudentResource
    {
        $validated = $request->validate([
            'desc' => 'required|string|max:1000',
            'user_id' => 'required|integer',
        ]);

        $user = User::findOrFail($validated['user_id']);

        if ($user->student()->exists() || $user->employer()->exists()) {
            return response(['message' => 'User is already associated.'], 409);
        }

        $student = new Student($validated);
        $student->user()->associate($user);
        $student->save();

        return new StudentResource($student);
    }

    public function update(Request $request, Student $student): StudentResource
    {
        $validated = $request->validate([
            'desc' => 'string|max:1000',
            'user_id' => 'integer',
        ]);

        $student->update($validated);
        $authUser = $request->user();

        if ($this->isStudent($authUser)) {
            if ($authUser->student->id !== $student->id) {
                return response()->json(['message' => 'You do not have permission to update this student.'], 403);
            }
        }

        if ($request->has('user_id')) {
            $newUser = User::findOrFail($validated['user_id']);

            if ($newUser->student()->exists() || $newUser->employer()->exists()) {
                return response()->json(['message' => 'User is already associated.'], 409);
            }

            $student->user()->associate($newUser);
        }

        $student->update($validated);
        $student->save();

        return new StudentResource($student);
    }

    public function destroy(Student $student)
    {
        return $student->delete();
    }
}
