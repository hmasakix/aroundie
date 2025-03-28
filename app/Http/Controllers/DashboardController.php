<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Campaign; 
use App\Models\Visitor;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $visitorCount = Visitor::count();   // ← 訪問者の人数カウント
        $campaignCount = Campaign::count(); 
        return view('dashboards.index', compact('visitorCount', 'campaignCount'));
    }

    
    public function create()
    {
        return view('campaigns.create');
    }
    
    protected function redirectTo()
    {
        return '/dashboards'; 
    }
}
