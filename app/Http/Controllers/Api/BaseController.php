<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;

class BaseController extends Controller
{
    use ApiResponseTrait;
    
    /**
     * @SWG\Swagger(
     *   host="",
     *   basePath="/api/v1",
     *   @SWG\Info(
     *     title="app",
     *     version="1.0.0"
     *   ),
     * @SWG\SecurityScheme(
     *   securityDefinition="api_key",
     *   type="apiKey",
     *   in="header",
     *   description = "认证token",
     *   name="token"
     * ),
     * )
     */
    public function __construct()
    {
//        $this->middleware("auth:api")->only(['logout']);
    }
}
