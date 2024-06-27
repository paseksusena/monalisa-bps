<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    //function index yang mengarah ke view(blade) admin
    public function index()
    {
        return view('page.admin.admin');
    }
}
