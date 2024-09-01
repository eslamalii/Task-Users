<!-- <?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        // Trusts the hosts
        \App\Http\Middleware\TrustHosts::class,
        
        // Trust proxies, like Cloudflare, load balancers, etc.
        \App\Http\Middleware\TrustProxies::class,


        // Validates the post size
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,

        // Trims strings from the request data
        \App\Http\Middleware\TrimStrings::class,

        // Converts empty strings to null in the request data
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            // Adds queued cookies to the response
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,

            // Starts the session for the user
            \Illuminate\Session\Middleware\StartSession::class,

            // Share errors from the session with views
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,

            // Substitutes route bindings
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            // Sanctum middleware for API stateful requests
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,

            // Throttles API requests to prevent abuse
            'throttle:api',

            // Substitutes route bindings
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        // Auth middleware to ensure the user is authenticated
        'auth' => \App\Http\Middleware\Authenticate::class,

        // Basic HTTP auth
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,

        // Cache headers
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,

        // Can middleware for authorization
        'can' => \Illuminate\Auth\Middleware\Authorize::class,

        // Requires password confirmation
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,

        // Validates signature on signed routes
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,

        // Throttles requests to prevent abuse
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,

        // Ensures email is verified
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
    ];
}
