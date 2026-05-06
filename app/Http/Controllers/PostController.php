<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Community;
use App\Models\Vote;
use Illuminate\Http\Request;
use ImageKit\ImageKit;

class PostController extends Controller
{
    public function feed(Request $request)
    {
        $sort = $request->query('sort', 'latest');

        $query = Post::with(['user', 'community', 'originalPost.user', 'originalPost.community']);

        if (auth()->check() && !empty(auth()->user()->joined_communities)) {
            $query->where(function ($q) {
                $q->whereIn('community_id', auth()->user()->joined_communities)
                  ->orWhereNull('community_id');
            });
        }

        if ($sort === 'top') {
            $query->orderBy('upvotes', 'desc');
        } elseif ($sort === 'trending') {
            $query->orderBy('quality_score', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $posts = $query->paginate(15);

        // Build a map of post_id => user's vote value (1 or -1) for highlighting
        $userVotes = [];
        if (auth()->check()) {
            $ids = $posts->pluck('id')->map(fn($id) => (string)$id)->toArray();
            Vote::where('user_id', auth()->id())
                ->where('votable_type', 'App\Models\Post')
                ->whereIn('votable_id', $ids)
                ->get(['votable_id', 'value'])
                ->each(fn($v) => $userVotes[(string)$v->votable_id] = (int)$v->value);
        }

        return view('home', compact('posts', 'sort', 'userVotes'));
    }

    public function myPosts(Request $request)
    {
        $sort = $request->query('sort', 'latest');
        $query = Post::with(['community'])
            ->where('user_id', auth()->id());

        if ($sort === 'top') {
            $query->orderBy('upvotes', 'desc');
        } elseif ($sort === 'trending') {
            $query->orderBy('quality_score', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $posts = $query->paginate(15);
        return view('posts.my-posts', compact('posts', 'sort'));
    }

    public function create(Community $community)
    {
        return view('posts.create', compact('community'));
    }

    // Standalone create — no community required
    public function createStandalone()
    {
        $communities = Community::orderBy('name')->get();
        return view('posts.create-standalone', compact('communities'));
    }

    public function store(Request $request, Community $community)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:text,image,media,link',
            'content' => 'required_if:type,text|required_if:type,link|string|nullable',
            'intent' => 'required|in:Question,Discussion,Tutorial,Opinion',
            'media' => 'required_if:type,media|required_if:type,image|array|max:10',
            'media.*' => 'file|mimes:jpeg,png,jpg,gif,mp4,mov,avi,mp3,wav,webp|max:20480',
        ]);

        $mediaList = [];
        if ($request->hasFile('media') && in_array($validated['type'], ['image', 'media'])) {
            try {
                $imageKit = new ImageKit(
                    config('imagekit.public_key'),
                    config('imagekit.private_key'),
                    config('imagekit.url_endpoint')
                );

                foreach ($request->file('media') as $file) {
                    $mime = $file->getMimeType();
                    $mediaType = 'image';
                    if (str_starts_with($mime, 'video/')) $mediaType = 'video';
                    elseif (str_starts_with($mime, 'audio/')) $mediaType = 'audio';

                    $uploadResponse = $imageKit->uploadFile([
                        'file' => base64_encode(file_get_contents($file->path())),
                        'fileName' => time() . '_' . $file->getClientOriginalName(),
                        'folder' => '/threadspace_posts'
                    ]);

                    if (isset($uploadResponse->result->url)) {
                        $mediaList[] = [
                            'url' => $uploadResponse->result->url,
                            'type' => $mediaType,
                            'mime' => $mime,
                        ];
                    }
                }
            } catch (\Exception $e) {
                return back()->withInput()->withErrors(['media' => 'Failed to upload media: ' . $e->getMessage()]);
            }
        }

        $post = Post::create([
            'community_id' => $community->id,
            'user_id'      => auth()->id(),
            'title'        => $validated['title'],
            'type'         => $validated['type'] === 'image' ? 'media' : $validated['type'],
            'content'      => $validated['content'] ?? '',
            'media'        => $mediaList,
            'intent'       => $validated['intent'],
            'upvotes'      => 0,
            'downvotes'    => 0,
            'quality_score'=> 0,
            'engagement_time' => 0,
        ]);

        return redirect()->route('posts.show', $post)->with('success', 'Post created successfully!');
    }

    // Standalone store — community optional
    public function storeStandalone(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'type'         => 'required|in:text,image,media,link',
            'content'      => 'required_if:type,text|required_if:type,link|string|nullable',
            'intent'       => 'required|in:Question,Discussion,Tutorial,Opinion',
            'community_id' => 'nullable|string',
            'media'        => 'required_if:type,media|required_if:type,image|array|max:10',
            'media.*'      => 'file|mimes:jpeg,png,jpg,gif,mp4,mov,avi,mp3,wav,webp|max:20480',
        ]);

