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

    // sendmail メソッドを修正
    public function sendmail(Campaign $campaign)
    {
        Log::info('CampaignController@sendmail が実行されました。キャンペーンID: ' . $campaign->id);

        $testEmail = 'hmasakix@gmail.com'; // ★ 自分のアドレスなど
        $visitorName = 'テスト受信者';

        // メール本文（HTML形式）
        $htmlContent = view('emails.contact-form', [
        'data' => [ // 注意: この $data 配列を emails.contact-form.blade.php が期待しているか確認
            'email' => $testEmail,
            'name' => $visitorName,
            'subject' => 'キャンペーンのお知らせ', // これはビュー内で使う用の変数
            'title' => $campaign->title,
            'message' => $campaign->content,
        ]
        ])->render();

        try {
            // Mail::send を使う
            \Mail::send([], [], function ($message) use ($testEmail, $visitorName, $htmlContent, $campaign) { // $campaign も use で渡す
                $message->to($testEmail, $visitorName)
                        ->from('masaki@aroundie.sakura.ne.jp', 'Aroundie')
                        ->subject('キャンペーンのお知らせ: ' . $campaign->title) // 件名にタイトルを付与
                        // ★★★★★ ここを変更 ★★★★★
                        ->html($htmlContent); // setBody() の代わりに html() メソッドを使用
                        // ★★★★★ ここまで ★★★★★
            });
            Log::info('テストメール送信試行完了: ' . $testEmail);
        } catch (\Exception $e) {
            Log::error('メール送信中にエラー発生: ' . $e->getMessage());
            // エラーの詳細もログに出力するとデバッグに役立ちます
            Log::error($e);
            // エラー発生時にもリダイレクトし、ユーザーにフィードバック
            return redirect()->route('campaigns.index')->with('error', 'メール送信中にエラーが発生しました。ログを確認してください。');
        }

        return redirect()->route('campaigns.index')->with('success', 'テスト送信処理を実行しました！');
    }
}
