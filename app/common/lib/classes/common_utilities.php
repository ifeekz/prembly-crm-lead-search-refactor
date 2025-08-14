<?php

declare(strict_types=1);

namespace App\Utils;

/**
 * Common_Utilities class provides utility functions for sanitizing input,
 * formatting phone numbers, and other common tasks.
 */
class Common_Utilities
{
    /**
     * Sanitize HTML input by removing tags and encoding special chars.
     * This is good for text fields that might contain unsafe characters.
     */
    public function sanitize_html(string $input): string
    {
        // Remove any tags
        $stripped = strip_tags($input);
        // Convert special characters to HTML entities
        return htmlspecialchars($stripped, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /**
     * Format phone number into a consistent numeric string.
     * Removes non-digits, keeps leading + if present.
     */
    public function formatPhoneNumber(string $phone): string
    {
        // Trim spaces
        $phone = trim($phone);

        // Allow + at the beginning for international numbers
        if (str_starts_with($phone, '+')) {
            $digits = '+' . preg_replace('/\D+/', '', substr($phone, 1));
        } else {
            $digits = preg_replace('/\D+/', '', $phone);
        }

        return $digits ?? '';
    }

    /**
     * Safe URL encoding.
     */
    public function safe_urlencode(string $input): string
    {
        return rawurlencode($input);
    }

    /**
     * Safe URL decoding.
     */
    public function safe_urldecode(string $input): string
    {
        return rawurldecode($input);
    }

    /**
     * Check if a string looks like an email.
     */
    public function is_valid_email(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Normalize a name (capitalize first letter of each word)
     */
    public function normalize_name(string $name): string
    {
        return ucwords(strtolower(trim($name)));
    }

    /**
     * Construct pagination data for a given page number, total items, and items per page.
     *
     * @param int $page The current page number.
     * @param int $total The total number of items.
     * @param int $perPage The number of items per page.
     * @return array An associative array containing pagination details:
     *               - 'page': The current page number (adjusted within valid range).
     *               - 'pages': Total number of pages.
     *               - 'perPage': Number of items per page.
     *               - 'hasPrev': Boolean indicating if a previous page exists.
     *               - 'hasNext': Boolean indicating if a next page exists.
     *               - 'prev': The previous page number (if exists, otherwise 1).
     *               - 'next': The next page number (if exists, otherwise the last page).
     */

    public function construct_pagination(int $page, int $total, int $perPage): array
    {
        $pages = (int)ceil(max(0, $total) / $perPage);
        $page = min(max(1, $page), max(1, $pages));
        
        return [
            'page' => $page,
            'pages' => $pages,
            'perPage' => $perPage,
            'hasPrev' => $page > 1,
            'hasNext' => $page < $pages,
            'prev' => max(1, $page - 1),
            'next' => min($pages, $page + 1),
        ];
    }
}
