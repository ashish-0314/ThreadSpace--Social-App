<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Community;
use App\Models\User;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q', '');

        if (empty(trim($query))) {
            return response()->json([
                'posts' => [],
                'communities' => [],
                'users' => []
            ]);
        }

        // Search Posts (by title)
        $posts = Post::where('title', 'like', "%{$query}%")
                     ->select('_id', 'title', 'intent')
                     ->take(5)
                     ->get()
                     ->map(function ($post) {
                         return [
                             'id' => (string)$post->_id,
                             'title' => $post->title,
                             'intent' => $post->intent,
                             'url' => route('posts.show', $post)
                         ];
                     });

        // Search Communities (by name)
        $communities = Community::where('name', 'like', "%{$query}%")
                                ->select('_id', 'name', 'slug')
                                ->take(5)
                                ->get()
                                ->map(function ($community) {
                                    return [
                                        'id' => (string)$community->_id,
                                        'name' => 'c/' . $community->name,
                                        'url' => route('communities.show', $community->slug)
                                    ];
                                });

        // Search Users (by name)
        $users = User::where('name', 'like', "%{$query}%")
                     ->select('_id', 'name')
                     ->take(5)
                     ->get()
                     ->map(function ($user) {
                         return [
                             'id' => (string)$user->_id,
                             'name' => 'u/' . $user->name,
                             // If there are user profiles later, point there. For now, maybe just '#'
                             'url' => '#' 
                         ];
                     });

        return response()->json([
            'posts' => $posts,
            'communities' => $communities,
            'users' => $users
        ]);
    }
}
