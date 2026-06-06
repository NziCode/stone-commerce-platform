<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Language;
use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use App\Models\Event;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature   = 'sitemap:generate';
    protected $description = 'Generate sitemap.xml';

    public function handle(): void
    {
        $sitemap = Sitemap::create();
        $locales = Language::allActive()->pluck('code');

        // صفحه اصلی
        foreach ($locales as $locale) {
            $sitemap->add(
                Url::create(route('home', [], true))
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                    ->setPriority(1.0)
            );
        }

        // محصولات
        Product::active()->chunk(100, function ($products) use ($sitemap, $locales) {
            foreach ($products as $product) {
                foreach ($locales as $locale) {
                    $slug = $product->getTranslation('slug', $locale, false);
                    if ($slug) {
                        $sitemap->add(
                            Url::create(route('products.show', $slug))
                                ->setLastModificationDate($product->updated_at)
                                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                                ->setPriority(0.8)
                        );
                    }
                }
            }
        });

        // دسته‌بندی‌ها
        Category::active()->chunk(50, function ($categories) use ($sitemap, $locales) {
            foreach ($categories as $category) {
                foreach ($locales as $locale) {
                    $slug = $category->getTranslation('slug', $locale, false);
                    if ($slug) {
                        $sitemap->add(
                            Url::create(route('categories.show', $slug))
                                ->setLastModificationDate($category->updated_at)
                                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                                ->setPriority(0.7)
                        );
                    }
                }
            }
        });

        // اخبار
        Post::published()->chunk(50, function ($posts) use ($sitemap, $locales) {
            foreach ($posts as $post) {
                foreach ($locales as $locale) {
                    $slug = $post->getTranslation('slug', $locale, false);
                    if ($slug) {
                        $sitemap->add(
                            Url::create(route('posts.show', $slug))
                                ->setLastModificationDate($post->updated_at)
                                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                                ->setPriority(0.6)
                        );
                    }
                }
            }
        });

        // نمایشگاه‌ها
        Event::chunk(50, function ($events) use ($sitemap, $locales) {
            foreach ($events as $event) {
                foreach ($locales as $locale) {
                    $slug = $event->getTranslation('slug', $locale, false);
                    if ($slug) {
                        $sitemap->add(
                            Url::create(route('events.show', $slug))
                                ->setLastModificationDate($event->updated_at)
                                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                                ->setPriority(0.5)
                        );
                    }
                }
            }
        });

        // صفحات CMS
        Page::active()->chunk(20, function ($pages) use ($sitemap, $locales) {
            foreach ($pages as $page) {
                foreach ($locales as $locale) {
                    $slug = $page->getTranslation('slug', $locale, false);
                    if ($slug) {
                        $sitemap->add(
                            Url::create(route('pages.show', $slug))
                                ->setLastModificationDate($page->updated_at)
                                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                                ->setPriority(0.5)
                        );
                    }
                }
            }
        });

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated: ' . public_path('sitemap.xml'));
    }
}
