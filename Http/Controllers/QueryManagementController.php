<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QueryManagementController extends Controller
{
    public function index() {
        return view('pages.queryMenagement');
    }
}
