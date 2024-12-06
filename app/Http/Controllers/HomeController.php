<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class HomeController extends Controller
{
    public function index(){
        $roles['roles']= Role::all();
        return view('welcome',$roles);
    }
}
