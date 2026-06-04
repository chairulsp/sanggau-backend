<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            // Check if request is for admin panel
            if ($request->is('admin/*') || $request->is('admin')) {
                return route('admin.login');
            }
            
            // For other requests, redirect to home or return null
            return route('home');
        }
    }
}
