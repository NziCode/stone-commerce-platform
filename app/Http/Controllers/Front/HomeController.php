<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Slider;
use App\Models\Post;
use App\Models\Event;
use App\Traits\HasSeo;

class HomeController extends Controller
{
    use HasSeo;

    public function index()
    {
        $siteName = Setting::get('site_name', config('app.name'));
        $desc     = Setting::get('about_desc', '');

        $this->setSeo(
            title:          $siteName,
            description:    $desc ? \Str::limit(strip_tags($desc), 155) : '',
            image:          Setting::get('og_image') ?: Setting::get('site_logo'),
            appendSiteName: false,
        );
        $sliders = Slider::active()->get();

        $featuredProducts = Product::active()
            ->available()
            ->featured()
            ->with(['media', 'categories', 'attributes', 'attributes.attribute'])
            ->ordered()
            ->limit(8)
            ->get();

        $latestProducts = Product::active()
            ->available()
            ->with(['media', 'categories', 'attributes', 'attributes.attribute'])
            ->latest()
            ->limit(8)
            ->get();

        $rootCategories = Category::active()
            ->roots()
            ->with('media')
            ->ordered()
            ->get();

        $latestPosts = Post::published()
            ->with('media')
            ->limit(3)
            ->get();

        // This site shows our own exhibition participation history rather than
        // a forward-looking events calendar, so prefer an ongoing exhibition
        // if one is currently running, otherwise fall back to the most
        // recently finished one.
        $upcomingEvents = Event::ongoing()
            ->with('media')
            ->orderBy('starts_at', 'desc')
            ->limit(3)
            ->get();

        if ($upcomingEvents->isEmpty()) {
            $upcomingEvents = Event::finished()
                ->with('media')
                ->orderBy('ends_at', 'desc')
                ->limit(3)
                ->get();
        }

        return view('front.home', compact(
            'sliders',
            'featuredProducts',
            'latestProducts',
            'rootCategories',
            'latestPosts',
            'upcomingEvents',
        ));
    }
}
