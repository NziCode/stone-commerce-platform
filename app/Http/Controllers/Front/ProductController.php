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

        // فیلتر وضعیت
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->available();
        }

        // فیلتر دسته‌بندی
        if ($request->filled('category')) {
            $category = Category::where('id', $request->category)->first();
            if ($category) {
                $categoryIds = $category->descendants()->pluck('id')->push($category->id);
                $query->whereHas('categories', fn($q) => $q->whereIn('categories.id', $categoryIds));
            }
        }

        // فیلتر قیمت
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // فیلتر کشور استخراج
        if ($request->filled('origin')) {
            $query->where('origin_country', $request->origin);
        }

        // جستجو
        if ($request->filled('q')) {
            $query->search($request->q);
        }

        // مرتب‌سازی
        match($request->sort) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'newest'     => $query->orderBy('created_at', 'desc'),
            'views'      => $query->orderBy('views_count', 'desc'),
            default      => $query->ordered(),
        };

        $products   = $query->paginate(12)->withQueryString();
        $categories = Category::active()->roots()->with('children')->ordered()->get();

        return view('front.products.index', compact('products', 'categories'));
    }

    public function show(string $slug)
    {
        $locale  = app()->getLocale();
        $product = Product::active()
            ->whereJsonContains("slug->{$locale}", $slug)
            ->with(['media', 'categories', 'attributes', 'approvedReviews'])
            ->firstOrFail();

        $product->incrementViews();
        $this->setProductSeo($product); // ← اضافه کن

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
