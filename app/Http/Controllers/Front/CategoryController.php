<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::active()->roots()->with('children')->ordered()->get();
        return view('front.categories.index', compact('categories'));
    }

    public function show(Request $request, string $slug)
    {
        $locale   = app()->getLocale();
        $category = Category::active()
            ->whereJsonContains("slug->{$locale}", $slug)
            ->with('parent', 'children')
            ->firstOrFail();

        // Include self + all descendants
        $categoryIds = $category->descendants()->pluck('id')->push($category->id);

        $query = Product::active()
            ->with(['media', 'categories'])
            ->whereHas('categories', fn($q) => $q->whereIn('categories.id', $categoryIds));

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Sort
        match($request->get('sort', 'latest')) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'featured'   => $query->orderBy('is_featured', 'desc')->orderBy('sort_order'),
            'name_asc'   => $query->orderByTranslation('name', 'asc'),
            default      => $query->orderBy('created_at', 'desc'),
        };

        $products          = $query->paginate(12)->withQueryString();
        $sidebarCategories = Category::active()->roots()
            ->with('children')
            ->withCount(['products as active_products_count' => fn($q) => $q->where('is_active', true)])
            ->ordered()->get();

        return view('front.products.index', compact('products', 'sidebarCategories', 'category'));
    }
}
