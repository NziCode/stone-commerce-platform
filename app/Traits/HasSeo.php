<?php

namespace App\Traits;

use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\JsonLd;
use App\Models\Setting;

trait HasSeo
{
    /**
     * @param  bool  $appendSiteName  SEOMeta appends " {separator} {site name}" to $title by
     *                                default. Pass false when $title already includes the site
     *                                name itself (e.g. the homepage, category/search listings),
     *                                otherwise it shows up twice in the <title> tag.
     */
    protected function setSeo(
        string $title,
        string $description = '',
        string $image = '',
        string $type = 'website',
        array $schemaData = [],
        bool $appendSiteName = true
    ): void {
        $siteName = Setting::get('site_name', config('app.name'));

        SEOMeta::setTitle($title, $appendSiteName);
        SEOMeta::setDescription($description);

        OpenGraph::setTitle($title);
        OpenGraph::setDescription($description);
        OpenGraph::setType($type);
        if ($image) {
            OpenGraph::addImage($image);
        }

        JsonLd::setTitle($title);
        JsonLd::setDescription($description);
        if ($schemaData) {
            foreach ($schemaData as $key => $value) {
                JsonLd::addValue($key, $value);
            }
        }
    }

    protected function setProductSeo($product): void
    {
        $locale      = app()->getLocale();
        $title       = $product->getTranslation('meta_title', $locale)
            ?? $product->getTranslation('name', $locale);
        $description = $product->getTranslation('meta_description', $locale)
            ?? $product->getTranslation('short_description', $locale)
            ?? '';

        $this->setSeo(
            title:       $title,
            description: $description,
            image:       $product->main_image_url,
            type:        'product',
            schemaData: [
                '@type'       => 'Product',
                'name'        => $title,
                'description' => $description,
                'image'       => $product->main_image_url,
                'sku'         => $product->sku,
                'offers'      => [
                    '@type'         => 'Offer',
                    'price'         => $product->price,
                    'priceCurrency' => 'IRR',
                    'availability'  => $product->isAvailable()
                        ? 'https://schema.org/InStock'
                        : 'https://schema.org/OutOfStock',
                ],
            ]
        );
    }

    protected function setCategorySeo($category): void
    {
        $locale      = app()->getLocale();
        $title       = $category->getTranslation('meta_title', $locale)
            ?? $category->getTranslation('name', $locale);
        $description = $category->getTranslation('meta_description', $locale)
            ?? $category->getTranslation('description', $locale)
            ?? '';

        $this->setSeo(
            title:       $title,
            description: $description,
            image:       $category->image_url,
        );
    }

    protected function setPostSeo($post): void
    {
        $locale      = app()->getLocale();
        $title       = $post->getTranslation('meta_title', $locale)
            ?? $post->getTranslation('title', $locale);
        $description = $post->getTranslation('meta_description', $locale)
            ?? $post->getTranslation('excerpt', $locale)
            ?? '';

        $this->setSeo(
            title:       $title,
            description: $description,
            image:       $post->cover_url,
            type:        'article',
        );
    }
}
