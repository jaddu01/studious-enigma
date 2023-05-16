<?php

namespace App\Http\Controllers\Admin;

use App\Cms;
use App\Scopes\StatusScope;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use DataTables;


class CmsController extends Controller
{
    protected $model;
    protected $user;
    protected $method;
    function __construct(Request $request,Cms $model,User $user)
    {
        parent::__construct();
        $this->model=$model;
        $this->user=$user;
        $this->method=$request->method();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ($this->user->can('view', Cms::class)) {
            return abort(403,'not able to access');
        }
        return view('admin/pages/cms/index');
    }




    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $validator = JsValidatorFacade::make($this->model->rules('PUT'));
        $cms=$this->model->withoutGlobalScope(StatusScope::class)->findOrFail($id);
        return view('admin/pages/cms/edit')->with('cms',$cms)->with('validator',$validator);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(),$this->model->rules($this->method),$this->model->messages($this->method));

        if ($validator->fails()) {
            dd($validator->errors());
            return back()
                ->withErrors($validator)
                ->withInput();
        }else{

            //dd($input);
            $this->model->withoutGlobalScope(StatusScope::class)->FindOrFail($id)->update($input);
            return redirect()->route('cms.index')->with('success','cms Upload successful');
        }
    }



    /**
     * @return mixed
     */
    public function anyData()
    {
        $cms = $this->model->get();
        //$cms =cms::query();
        $start = 1;
        return Datatables::of($cms)
            ->editColumn('id',function () use(& $start){
                return $start++;
            })
            ->addColumn('action',function ($cms){
                return '<a href="'.route("cms.edit",$cms->id).'" class="btn btn-success">Edit</a>';
            })
            ->rawColumns(['image','action'])
            ->make(true);

    }

}
