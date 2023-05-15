<?php

namespace App\Http\Controllers;

use App\Http\Library\ApiHelpers;
use App\Http\Resources\NewsCollection;
use App\Http\Resources\NewsResource;
use App\Models\Employer;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class NewsController extends Controller
{
    use ApiHelpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new NewsCollection(News::all());
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
            'title' => 'required|string|max:45',
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'preview_text' => 'required|string|max:255',
            'detail_text' => 'required|string|max:1000',
            'employer_id' => 'integer',
        ]);
        if ($validator->fails()) {
            return $validator->errors()->all();
        }

        $news = new News($request->all());
        $user = $request->user();

        if ($this->isEmployer($user)) {
            $employer = Employer::where('user_id', $user->id)->first();
        } else {
            $employer = Employer::find($request->input('employer_id'));
        }

        if (!$employer) {
            return response([
                'message' => 'Employer not found.'
            ], 401);
        } else {
            $news->employer()->associate($employer);
        }
        $news->save();

        if ($request->hasFile('image')) {
            $news->img_path = $request->file('image')->store('img/news' . $news->id, 'public');
        }
        $news->save();

        return new NewsResource($news);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new NewsResource(News::find($id));
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
            'title' => 'string|max:45',
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'preview_text' => 'string|max:255',
            'detail_text' => 'string|max:1000',
            'employer_id' => 'integer',
        ]);
        if ($validator->fails()) {
            return $validator->errors()->all();
        }

        $news = News::find($id);
        $user = $request->user();

        if ($this->isEmployer($user)) {
            $employer_id = Employer::where('user_id', $user->id)->first()->id;
            if (News::where('id', $id)->first()->employer_id != $employer_id) {
                return response([
                    'message' => 'You do not have permission to do this.'
                ], 401);
            }
        }

        if ($this->isAdmin($user)) {
            if ($request->has('employer_id')) {
                $employer = Employer::find($request->input('employer_id'));
                if (!$employer) {
                    return response([
                        'message' => 'Employer not found.'
                    ], 401);
                } else {
                    $news->employer()->associate($employer);
                }
            }
        }

        $news->update($request->all());

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($news->img_path);
            $news->img_path = $request->file('image')->store('img/news' . $news->id, 'public');
        }

        $news->save();

        return new NewsResource($news);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        if ($this->isEmployer($user)) {
            $employer_id = Employer::where('user_id', $user->id)->first()->id;
            if (News::where('id', $id)->first()->employer_id != $employer_id) {
                return response([
                    'message' => 'You do not have permission to do this.'
                ], 401);
            } else {
                Storage::disk('public')->delete(News::find($id)->img_path);
                return News::destroy($id);
            }
        } else if ($this->isAdmin($user)) {
            Storage::disk('public')->delete(News::find($id)->img_path);
            return News::destroy($id);
        }
    }
}
