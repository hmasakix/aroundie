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

        // Visitor テーブルから全ての宛先を取得
        $visitors = Visitor::all();
        $sendCount = 0; // 送信成功カウント
        $errorCount = 0; // 送信失敗カウント

        // デバッグ用にテストメールアドレスにも送る場合（不要ならこの行は削除）
        $testVisitor = new Visitor(['name' => 'テスト受信者', 'email' => 'hmasakix@gmail.com']);
        $visitors->push($testVisitor);

        Log::info('送信対象件数: ' . $visitors->count() . '件');

        // 各 Visitor に対してループ処理
        foreach ($visitors as $visitor) {
            try {
                // メール本文（HTML形式）- ループ毎に Visitor 情報を反映させる場合
                // （現在のビューが visitor 情報を必要としないなら、ループの外で1回生成でもOK）
                $htmlContent = view('emails.contact-form', [
                'data' => [
                    'email' => $visitor->email, // Visitor のメールアドレスを使用
                    'name' => $visitor->name,   // Visitor の名前を使用
                    'subject' => 'キャンペーンのお知らせ', // ビュー用の変数
                    'title' => $campaign->title,
                    'message' => $campaign->content,
                ]
                ])->render();

                // Mail::send を使う
                \Mail::send([], [], function ($message) use ($visitor, $htmlContent, $campaign) {
                    $message->to($visitor->email, $visitor->name) // Visitor の情報を使う
                            ->from('masaki@aroundie.sakura.ne.jp', 'Aroundie')
                            ->subject('キャンペーンのお知らせ: ' . $campaign->title)
                            ->html($htmlContent);
                });

                Log::info('メール送信成功: ' . $visitor->email);
                $sendCount++;

            } catch (\Exception $e) {
                Log::error('メール送信中にエラー発生: ' . $visitor->email . ' - ' . $e->getMessage());
                // エラーの詳細もログに出力
                Log::error($e);
                $errorCount++;
                // 1件エラーが出ても処理を続行する (continue)
                continue;
            }

            // 送信間隔を少し空ける（サーバー負荷軽減、任意）
             sleep(1); // 1秒待機 (必要に応じて調整・削除)
        }

        Log::info('メール一斉送信処理完了。成功: ' . $sendCount . '件, 失敗: ' . $errorCount . '件');

        // ループ完了後にリダイレクト
        if ($errorCount > 0) {
            return redirect()->route('campaigns.index')->with('warning', $sendCount . '件のメール送信に成功しましたが、' . $errorCount . '件でエラーが発生しました。詳細はログを確認してください。');
        } else {
            return redirect()->route('campaigns.index')->with('success', $sendCount . '件のメールを一斉送信しました！');
        }
    }
}
