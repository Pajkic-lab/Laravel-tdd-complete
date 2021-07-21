<?php

namespace App\Http\Controllers;

use App\Services\NestoService;
use Illuminate\Http\Request;

class ChartController extends Controller
{
    public function index() {
        $data = NestoService::getNesto();
        //$data = NestoService::getNestoSlozeno();
        //dd($data);

        return view('pages.chart', ["data" => $data]);
    }

    public function show(Request $request) {
        //dd($request->all());
        NestoService::getNestoNesto($request->all());
    }

    public function getUsers() {
        //dd('gadja');
        //return 'nesto!!!!';
        $data = NestoService::getUsers();
        return $data;
    }

    public function query(Request $request) {
        //dd($request->all());
        $data = NestoService::buildQuery($request->all());
        return $data;
    }
}
