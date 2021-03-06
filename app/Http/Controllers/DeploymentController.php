<?php

namespace Ilfate\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class DeploymentController extends BaseController
{


    /**
     * Main page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('pages.index');
    }

    /**
     * Cv page
     *
     * @return string
     */
    public function resetopcache(Request $request)
    {
        $allowedIps = \Config::get('auth.adminIps');
        if (in_array($request->ip(), $allowedIps)) {

            Log::info('Resetting opcache for ' . php_sapi_name());
            opcache_reset();
        } else {
            Log::info('Request was rejected for IP: ' . $request->ip());
        }
    }
}
