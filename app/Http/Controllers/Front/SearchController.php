<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Post;
use App\Traits\HasSeo;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    use HasSeo;

    public function index(Request $request)
    {
        $query = $request->get('q', '');

        $this->setSeo(
            title: ($query ? "\"$query\" — " : '') . __('messages.search') . ' | ' . \App\Models\Setting::get('site_name', config('app.name')),
        );

        $products = collect();
        $posts    = collect();

        if (strlen($query) >= 2) {
            $products = Product::active()
                ->available()
                ->search($query)
                ->with('media')
                ->limit(12)
                ->get();

            $posts = Post::published()
                ->whereRaw("JSON_SEARCH(LOWER(title), 'one', ?) IS NOT NULL", ["%{$query}%"])
                ->with('media')
                ->limit(6)
                ->get();
        }

        return view('front.search', compact('query', 'products', 'posts'));
    }
}
