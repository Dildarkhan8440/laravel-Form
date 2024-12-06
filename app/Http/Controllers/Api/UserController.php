<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Role;
use App\Models\User;

class UserController extends Controller
{
    public function get(Request $request){
        
        $userList=User::with('role')->get();
         return response()->json([
            'user' => $userList
        ], 200);
    }
    public function add(Request $request){
        $rules = [
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'phone' => 'required|digits:10',
            'role' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg',
            'description' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return response()->json([
                        'error' => $validator->errors()
                    ]);
        }
         
         if($request->hasfile('image'))
        {
            $file = $request->file('image');
            $extenstion = $file->getClientOriginalExtension();
            $filename = time().'.'.$extenstion;
            $file->move(public_path('images'), $filename);
            $image = $filename;
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role_id' =>  $request->role,
            'image' => $image,
            'password'=>'12345',
            'description' => $request->description,
        ]);

        return response()->json([
            'user' => $user,
            "st"=>"success"
        ], 200);
    }
}
