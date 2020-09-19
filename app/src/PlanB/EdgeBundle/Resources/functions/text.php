<?php

/**
 * This file is part of the planb project.
 *
 * (c) jmpantoja <jmpantoja@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

if (!function_exists('strip_tags_content')) {

    /**
     * @param string $text
     * @param string $tags
     * @param false $invert
     * @return string
     */
    function strip_tags_content(string $text, string $tags = '', $invert = FALSE)
    {

        preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($tags), $tags);
        $tags = array_unique($tags[1]);

        if (is_array($tags) and count($tags) > 0) {
            if ($invert == FALSE) {
                $text = preg_replace('@<(?!(?:' . implode('|', $tags) . ')\b)(\w+)\b.*?>.*?</\1>@si', '', $text);
            } else {
                $text = preg_replace('@<(' . implode('|', $tags) . ')\b.*?>.*?</\1>@si', '', $text);
            }
        } elseif ($invert == FALSE) {
            $text = preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $text);
        }

        $text = strip_tags($text);
        return preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', $text);
    }
}
