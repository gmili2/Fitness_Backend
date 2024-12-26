<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    //
    public function index()
    {
        dd("ok");
        return view('admin.dashboard'); // Vue à afficher pour le tableau de bord
    }
}
