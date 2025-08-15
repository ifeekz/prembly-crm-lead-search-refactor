<?php

declare(strict_types=1);

namespace Prembly\Crm\Utils;

/**
 * Common_Utilities class provides utility functions for sanitizing input,
 * formatting phone numbers, and other common tasks.
 */
class Common_Utilities
{
    /**
     * Sanitizes the input string by removing any HTML tags and converting special
     * characters to HTML entities.
     *
     * @param string $input The input string to sanitize.
     * @return string The sanitized string.
     */
    public function sanitize_html(string $input): string
    {
        // Remove any tags
        $stripped = strip_tags($input);
        // Convert special characters to HTML entities
        return htmlspecialchars($stripped, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /**
     * Formats a phone number by removing any non-digit characters and
     * optionally adding a leading '+' for international numbers.
     *
     * @param string $phone The phone number to format.
     * @return string The formatted phone number.
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
     * URL-encodes the input string.
     *
     * This function is a safe alternative to `rawurlencode` because it only
     * encodes the characters that need to be encoded, not the whole string.
     *
     * @param string $input The input string to encode.
     * @return string The URL-encoded string.
     */
    public function safe_urlencode(string $input): string
    {
        return rawurlencode($input);
    }

    /**
     * URL-decodes the input string.
     *
     * This function decodes the string using `rawurldecode` and is a safe
     * alternative to `urldecode` because it only decodes the characters that
     * need to be decoded, not the whole string.
     *
     * @param string $input The input string to decode.
     * @return string The URL-decoded string.
     */
    public function safe_urldecode(string $input): string
    {
        return rawurldecode($input);
    }

    /**
     * Checks if the given string is a valid email address.
     *
     * @param string $email The email address to check.
     * @return bool True if the email is valid, false otherwise.
     */
    public function is_valid_email(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Normalizes a name by capitalizing each word and removing leading/trailing whitespace.
     *
     * @param string $name The name to normalize.
     * @return string The normalized name.
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


    /**
     * Build a query string from an array of parameters and overrides.
     *
     * @param array $params The base parameters.
     * @param array $overrides The parameters to override in the base.
     * @return string The merged and encoded query string.
     */
    public function build_query(array $params, array $overrides): string
    {
        return http_build_query(array_merge($params, $overrides));
    }
}
