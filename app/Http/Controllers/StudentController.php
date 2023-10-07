<?php

namespace App\Http\Controllers;

use App\Http\Library\ApiHelpers;
use App\Http\Resources\StudentCollection;
use App\Http\Resources\StudentResource;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    use ApiHelpers;

    public function index(): StudentCollection
    {
        return new StudentCollection(Student::all());
    }

    public function store(Request $request): StudentResource
    {
        $validated = $request->validate([
            'desc' => 'required|string|max:1000',
            'user_id' => 'required|integer',
        ]);
        $student = Student::create($validated);

        return new StudentResource($student);
    }

    public function show(Student $student): StudentResource
    {
        return new StudentResource($student);
    }

    public function update(Request $request, Student $student): StudentResource
    {
        $$validated = $request->validate([
            'desc' => 'string|max:1000',
            'user_id' => 'integer',
        ]);
        $student->update($validated);

        return new StudentResource($student);

            if (!$this->isAuthorizedToUpdate($request->user(), $student)) {
                return response(['message' => 'You do not have permission to do this.'], Response::HTTP_UNAUTHORIZED);
            }
        
            $validatedData = $request->validated();
        
            if ($request->has('user_id')) {
                $user = User::find($validatedData['user_id']);
                if (!$user) {
                    return response(['message' => 'User not found.'], Response::HTTP_NOT_FOUND);
                }
        
                if ($user->student()->exists() || $user->employer()->exists()) {
                    return response(['message' => 'User already associated.'], Response::HTTP_UNAUTHORIZED);
                }
        
                $student->user()->associate($user);
            }

            $student->update($validatedData);
        
            return new StudentResource($student);
        }
        
        private function isAuthorizedToUpdate($user, $student): bool
        {
            if ($this->isStudent($user)) {
                return $user->id === $student->user_id;
            }
        
            return true;
        }

    public function destroy(Student $student)
    {
        return $student->delete();
    }
}
