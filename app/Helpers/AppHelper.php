<?php

/**
 * Helper functions for PNCP system
 */

if (!function_exists('format_money')) {
    /**
     * Format value as currency
     */
    function format_money($value, $currency = 'R$')
    {
        return $currency . ' ' . number_format($value, 2, ',', '.');
    }
}

if (!function_exists('format_date')) {
    /**
     * Format date in pt-BR
     */
    function format_date($date, $format = 'd/m/Y')
    {
        if (!$date) return '-';
        return date($format, strtotime($date));
    }
}

if (!function_exists('truncate_text')) {
    /**
     * Truncate text with ellipsis
     */
    function truncate_text($text, $length = 100, $suffix = '...')
    {
        if (strlen($text) > $length) {
            return substr($text, 0, $length) . $suffix;
        }
        return $text;
    }
}

if (!function_exists('is_authenticated')) {
    /**
     * Check if user is authenticated
     */
    function is_authenticated()
    {
        return session()->has('user');
    }
}

if (!function_exists('get_user')) {
    /**
     * Get authenticated user
     */
    function get_user()
    {
        return session()->get('user');
    }
}

if (!function_exists('get_user_id')) {
    /**
     * Get authenticated user ID
     */
    function get_user_id()
    {
        $user = get_user();
        return $user ? $user['id'] : null;
    }
}

if (!function_exists('formatUsernameWithHonorific')) {
    /**
     * Formata o nome do usuário com um honorífico
     */
    function formatUsernameWithHonorific($userId = null)
    {
        // Se usar CodeIgniter Shield
        if (function_exists('auth') && auth()->loggedIn()) {
            $user = auth()->user();
            return 'Dr(a). ' . ($user->username ?? 'Usuário(a)');
        }

        return 'Dr(a). Usuário(a)';
    }
}