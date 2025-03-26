<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Campaign; 
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $visitors = [];
        return view('dashboard.index', compact('visitors')); 
    }
    
    public function create()
    {
        return view('campaigns.create');
    }
    
}
