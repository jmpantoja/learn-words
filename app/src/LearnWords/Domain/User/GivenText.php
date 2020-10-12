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

namespace LearnWords\Domain\User;


class GivenText
{
    private ?string $text;

    public function __construct(?string $text)
    {
        $this->text = $this->normalize($text);

        $this->text = $text;
    }

    private function normalize(?string $text): ?string
    {
        if (is_null($text)) {
            return null;
        }

        $text = trim($text);
        if (empty($text)) {
            return null;
        }

        $text = strtolower($text);
        $words = preg_split('/\s+/', $text);

        return implode(' ', $words);
    }

    /**
     * @return string|null
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    public function match(string $otherText): bool
    {
        $otherText = $this->normalize($otherText);
        $text = $this->normalize($this->text);

        return $text === $otherText;
    }

    public function isEmpty(): bool
    {
        return is_null($this->text);
    }

}
