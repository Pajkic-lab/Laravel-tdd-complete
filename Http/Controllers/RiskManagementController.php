<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RiskManagementController extends Controller
{
    public function index() {
        return view('pages.riskMenagement');
    }
}
