<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Post;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->get('q', '');

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
