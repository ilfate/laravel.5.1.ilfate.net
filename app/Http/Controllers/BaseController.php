<?php

namespace Ilfate\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BaseController extends Controller
{
    use DispatchesJobs, ValidatesRequests, AuthorizesRequests;

    /**
     * Get current class name
     * @return string
     */
	protected function getCurrentClass()
    {
        return substr(get_class($this), strrpos(get_class($this), '\\') + 1);
    }

}
