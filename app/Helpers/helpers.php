<?php

if (!function_exists('full_asset_url')) {
    /**
     * Generate full URL for uploaded assets
     * Handles both local and production environments
     * 
     * @param string|null $path
     * @return string
     */
    function full_asset_url($path)
    {
        if (empty($path)) {
            return '';
        }
        
        // If already a full URL, return as-is
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }
        
        // Remove leading slash if present
        $path = ltrim($path, '/');
        
        // Get the app URL from config
        $appUrl = rtrim(config('app.url'), '/');
        
        // Return full URL
        return $appUrl . '/' . $path;
    }
}

if (!function_exists('format_image_url')) {
    /**
     * Format image path to full URL for API responses
     * 
     * @param string|null $imagePath
     * @return string|null
     */
    function format_image_url($imagePath)
    {
        if (empty($imagePath)) {
            return null;
        }
        
        return full_asset_url($imagePath);
    }
}
