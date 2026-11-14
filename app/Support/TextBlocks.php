<?php

namespace App\Support;

class TextBlocks
{
    /**
     * @return list<string>
     */
    public static function paragraphs(?string $text): array
    {
        $text = trim(str_replace("\r\n", "\n", (string) $text));

        if ($text === '') {
            return [];
        }

        $parts = preg_split("/\n\s*\n/", $text) ?: [];

        return array_values(array_filter(array_map('trim', $parts), fn (string $part) => $part !== ''));
    }

    /**
     * @return list<string>
     */
    public static function lines(?string $text): array
    {
        $parts = preg_split("/\r\n|\n/", (string) $text) ?: [];

        return array_values(array_filter(array_map('trim', $parts), fn (string $part) => $part !== ''));
    }
}
