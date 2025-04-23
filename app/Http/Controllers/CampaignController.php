<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Campaign; 
use App\Models\Visitor;
// Logファサードを追加
use Illuminate\Support\Facades\Log;

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
        'content' => 'required|string',
      ]);
  
      $campaign->update($request->only(['title', 'content']));
  
      return redirect()->route('campaigns.show', $campaign)->with('success', '更新されました！');
    }

    public function destroy(Campaign $campaign)
    {
        $campaign->delete();
         return redirect()->route('campaigns.index')->with('success', '削除されました！');
    }
    
    public function sendmail(Campaign $campaign)
    {
        Log::info('CampaignController@sendmail が実行されました。キャンペーンID: ' . $campaign->id);
        
        $testEmail = 'hmasakix@gmail.com'; // ★ 自分のアドレスなど
        $visitorName = 'テスト受信者';
        
        // メール本文（HTML形式）
        $htmlContent = view('emails.contact-form', [
        'data' => [
            'email' => $testEmail,
            'name' => $visitorName,
            'subject' => 'キャンペーンのお知らせ',
            'title' => $campaign->title,
            'message' => $campaign->content,
        ]
        ])->render();
        // $visitors = Visitor::all();
        
        // foreach ($visitors as $visitor) {
            // $data = [
            //     'email' => $testEmail, // ← ここ！
            //     'name' => $visitorName, // ← これを追加！
                // 'name' => $visitor->name,
                // 'email' => $visitor->email,
                // 'subject' => 'キャンペーンのお知らせ',
                // 'title' => $campaign->title,
                // 'message' => $campaign->content,
                // 'campaign' => $campaign->title,
            //     'campaign_id' => $campaign->id,
            //     'campaign_content' => $campaign->content,
            // ];

            try {
                \Mail::send([], [], function ($message) use ($testEmail, $visitorName, $htmlContent) {
                    $message->to($testEmail, $visitorName)
                            ->from('masaki@aroundie.sakura.ne.jp', 'Aroundie')
                            ->subject('キャンペーンのお知らせ')
                            ->setBody($htmlContent, 'text/html');
                });        
                Log::info('テストメール送信試行完了: ' . $testEmail);
            } catch (\Exception $e) {
                Log::error('メール送信中にエラー発生: ' . $e->getMessage());
            }
        
            return redirect()->route('campaigns.index')->with('success', 'テスト送信処理を実行しました！');
        //     Mail::to($visitor->email)->send(new TestMail($data));
        //     }
        // return redirect()->route('campaigns.index')->with('success', '送信されました！');
     
    }
}

