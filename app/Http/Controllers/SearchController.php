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
        $query = trim($request->input('q', ''));

        // ── AJAX / JSON request (navbar live-search) ─────────────────
        if ($request->expectsJson() || $request->wantsJson()) {
            if (empty($query)) {
                return response()->json(['posts' => [], 'communities' => [], 'users' => []]);
            }

            $posts = Post::where('title', 'regexp', "/{$query}/i")
                ->select('_id', 'title', 'intent')->take(5)->get()
                ->map(fn($p) => ['id' => (string)$p->_id, 'title' => $p->title, 'intent' => $p->intent, 'url' => route('posts.show', $p)]);

            $communities = Community::where('name', 'regexp', "/{$query}/i")
                ->select('_id', 'name', 'slug')->take(5)->get()
                ->map(fn($c) => ['id' => (string)$c->_id, 'name' => 'c/' . $c->name, 'url' => route('communities.show', $c->slug)]);

            $users = User::where('name', 'regexp', "/{$query}/i")
                ->select('_id', 'name')->take(5)->get()
                ->map(fn($u) => ['id' => (string)$u->_id, 'name' => 'u/' . $u->name, 'url' => route('profile.show', $u->_id)]);

            return response()->json(compact('posts', 'communities', 'users'));
        }

        // ── Full-page browser request ─────────────────────────────────
        $posts       = collect();
        $communities = collect();
        $users       = collect();

        if (!empty($query)) {
            $posts = Post::where('title', 'regexp', "/{$query}/i")
                ->with('user', 'community')
                ->orderBy('created_at', 'desc')
                ->take(20)->get();

            $communities = Community::where('name', 'regexp', "/{$query}/i")
                ->take(10)->get();

            $users = User::where('name', 'regexp', "/{$query}/i")
                ->take(10)->get();
        }

        return view('search.index', compact('query', 'posts', 'communities', 'users'));
    }
}
