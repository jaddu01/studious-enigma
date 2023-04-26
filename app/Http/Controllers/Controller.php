<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use stdClass;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    protected $errorStatus   = 500;
    protected $successStatus = 200;
    protected $validationStatus = 400;
    protected $unauthStatus  = 401;
    protected $notFoundStatus  = 404;
    protected $invalidPermission = 403;

    protected $response;
    public function __construct(){

        $this->response  = new stdClass();
        $prefix = Request::route()->getPrefix();
        if($prefix=='api/v1'){

            if (Auth::guard('api')->check()  ) {
                App::setLocale(Auth::guard('api')->user()->language);
            }

        }

    }

}
