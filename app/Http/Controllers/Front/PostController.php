<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Traits\HasSeo;

class PostController extends Controller
{
    use HasSeo;

    public function index()
    {
        $posts = Post::published()
            ->with('media')
            ->latest('published_at')
            ->paginate(9);

        return view('front.posts.index', compact('posts'));
    }

    public function show(string $slug)
    {
        $locale = app()->getLocale();
        $post   = Post::published()
            ->whereJsonContains("slug->{$locale}", $slug)
            ->with('media')
            ->firstOrFail();

        $post->incrementViews();
        $this->setPostSeo($post); // ← اضافه کن

        $relatedPosts = Post::published()
            ->where('id', '!=', $post->id)
            ->with('media')
            ->limit(3)
            ->get();

        return view('front.posts.show', compact('post', 'relatedPosts'));
    }
}
