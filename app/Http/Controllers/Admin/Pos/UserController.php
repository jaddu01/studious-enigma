<?php

namespace App\Http\Controllers\Admin\Pos;

use App\CountryPhoneCode;
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
        return view('admin.pages.pos.users.index');
    }

    public function create()
    {
        $countryPhoneCode = CountryPhoneCode::pluck('phonecode','phonecode');
        return view('admin.pages.pos.users.create', compact('countryPhoneCode'));
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
            return redirect()->route('pos.users')->with('success', 'User created successfully');
        }catch(\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function edit($id)
    {
        $user = PosUser::find($id);
        if(!$user){
            return redirect()->back()->withErrors('User not found');
        }
        $countryPhoneCode = CountryPhoneCode::pluck('phonecode','phonecode');
        return view('admin.pages.pos.users.edit', compact('user', 'countryPhoneCode'));
    }

    public function update(Request $request, $id){
        try{
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'phone_code' => 'required',
                'phone_number' => 'required',
                'email' => 'required|email',
                'address' => 'nullable',
            ]);
            if ($validator->fails()){
               return redirect()->back()->withErrors($validator->errors()->first());
            }
            $input = $request->only(['name','phone_code','phone_number','email','address']);
            $user = PosUser::find($id);
            if(!$user){
                return redirect()->back()->withErrors('User not found');
            }
            if(!empty($input['password'])){
                $input['password'] = bcrypt($input['password']);
            }
            
            $user->update($input);
            return redirect()->route('pos.users')->with('success', 'User updated successfully');
        }catch(\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function show($id)
    {
        return view('admin.pos.user.show', compact('id'));
    }

    public function destroy($id)
    {
        try{
            $user = PosUser::find($id);
            if(!$user){
                return redirect()->back()->withErrors('User not found');
            }
            $user->delete();
            return redirect()->route('pos.users')->with('success', 'User deleted successfully');
        }catch(\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function anyData(){
        $users = PosUser::latest();
        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('created_at',function ($user){
                //return date('d/m/Y', strtotime($user->created_at));
                return Carbon::parse($user->created_at)->format('d M, Y H:i:s');
            })
            ->addColumn('action', function($user){
                $edit = route('pos.users.edit', $user->id);
                $delete = route('pos.users.delete', $user->id);
                return "<a href=".$edit." class='btn btn-xs btn-primary'><i class='glyphicon glyphicon-edit'></i> Edit</a>
                        <a href=".$delete." onclick='return confirm(\'Are you sure, want to delete this record?\');' class='btn btn-xs btn-danger'><i class='glyphicon glyphicon-trash'></i> Delete</a>";
            })
            ->rawColumns(['action'])
            ->make(true);
    }


}
