<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Community;
use Illuminate\Http\Request;
use ImageKit\ImageKit;

class PostController extends Controller
{
    public function feed(Request $request)
    {
        $sort = $request->query('sort', 'latest');

        $query = Post::with(['user', 'community', 'originalPost.user', 'originalPost.community']);

        if (auth()->check() && auth()->user()->joined_communities) {
            $query->whereIn('community_id', auth()->user()->joined_communities);
        }

        if ($sort === 'top') {
            $query->orderBy('upvotes', 'desc');
        } elseif ($sort === 'trending') {
            $query->orderBy('quality_score', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $posts = $query->paginate(15);
        return view('home', compact('posts', 'sort'));
    }

    public function create(Community $community)
    {
        return view('posts.create', compact('community'));
    }

    public function store(Request $request, Community $community)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:text,image,link',
            'content' => 'required_if:type,text|required_if:type,link|string|nullable',
            'intent' => 'required|in:Question,Discussion,Tutorial,Opinion',
            'image' => 'required_if:type,image|image|max:5120', // 5MB max
        ]);

        $imageUrl = null;
        if ($request->hasFile('image') && $validated['type'] === 'image') {
            try {
                $imageKit = new ImageKit(
                    config('imagekit.public_key'),
                    config('imagekit.private_key'),
                    config('imagekit.url_endpoint')
                );

                $file = $request->file('image');
                $uploadResponse = $imageKit->uploadFile([
                    'file' => base64_encode(file_get_contents($file->path())),
                    'fileName' => time() . '_' . $file->getClientOriginalName(),
                    'folder' => '/threadspace_posts'
                ]);

                if (isset($uploadResponse->result->url)) {
                    $imageUrl = $uploadResponse->result->url;
                }
            } catch (\Exception $e) {
                return back()->withInput()->withErrors(['image' => 'Failed to upload image.']);
            }
        }

        $post = Post::create([
            'community_id' => $community->id,
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'type' => $validated['type'],
            'content' => $validated['content'] ?? '',
            'image_url' => $imageUrl,
            'intent' => $validated['intent'],
            'upvotes' => 0,
            'downvotes' => 0,
            'quality_score' => 0,
            'engagement_time' => 0,
        ]);

        return redirect()->route('posts.show', $post)->with('success', 'Post created successfully!');
    }

    public function show(Post $post)
    {
        $post->load(['user', 'community', 'originalPost.user', 'originalPost.community']);
        $comments = $post->comments()
            ->with(['user', 'replies.user'])
            ->whereNull('parent_id')
            ->orderBy('is_best_answer', 'desc')
            ->orderBy('upvotes', 'desc')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('posts.show', compact('post', 'comments'));
    }

    public function summarize(Post $post)
    {
        $summary = "This is a mocked AI summary of the thread. The discussion primarily revolves around " . $post->title . ". Users provided insights on " . $post->intent . " and shared various perspectives.";
        return response()->json(['summary' => $summary]);
    }

    // ── Repost ────────────────────────────────────────────────
    public function repostForm(Post $post)
    {
        // Load the original if this is already a repost
        $original = $post->is_repost ? $post->originalPost : $post;
        $communities = Community::orderBy('name')->get();
        return view('posts.repost', compact('original', 'communities'));
    }

    public function repostStore(Request $request, Post $post)
    {
        $original = $post->is_repost ? $post->originalPost : $post;

        $validated = $request->validate([
            'community_id'   => 'required|string',
            'title'          => 'required|string|max:255',
            'repost_comment' => 'nullable|string|max:1000',
            'intent'         => 'required|in:Question,Discussion,Tutorial,Opinion',
        ]);

        $repost = Post::create([
            'community_id'    => $validated['community_id'],
            'user_id'         => auth()->id(),
            'title'           => $validated['title'],
            'type'            => 'repost',
            'content'         => '',
            'intent'          => $validated['intent'],
            'is_repost'       => true,
            'original_post_id'=> $original->id,
            'repost_comment'  => $validated['repost_comment'] ?? '',
            'upvotes'         => 0,
            'downvotes'       => 0,
            'quality_score'   => 0,
        ]);

        return redirect()->route('posts.show', $repost)
            ->with('success', 'Reposted successfully!');
    }
}
