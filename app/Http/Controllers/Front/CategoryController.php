<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Traits\HasSeo;

class CategoryController extends Controller
{
    use HasSeo;

    public function index()
    {
        $categories = Category::active()
            ->roots()
            ->with(['media', 'children' => fn($q) => $q->active()->with('media')])
            ->ordered()
            ->get();

        return view('front.categories.index', compact('categories'));
    }

    public function show(string $slug, Request $request)
    {
        $locale   = app()->getLocale();
        $category = Category::active()
            ->whereJsonContains("slug->{$locale}", $slug)
            ->with(['media', 'children'])
            ->firstOrFail();

        $this->setCategorySeo($category); // ← اضافه کن

        // همه زیردسته‌ها
        $categoryIds = $category->descendants()->pluck('id')->push($category->id);

        $query = Product::active()
            ->whereHas('categories', fn($q) => $q->whereIn('categories.id', $categoryIds))
            ->with(['media', 'attributes'])
            ->ordered();

        // فیلتر وضعیت
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->available();
        }

        // فیلتر قیمت
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // مرتب‌سازی
        match($request->sort) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'newest'     => $query->orderBy('created_at', 'desc'),
            default      => $query->ordered(),
        };

        $products = $query->paginate(12)->withQueryString();

        return view('front.categories.show', compact('category', 'products'));
    }
}
