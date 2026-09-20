<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MainCategory;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Slider;
use App\Models\Post;
use App\Models\Event;
use App\Traits\HasSeo;
use Artesaos\SEOTools\Facades\JsonLd;

class HomeController extends Controller
{
    use HasSeo;

    public function index()
    {
        $siteName = Setting::get('site_name', config('app.name'));
        $desc     = Setting::get('about_desc', '');

        // the home page is the one page that should carry the full brand + what-we-do title (Settings → SEO)
        $this->setSeo(
            title:          Setting::get('meta_title') ?: $siteName,
            description:    $desc ? \Str::limit(strip_tags($desc), 155) : '',
            image:          (string) (Setting::get('og_image') ?: Setting::get('site_logo')),
            appendSiteName: false,
        );
        // structured data names the organisation, not the long page title
        JsonLd::setTitle($siteName);
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

        // the hero slider: export / saw-cut / top-cut
        $mainCategories = MainCategory::active()->ordered()->with('media')
            ->withCount(['products as active_products_count' => fn ($q) => $q->where('is_active', true)])
            ->get();

        $latestPosts = Post::published()
            ->with('media')
            ->limit(3)
            ->get();

        // Homepage exhibition banner: prefer what is running now / coming next,
        // otherwise the latest held exhibition. The banner is photo-led, so only
        // exhibitions that already have an image are eligible — an upcoming one
        // takes over automatically as soon as its cover is uploaded.
        $upcomingEvents = Event::published()
            ->current()
            ->with('media')
            ->limit(6)
            ->get()
            ->filter(fn (Event $e) => $e->has_image)
            ->values();

        if ($upcomingEvents->isEmpty()) {
            $upcomingEvents = Event::published()
                ->finished()
                ->with('media')
                ->limit(6)
                ->get()
                ->filter(fn (Event $e) => $e->has_image)
                ->values();
        }

        return view('front.home', compact(
            'sliders',
            'featuredProducts',
            'latestProducts',
            'rootCategories',
            'mainCategories',
            'latestPosts',
            'upcomingEvents',
        ));
    }
}
