<?php
if(!function_exists('sort_by_strnatcmp')) {
    function sort_by_strnatcmp($key, $order) {
        return function ($a, $b) use ($key, $order) {
            if(is_numeric($a[$key]) && is_numeric($b[$key])) { # Both Numeric Values
                return (($order === 'ASC') ? ($a[$key] >= $b[$key]) : ($a[$key] < $b[$key]));
            }
            # One of them at least is string
            return (
                ($order == 'ASC') ? 
                strnatcmp($a[$key], $b[$key]) : strnatcmp($b[$key], $a[$key])
            );
        };
    }
}

if(!function_exists('clean_url')) {
    function clean_url($url) {
        $path = parse_url($url, PHP_URL_PATH);
        $encoded_path = array_map('urlencode', explode('/', $path));
        $url = str_replace($path, implode('/', $encoded_path), $url);
    
        return filter_var($url, FILTER_VALIDATE_URL)? $url : 'Invalid URL!';
    }
}

if(!function_exists('sanitize_ascii')) {
    function sanitize_ascii($string) {
        $string = preg_replace('/[[:^print:]]/', '', $string);
        return $string;
    }
}

if(!function_exists('sanitize_extended_ascii')) {
    function sanitize_extended_ascii($string) {
        $string = preg_replace('/[^\x01-\xFF]/','', $string);
        return html_entity_decode($string);
    }
}

if(!function_exists('valid_int')) {
    function valid_int($integer) {
        $integer = (int) $integer;
        return is_int($integer)? $integer : 'Invalid integer value!';
    }
}

if(!function_exists('valid_float')) {
    function valid_float($float) {
        $float = (float) $float;
        return is_float($float)? $float : 'Invalid float value!';
    }
}

# Remove redundent whitespaces inside string
if(!function_exists('clean_string')) {
    function clean_string($string) {
        return preg_replace('/\s+/', ' ', $string);
    }
}

if(!function_exists('hotel_stars')) {
    function hotel_stars($starsCount) {
        $starsCount = (int) $starsCount;
        return ($starsCount > 0 && $starsCount <= 5)? $starsCount : 'Invalid!';
    }
}