<?php

namespace App\Http\Controllers;

use App\Http\Library\ApiHelpers;
use App\Http\Resources\NewsCollection;
use App\Http\Resources\NewsResource;
use App\Models\Employer;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    use ApiHelpers;

    public function index(): NewsCollection
    {
        return new NewsCollection(News::all());
    }

    public function show(News $news): NewsResource
    {
        return new NewsResource($news);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:45',
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'preview_text' => 'required|string|max:255',
            'detail_text' => 'required|string|max:1000',
            'employer_id' => 'integer',
        ]);

        $user = $request->user();

        if ($this->isEmployer($user)) {
            $employer = Employer::where('user_id', $user->id)->first();
        } else {
            $employer = Employer::findOrFail($request->input('employer_id'));
        }

        $news = News::create($validated);
        $news->employer()->associate($employer);

        if ($request->hasFile('image')) {
            $news->img_path = $request->file('image')->store('img/news' . $news->id, 'public');
        }

        $news->save();

        return new NewsResource($news);
    }

    public function update(Request $request, News $news): NewsResource
    {
        $validated = $request->validate([
            'title' => 'string|max:45',
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'preview_text' => 'string|max:255',
            'detail_text' => 'string|max:1000',
            'employer_id' => 'integer',
        ]);

        $user = $request->user();

        if ($this->isEmployer($user)) {
            $employer = Employer::where('user_id', $user->id)->first();
            if ($news->employer_id !== $employer->id) {
                return response()->json(['message' => 'You do not have permission to do this.'], 403);
            }
        }

        if ($this->isAdmin($user) && $request->has('employer_id')) {
            $employer = Employer::findOrFail($validated['employer_id']);
            $news->employer()->associate($employer);
        }

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($news->img_path);
            $news->img_path = $request->file('image')->store('img/news' . $news->id, 'public');
        }

        $news->update($validated);
        $news->save();

        return new NewsResource($news);
    }

    public function destroy(Request $request, News $news)
    {
        $user = $request->user();

        if ($this->isEmployer($user)) {
            $employer = Employer::where('user_id', $user->id)->first();

            if ($news->employer_id !== $employer->id) {
                return response(['message' => 'You do not have permission to do this.'], 403);
            }
        }

        Storage::disk('public')->delete($news->img_path);
        return $news->delete();
    }
}
