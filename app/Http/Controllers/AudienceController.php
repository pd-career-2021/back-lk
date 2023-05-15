<?php

namespace App\Http\Controllers;

use App\Http\Resources\AudienceCollection;
use App\Http\Resources\AudienceResource;
use Illuminate\Http\Request;
use App\Models\Audience;
use Illuminate\Support\Facades\Validator;

class AudienceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new AudienceCollection(Audience::all());
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:45'
        ]);

        if ($validator->fails()) {
            return $validator->errors()->all();
        }

        $audience = new Audience($request->all());
        $audience->save();

        return new AudienceResource($audience);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new AudienceResource(Audience::find($id));
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:45'
        ]);

        if ($validator->fails()) {
            return $validator->errors()->all();
        }

        $audience = Audience::find($id);
        $audience->update($request->all())->save();

        return new AudienceResource($audience);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Audience::destroy($id);
    }
}
