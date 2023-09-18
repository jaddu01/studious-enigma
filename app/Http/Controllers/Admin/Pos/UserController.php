<?php

namespace App\Http\Controllers\Admin\Pos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PosUser;
use Carbon\Carbon;
use DataTables;
use Log;
use Validator;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.pos.user.index');
    }

    public function create()
    {
        return view('admin.pos.user.create');
    }

    public function store(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'phone_code' => 'required',
                'phone_number' => 'required',
                'email' => 'required|email',
                'password' => 'required',
                'address' => 'nullable',
            ]);
            if ($validator->fails()){
               return redirect()->back()->withErrors($validator->errors()->first());
            }
            $input = $request->only(['name','phone_code','phone_number','email','password','address']);
            $input['password'] = bcrypt($input['password']);
            $input['status'] = 1;
            $input['language'] = 'en';
            $input['zone_id'] = 61;
            PosUser::create($input);
        }catch(\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    // public function edit($id)
    // {
    //     return view('admin.pos.user.edit', compact('id'));
    // }

    // public function show($id)
    // {
    //     return view('admin.pos.user.show', compact('id'));
    // }

    // public function delete($id)
    // {
    //     return view('admin.pos.user.delete', compact('id'));
    // }

    public function anyData(){
        $users = PosUser::latest();

        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('created_at',function ($user){
                //return date('d/m/Y', strtotime($user->created_at));
                return Carbon::parse($user->created_at)->format('d M, Y H:i:s');
            })
            ->make(true);
    }


}
