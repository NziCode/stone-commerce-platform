<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Traits\HasSeo;

class ProductController extends Controller
{
    use HasSeo;

    public function index(Request $request)
    {
        $query = Product::active()
            ->with(['media', 'categories', 'attributes'])
            ->ordered();

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Category filter
        if ($request->filled('category')) {
            $category = Category::find($request->category);
            if ($category) {
                $categoryIds = $category->descendants()->pluck('id')->push($category->id);
                $query->whereHas('categories', fn($q) => $q->whereIn('categories.id', $categoryIds));
            }
        }

        // Price filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Search — blade uses 'search' param
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
        $sidebarCategories = Category::active()->roots()->with('children')->ordered()->get();

        return view('front.products.index', compact('products', 'sidebarCategories'));
    }

    public function category(Request $request, string $slug)
    {
        $locale   = app()->getLocale();
        $category = Category::active()
            ->whereJsonContains("slug->{$locale}", $slug)
            ->firstOrFail();

        $query = Product::active()
            ->with(['media', 'categories', 'attributes'])
            ->whereHas('categories', function ($q) use ($category) {
                $ids = $category->descendants()->pluck('id')->push($category->id);
                $q->whereIn('categories.id', $ids);
            });

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
        $sidebarCategories = Category::active()->roots()->with('children')->ordered()->get();

        return view('front.products.index', compact('products', 'sidebarCategories', 'category'));
    }

    public function show(string $slug)
    {
        $locale  = app()->getLocale();
        $product = Product::active()
            ->whereJsonContains("slug->{$locale}", $slug)
            ->with(['media', 'categories', 'attributes', 'approvedReviews'])
            ->firstOrFail();

        $product->incrementViews();
        $this->setProductSeo($product);

        $relatedProducts = Product::active()
            ->available()
            ->whereHas('categories', fn($q) =>
            $q->whereIn('categories.id', $product->categories->pluck('id'))
            )
            ->where('id', '!=', $product->id)
            ->with('media')
            ->limit(6)
            ->get();

        return view('front.products.show', compact('product', 'relatedProducts'));
    }
}
