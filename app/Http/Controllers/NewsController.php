<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use App\Models\Employer;

class NewsController extends Controller
{
    public function index()
    {
        return News::all();
    }

    public function show($id)
    {
        return News::find($id);
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            
            'title' => 'required|string|max:45',
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'preview_text' => 'required|string|max:255',
            'detail_text' => 'required|string|max:1000',
            'id_employers' => 'required|integer',
            
            
        ]);
        if ($validator->fails()) {
        return $validator->errors()->all();
        }

        return News::create($request->all());
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            
            'title' => 'string|max:45',
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'preview_text' => 'string|max:255',
            'detail_text' => 'string|max:1000',
            'id_employers' => 'integer',
            
            
        ]);
        if ($validator->fails()) {
        return $validator->errors()->all();
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return News::destroy($id);
    }
}
