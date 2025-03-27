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
        return view('dashboards.index', compact('visitors')); 
    }
    
    public function create()
    {
        return view('campaigns.create');
    }
    
    protected function redirectTo()
    {
        return '/dashboards'; // ← 複数形に変更
    }
}
