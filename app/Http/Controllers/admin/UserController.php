<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //
    public function index(){
        $users=User::orderBy('created_at','desc')->where('role','user')->paginate(5);
        return view('admin.users.list',compact('users'));
    }
    public function create(){
return view('admin.users.create');
    }
    public function store(Request $request){
        $validator=Validator::make($request->all(),[
            'name'=>'required|min:3',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:3',
            
           ]);
           if( $validator->passes()){
          $user=new User();
          $user->name=$request->name;
          $user->email=$request->email;
          $user->password=Hash::make($request->password);
          $user->designation=$request->designation;
          $user->mobile=$request->mobile;
          $user->save();
            
          session()->flash('success','User information Created Successfully!');
          return response()->json([
           'status'=>true,
           'errors'=>[]
           ]);
          
           }
           else{
               return response()->json([
               'status'=>false,
               'errors'=>$validator->errors()
               ]);
           }
    }
    public function edit($id){
        $user=User::findOrFail($id);
        return view('admin.users.edit',compact('user'));
    }

    public function update($id,Request $request){
        $validator=Validator::make($request->all(),[
            'name'=>'required|min:3',
            'email'=>'required|email|unique:users,email,'.$id.',id',
            'password'=>'required|min:3',
            
           ]);

           if( $validator->passes()){

          $user=User::findOrFail($id);

          if(Hash::check($request->password,$user->password)==true){
            
            return response()->json([
                'status' => false,
                'errors' => ['password' => 'This password is already in use. Please choose a different password.']
            ]);
        }
          $user->name=$request->name;
          $user->email=$request->email;
          $user->designation=$request->designation;
        
          $user->password=Hash::make($request->password);
          $user->mobile=$request->mobile;
          $user->save();
            
          session()->flash('success','User information Updated Successfully!');
          return response()->json([
           'status'=>true,
           'errors'=>[]
           ]);
          
           }
           else{
               return response()->json([
               'status'=>false,
               'errors'=>$validator->errors()
               ]);
           }
    }
    public function destroy(Request $request){
        $id=$request->id;
        User::findOrFail($id)->delete();
        session()->flash('success','User deleted successfully');
        return response()->json([
            'status'=>true,
            'message'=>'User deleted successfully'
          ]);
    }
}
