<?php

namespace App\Http\Controllers;

use App\Http\Library\ApiHelpers;
use Illuminate\Http\Request;
use App\Models\Employer;
use App\Models\User;
use App\Models\EmployerStatus;
use App\Models\Organization;

class EmployerController extends Controller
{
    use ApiHelpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Employer::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $employer = new Employer($request->all());
        $user = User::find($request->input('user_id'));
        if ($user) {
            if ($user->student()->exists() || $user->employer()->exists())  {
               return response([
                    'message' => 'User already associated.'
                ], 401); 
            } else {
                $employer->user()->associate($user);
            }
        }
        
        $employerStatus = EmployerStatus::find($request->input('employer_status_id'));
        if(!$employerStatus) {
            return response([
                'message' => 'Employer status not found.'
            ], 401);
        } else {
            $employer->employer_status()->associate($employerStatus);
        }

        $organization = Organization::find($request->input('organization_id'));
        if(!$organization) {
            return response([
                'message' => 'Organization not found.'
            ], 401);
        } else {
            $employer->organization()->associate($organization);
        }
        
        $employer->save();
        return $employer;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Employer::find($id);
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
        $employer = Employer::find($id);
        $user = $request->user();
        if ($this->isEmployer($user)) {
            if ($request->has('employer_status_id')) {
                $employerStatus = EmployerStatus::find($request->input('employer_status_id'));
                if(!$employerStatus) {
                    return response([
                        'message' => 'Employer status not found.'
                    ], 401);
                } else {
                    $employer->employer_status()->associate($employerStatus);
                }
            }
            $employer->update($request->all());

            $employer->save();
            return $employer;
        }

        if ($request->has('user_id')) {
            $user = User::find($request->input('user_id'));
            if ($user) {
                if ($user->student()->exists() || $user->employer()->exists()) {
                    return response([
                        'message' => 'User already associated.'
                    ], 401);
                } 
                else {
                    $employer->user()->associate($user);
                }
            }
        }
                
        if ($request->has('employer_status_id')) {
            $employerStatus = EmployerStatus::find($request->input('employer_status_id'));
            if(!$employerStatus) {
                return response([
                    'message' => 'Employer status not found.'
                ], 401);
            } else {
                $employer->employer_status()->associate($employerStatus);
            }
        }
        
        if ($request->has('organization_id')) {
            $organization = Organization::find($request->input('organization_id'));
            if(!$organization) {
                return response([
                    'message' => 'Organization not found.'
                ], 401);
            } else {
                $employer->organization()->associate($organization);
            }
        }
        
        $employer->update($request->all());

        $employer->save();
        return $employer;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Employer::destroy($id);
    }
}