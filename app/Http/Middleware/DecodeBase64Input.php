<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DecodeBase64Input
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Only decode non-file inputs
        $input = $request->except(array_keys($request->allFiles()));

        $decoded = $this->decodeRecursive($input);

        $request->merge($decoded);

        return $next($request);
    }

    /**
     * Recursively decode base64 encoded values
     *
     * @param mixed $data
     * @return mixed
     */
    private function decodeRecursive($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->decodeRecursive($value);
            }
            return $data;
        }

        if (is_string($data) && strpos($data, 'b64:') === 0) {
            $decoded = base64_decode(substr($data, 4), true);
            if ($decoded !== false) {
                return trim($decoded);
            }
        }

        return $data;
    }
}
