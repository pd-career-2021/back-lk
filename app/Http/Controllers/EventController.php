<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Library\ApiHelpers;
use App\Models\Event;
use App\Models\Employer;
use App\Models\Audience;

class EventController extends Controller
{
    public function index()
    {
        return Event::all();
    }

    public function show($id)
    {
        return Event::find($id);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            
            'title' => 'required|string|max:45',
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'date' => 'required|date',
            'id_audience' => 'required|integer',
            'description' => 'required|string|max:1000',
            
            
            
        ]);
        if ($validator->fails()) {
        return $validator->errors()->all();
        }

        return Event::create($request->all());
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            
            'title' => 'string|max:45',
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'date' => 'date',
            'id_audience' => 'integer',
            'description' => 'string|max:1000',
            
            
            
        ]);
        if ($validator->fails()) {
        return $validator->errors()->all();
        }
    }
    
    public function destroy($id)
    {
        return Event::destroy($id);
    }
}
