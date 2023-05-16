<?php namespace App\Traits;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


trait RestControllerTrait
{

    protected $successStatus = 200;

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    protected  function index()
    {
        $m = self::MODEL;
        return $this->listResponse($m::all());
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    protected function show($id)
    {
        $m = self::MODEL;
        if($data = $m::find($id))
        {
            return $this->showResponse($data);
        }
        return $this->notFoundResponse();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    protected function store(Request $request)
    {
        $m = self::MODEL;
        try
        {
            $v = Validator::make($request->all(), $this->validationRules);
            if($v->fails())
            {
                throw new \Exception($v->errors()->first());
            }
            $data = $m::create(\Request::all());
            return $this->createdResponse($data);
        }catch(\Exception $ex)
        {/*
            $data = ['form_validations' => $v->errors(), 'exception' => $ex->getMessage()];*/
            return $this->clientErrorResponse($ex);
        }

    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    protected function update($id)
    {
        $m = self::MODEL;
        if(!$data = $m::find($id))
        {
            return $this->notFoundResponse();
        }
        try
        {
            $v = Validator::make(\Request::all(), $this->validationRules);
            if($v->fails())
            {
                throw new \Exception("ValidationException");
            }
            $data->fill(\Request::all());
            $data->save();
            return $this->showResponse($data);
        }catch(\Exception $ex)
        {
            $data = ['form_validations' => $v->errors(), 'exception' => $ex->getMessage()];
            return $this->clientErrorResponse($data);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    protected function destroy($id)
    {
        $m = self::MODEL;
        if(!$data = $m::find($id))
        {
            return $this->notFoundResponse();
        }
        $data->delete();
        return $this->deletedResponse();
    }


}