        $mediaList = [];
        if ($request->hasFile('media') && in_array($validated['type'], ['image', 'media'])) {
            try {
                $imageKit = new ImageKit(
                    config('imagekit.public_key'),
                    config('imagekit.private_key'),
                    config('imagekit.url_endpoint')
                );
                
                foreach ($request->file('media') as $file) {
                    $mime = $file->getMimeType();
                    $mediaType = 'image';
                    if (str_starts_with($mime, 'video/')) $mediaType = 'video';
                    elseif (str_starts_with($mime, 'audio/')) $mediaType = 'audio';

                    $uploadResponse = $imageKit->uploadFile([
                        'file'     => base64_encode(file_get_contents($file->path())),
                        'fileName' => time() . '_' . $file->getClientOriginalName(),
                        'folder'   => '/threadspace_posts',
                    ]);

                    if (isset($uploadResponse->result->url)) {
                        $mediaList[] = [
                            'url' => $uploadResponse->result->url,
                            'type' => $mediaType,
                            'mime' => $mime,
                        ];
                    }
                }
            } catch (\Exception $e) {
                return back()->withInput()->withErrors(['media' => 'Failed to upload media: ' . $e->getMessage()]);
            }
        }

        $post = Post::create([
            'community_id' => $validated['community_id'] ?? null,
            'user_id'      => auth()->id(),
            'title'        => $validated['title'],
            'type'         => $validated['type'] === 'image' ? 'media' : $validated['type'],
            'content'      => $validated['content'] ?? '',
            'media'        => $mediaList,
            'intent'       => $validated['intent'],
            'upvotes'      => 0,
            'downvotes'    => 0,
            'quality_score'=> 0,
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

        // Current user's vote on THIS post
        $userVote = null;
        if (auth()->check()) {
            $v = Vote::where('user_id', auth()->id())
                     ->where('votable_type', 'App\Models\Post')
                     ->where('votable_id', (string)$post->id)
                     ->first();
            $userVote = $v ? (int)$v->value : null;
        }

        return view('posts.show', compact('post', 'comments', 'userVote'));
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

        // Notify the original post owner
        if ($original->user_id && $original->user_id !== auth()->id()) {
            \App\Models\Notification::create([
                'user_id'      => $original->user_id,
                'from_user_id' => auth()->id(),
                'type'         => 'repost',
                'post_id'      => (string) $original->id,
            ]);
        }

        return redirect()->route('posts.show', $repost)
            ->with('success', 'Reposted successfully!');
    }

    // ── Edit & Update ──────────────────────────────────────────
    public function edit(Post $post)
    {
        abort_if(auth()->id() != $post->user_id, 403, 'You can only edit your own posts.');
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        abort_if(auth()->id() != $post->user_id, 403, 'You can only edit your own posts.');

        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'content'      => 'nullable|string',
            'intent'       => 'required|in:Question,Discussion,Tutorial,Opinion',
            'delete_media' => 'nullable|array',
            'delete_media.*'=> 'integer',
            'new_media'    => 'nullable|array|max:10',
            'new_media.*'  => 'file|mimes:jpeg,png,jpg,gif,mp4,mov,avi,mp3,wav,webp|max:20480',
        ]);

        // 1) Remove checked media items
        $mediaList = $post->media ?? [];
        if (!empty($validated['delete_media'])) {
            $toDelete = array_map('intval', $validated['delete_media']);
            $mediaList = array_values(
                array_filter($mediaList, fn($_, $i) => !in_array($i, $toDelete), ARRAY_FILTER_USE_BOTH)
            );
        }

        // 2) Upload new media files
        if ($request->hasFile('new_media')) {
            try {
                $imageKit = new \ImageKit\ImageKit(
                    config('imagekit.public_key'),
                    config('imagekit.private_key'),
                    config('imagekit.url_endpoint')
                );
                foreach ($request->file('new_media') as $file) {
                    $mime = $file->getMimeType();
                    $mediaType = 'image';
                    if (str_starts_with($mime, 'video/')) $mediaType = 'video';
                    elseif (str_starts_with($mime, 'audio/')) $mediaType = 'audio';

                    $uploadResponse = $imageKit->uploadFile([
                        'file'     => base64_encode(file_get_contents($file->path())),
                        'fileName' => time() . '_' . $file->getClientOriginalName(),
                        'folder'   => '/threadspace_posts',
                    ]);

                    if (isset($uploadResponse->result->url)) {
                        $mediaList[] = [
                            'url'  => $uploadResponse->result->url,
                            'type' => $mediaType,
                            'mime' => $mime,
                        ];
                    }
                }
            } catch (\Exception $e) {
                return back()->withInput()->withErrors(['new_media' => 'Upload failed: ' . $e->getMessage()]);
            }
        }

        $post->update([
            'title'   => $validated['title'],
            'content' => $validated['content'] ?? $post->content,
            'intent'  => $validated['intent'],
            'media'   => $mediaList,
        ]);

        return redirect()->route('posts.show', $post)->with('success', 'Post updated successfully!');
    }

    // ── Delete ─────────────────────────────────────────────────
    public function destroy(Post $post)
    {
        abort_if(auth()->id() != $post->user_id, 403, 'You can only delete your own posts.');
        $post->delete();
        return redirect()->route('home')->with('success', 'Post deleted.');
    }
}
