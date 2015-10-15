<?php

namespace Ilfate\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'Ilfate\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router)
    {
        /*
        |--------------------------------------------------------------------------
        | Authentication Filters
        |--------------------------------------------------------------------------
        |
        | The following filters are used to verify that the user of the current
        | session is logged into this application. The "basic" filter easily
        | integrates HTTP Basic authentication for quick, simple checking.
        |
        */

        $router->filter('auth', function()
        {
            if (Auth::guest())
            {
                if (Request::ajax())
                {
                    return Response::make('Unauthorized', 401);
                }
                else
                {
                    return \Redirect::guest('login');
                }
            }
        });


        $router->filter('auth.basic', function()
        {
            return Auth::basic();
        });

        /*
        |--------------------------------------------------------------------------
        | Guest Filter
        |--------------------------------------------------------------------------
        |
        | The "guest" filter is the counterpart of the authentication filters as
        | it simply checks that the current user is not logged in. A redirect
        | response will be issued if they are, which you may freely change.
        |
        */

        $router->filter('guest', function()
        {
            if (Auth::check()) return \Redirect::to('/');
        });

        /*
        |--------------------------------------------------------------------------
        | CSRF Protection Filter
        |--------------------------------------------------------------------------
        |
        | The CSRF filter is responsible for protecting your application against
        | cross-site request forgery attacks. If this special token in a user
        | session does not match the one given in this request, we'll bail.
        |
        */

        parent::boot($router);
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map(Router $router)
    {
        $router->group(['namespace' => $this->namespace], function ($router) {
            require app_path('Http/routes.php');
        });
    }
}
