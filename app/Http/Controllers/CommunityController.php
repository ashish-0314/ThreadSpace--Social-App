<?php

namespace App\Http\Controllers;

use App\Models\Community;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CommunityController extends Controller
{
    public function index()
    {
        $communities = Community::orderBy('members_count', 'desc')->get();

        // Manually attach post counts (withCount is not supported in MongoDB)
        $communities->each(function ($community) {
            $community->posts_count = $community->posts()->count();
        });

        return view('communities.index', compact('communities'));
    }

    public function create()
    {
        return view('communities.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:communities',
            'description' => 'required|string',
            'rules' => 'nullable|string',
        ]);

        $community = Community::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'],
            'rules' => $validated['rules'],
            'creator_id' => auth()->id(),
            'moderators' => [auth()->id()],
            'members_count' => 1,
        ]);

        $user = auth()->user();
        $joined = $user->joined_communities ?? [];
        $joined[] = $community->id;
        $user->joined_communities = array_unique($joined);
        $user->save();

        return redirect()->route('communities.show', $community->slug)->with('success', 'Community created!');
    }

    public function show($identifier)
    {
        $community = Community::where('slug', $identifier)->orWhere('_id', $identifier)->firstOrFail();
        $posts = $community->posts()->with('user')->orderBy('created_at', 'desc')->paginate(15);

        $userVotes = [];
        if (auth()->check()) {
            $ids = $posts->pluck('id')->map(fn($id) => (string)$id)->toArray();
            Vote::where('user_id', auth()->id())
                ->where('votable_type', 'App\Models\Post')
                ->whereIn('votable_id', $ids)
                ->get(['votable_id', 'value'])
                ->each(fn($v) => $userVotes[(string)$v->votable_id] = (int)$v->value);
        }

        return view('communities.show', compact('community', 'posts', 'userVotes'));
    }

    public function edit(Community $community)
    {
        if (!in_array(auth()->id(), $community->moderators ?? [])) {
            abort(403);
        }
        return view('communities.edit', compact('community'));
    }

    public function update(Request $request, Community $community)
    {
        if (!in_array(auth()->id(), $community->moderators ?? [])) {
            abort(403);
        }

        $validated = $request->validate([
            'description' => 'required|string',
            'rules' => 'nullable|string',
        ]);

        $community->update($validated);

        return redirect()->route('communities.show', $community->slug)->with('success', 'Community updated!');
    }

    public function join(Community $community)
    {
        $user = auth()->user();
        $joined = $user->joined_communities ?? [];
        if (!in_array($community->id, $joined)) {
            $joined[] = $community->id;
            $user->joined_communities = $joined;
            $user->save();

            $community->increment('members_count');
        }

        return back()->with('success', 'Joined community!');
    }

    public function leave(Community $community)
    {
        $user = auth()->user();
        $joined = $user->joined_communities ?? [];
        
        if (($key = array_search($community->id, $joined)) !== false) {
            unset($joined[$key]);
            $user->joined_communities = array_values($joined);
            $user->save();

            $community->decrement('members_count');
        }

        return back()->with('success', 'Left community!');
    }
}
