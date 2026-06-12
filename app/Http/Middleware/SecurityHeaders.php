<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);
        $isDevelopment = app()->environment('local') || config('app.debug') || file_exists(public_path('hot'));

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=()');

        if ($request->isSecure() && ! $isDevelopment) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        if (! $isDevelopment) {
            $scriptSrc = "'self' 'unsafe-inline'";
            $connectSrc = "'self'";

            if (config('services.analytics.enabled')) {
                $analyticsHost = $this->urlHost((string) config('services.analytics.url'));

                if ($analyticsHost !== null) {
                    $scriptSrc .= ' https://'.$analyticsHost;
                    $connectSrc .= ' https://'.$analyticsHost;
                }
            }

            if (config('services.newsletter.enabled')) {
                $newsletterHost = $this->urlHost((string) config('services.newsletter.url'));

                if ($newsletterHost !== null) {
                    $connectSrc .= ' https://'.$newsletterHost;
                }
            }

            $response->headers->set(
                'Content-Security-Policy',
                "default-src 'self'; ".
                "base-uri 'self'; ".
                "frame-ancestors 'none'; ".
                "object-src 'none'; ".
                "script-src {$scriptSrc}; ".
                "style-src 'self' 'unsafe-inline'; ".
                "font-src 'self' data:; ".
                "img-src 'self' data: blob: https:; ".
                "connect-src {$connectSrc}; ".
                "form-action 'self'; ".
                "upgrade-insecure-requests"
            );
        }

        return $response;
    }

    private function urlHost(string $url): ?string
    {
        if ($url === '') {
            return null;
        }

        $normalized = str_starts_with($url, '//') ? 'https:'.$url : $url;
        $host = parse_url($normalized, PHP_URL_HOST);

        return is_string($host) && $host !== '' ? $host : null;
    }
}
