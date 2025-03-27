<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Campaign; 
use App\Models\Visitor;
use App\Mail\TestMail;

class CampaignController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::latest()->get();
        return view('campaigns.index', compact('campaigns'));
    }
    
    public function create()
    {
        return view('campaigns.create');
    }

    public function show(Campaign $campaign)
    {
        return view('campaigns.show', compact('campaign'));
    }
  
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);
        
        Campaign::create($request->all());
        return redirect("/campaigns")->with('success', 'キャンペーンを作成しました！');
    }

    public function edit($id)
    {
        $campaign = Campaign::findOrFail($id); 
        return view('campaigns.edit', compact('campaign'));
    }
    public function update(Request $request, Campaign $campaign)
    {
      $request->validate([
        'title' => 'required|max:255',
      ]);
  
      $campaign->update($request->only('campaign'));
  
      return redirect()->route('campaigns.show', $campaign);
    }

    public function destroy(Campaign $campaign)
    {
        $campaign->delete();
        return redirect()->route('campaigns.index');
    }
    
    public function sendmail(Campaign $campaign)
    {
        $visitors = Visitor::all();
        
        foreach ($visitors as $visitor) {
            $data = [
                'name' => $visitor->name,
                'email' => $visitor->email,
                'subject' => 'キャンペーンのお知らせ',
                'title' => $campaign->title,
                'message' => $campaign->content,
                // 'campaign' => $campaign->title,
                'campaign_id' => $campaign->id,
                'campaign_content' => $campaign->content,
                'from' => 'test@example.com',
            ];
            Mail::to($visitor->email)->send(new TestMail($data));
            }
        return redirect()->route('campaigns.index')->with('success', '送信されました！');
     
    }
}

