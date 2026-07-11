<?php

declare(strict_types=1);

namespace App\Support;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;

/**
 * Converts Markdown content into safe HTML for display.
 *
 * Content authored in the admin panel is stored as Markdown. Rendering
 * happens here so the front-end shows properly formatted articles
 * (headings, lists, links, code blocks) instead of raw text.
 */
final class Markdown
{
    private static ?MarkdownConverter $converter = null;

    public static function toHtml(?string $markdown): string
    {
        $markdown = trim((string) $markdown);

        if ($markdown === '') {
            return '';
        }

        return (string) self::converter()->convert($markdown)->getContent();
    }

    private static function converter(): MarkdownConverter
    {
        if (self::$converter instanceof MarkdownConverter) {
            return self::$converter;
        }

        $environment = new Environment([
            // Escape raw HTML in the source — protects against XSS even
            // though only trusted editors write content.
            'html_input' => 'escape',
            'allow_unsafe_links' => false,
        ]);
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new GithubFlavoredMarkdownExtension());

        return self::$converter = new MarkdownConverter($environment);
    }
}
