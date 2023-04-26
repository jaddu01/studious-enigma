<?php

namespace App\Http\Controllers\Api;


use App\Cms;
use App\Traits\ResponceTrait;
use App\Traits\RestControllerTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CmsController extends Controller
{
    use RestControllerTrait,ResponceTrait;

    const MODEL = 'App\Cms';
    /**
     * @var cms
     */
    private $cms;
    /**
     * @var string
     */
    protected $method;

    /**
     * cmsController constructor.
     * @param Request $request
     * @param cms $cms
     */
    public function __construct(Request $request,Cms $cms)
    {
        parent::__construct();
        $this->cms = $cms;
        $this->method=$request->method();
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($page){
        $cms = $this->cms->where(['name'=>$page])->firstOrFail();

        return $this->listResponse($cms);
    }


}
