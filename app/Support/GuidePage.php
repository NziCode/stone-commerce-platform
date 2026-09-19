<?php

namespace App\Support;

use App\Models\Page;
use App\Models\Setting;
use DOMDocument;
use DOMElement;
use DOMNode;

/**
 * Data behind the "guide" page template — a step-by-step page (buying guide,
 * payment process, ...) with a "talk to our team" panel (WhatsApp / call /
 * choose a stone).
 *
 * The steps are authored in the normal rich-text editor of the CMS page: an
 * ordered list whose items start with a bold title —
 *
 *     <p>intro</p>
 *     <ol><li><strong>Choose your stone</strong> — details…</li> …</ol>
 *     <p>closing note</p>
 *
 * Whatever comes before the list is the intro, whatever comes after it a note.
 * Content without a list is shown as plain text.
 */
class GuidePage
{
    public static function build(Page $page, string $locale): array
    {
        $parts = static::parse((string) $page->getTranslation('content', $locale));
        $message = __('messages.gd_wa_message');

        return [
            'page'    => $page,
            'locale'  => $locale,
            'intro'   => $parts['intro'],
            'steps'   => $parts['steps'],
            'outro'   => $parts['outro'],
            'hero'    => $page->coverUrlFor('hero'),
            'contact' => [
                'whatsapp' => static::whatsappUrl($message),
                'phone'    => display_phone(Setting::get('site_phone')),
                'hours'    => Setting::get('site_working_hours'),
            ],
        ];
    }

    /**
     * @return array{intro:string, steps:array<int,array{title:?string, body:string}>, outro:string}
     */
    public static function parse(string $html): array
    {
        $html = trim($html);

        if ($html === '') {
            return ['intro' => '', 'steps' => [], 'outro' => ''];
        }

        $doc = new DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);
        // the meta tag makes libxml read the fragment as UTF-8
        $doc->loadHTML('<?xml encoding="UTF-8"><body>' . $html . '</body>');
        libxml_clear_errors();

        $body = $doc->getElementsByTagName('body')->item(0);
        $list = null;
        $intro = '';
        $outro = '';

        foreach (iterator_to_array($body->childNodes) as $node) {
            if ($list === null && $node instanceof DOMElement && strtolower($node->tagName) === 'ol') {
                $list = $node;
                continue;
            }

            if ($list === null) {
                $intro .= static::outer($doc, $node);
            } else {
                $outro .= static::outer($doc, $node);
            }
        }

        if ($list === null) {
            return ['intro' => $html, 'steps' => [], 'outro' => ''];
        }

        $steps = [];

        foreach ($list->childNodes as $item) {
            if (! $item instanceof DOMElement || strtolower($item->tagName) !== 'li') {
                continue;
            }

            $steps[] = static::step($doc, $item);
        }

        return ['intro' => trim($intro), 'steps' => $steps, 'outro' => trim($outro)];
    }

    /** One <li>: the first <strong>/<b> is the title, the rest is the description. */
    private static function step(DOMDocument $doc, DOMElement $li): array
    {
        $title = null;

        foreach (['strong', 'b'] as $tag) {
            $found = $li->getElementsByTagName($tag)->item(0);

            if ($found) {
                $title = trim($found->textContent);
                $found->parentNode->removeChild($found);
                break;
            }
        }

        $body = '';
        foreach ($li->childNodes as $child) {
            $body .= static::outer($doc, $child);
        }

        // drop the dash / colon that used to follow the bold title
        $body = trim(preg_replace('/^(\s|&nbsp;|<br\s*\/?>|[—–\-:：])+/u', '', trim($body)));

        return ['title' => $title !== '' ? $title : null, 'body' => $body];
    }

    private static function outer(DOMDocument $doc, DOMNode $node): string
    {
        return (string) $doc->saveHTML($node);
    }

    /**
     * wa.me link with a prefilled message. Uses the "WhatsApp" social setting when
     * it is filled in (a number or a wa.me link), otherwise the site phone number.
     */
    public static function whatsappUrl(string $message = ''): ?string
    {
        $configured = trim((string) Setting::get('social_whatsapp', ''));

        if (preg_match('#^https?://#i', $configured)) {
            $base = $configured;
        } else {
            $digits = static::internationalDigits($configured !== '' ? $configured : (string) Setting::get('site_phone', ''));

            if ($digits === '') {
                return null;
            }

            $base = "https://wa.me/{$digits}";
        }

        // the text parameter only means something on chat links, not on group invites
        if ($message === '' || ! preg_match('#(wa\.me|api\.whatsapp\.com/send|web\.whatsapp\.com/send)#i', $base)) {
            return $base;
        }

        return $base . (str_contains($base, '?') ? '&' : '?') . 'text=' . rawurlencode($message);
    }

    /** "+98 914 …" / "0914 …" / "98914…" → "98914…" (wa.me wants country code + number, digits only). */
    private static function internationalDigits(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone);

        // an Iranian mobile number written the local way (09xx…) → 989xx…
        if (strlen($digits) === 11 && str_starts_with($digits, '09')) {
            $digits = '98' . substr($digits, 1);
        }

        return $digits;
    }
}
