<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail; // Mail ファサードを使用
use App\Models\Campaign;
use App\Models\Visitor;
use App\Mail\CampaignMail; // ★★★ 作成した Mailable を use ★★★
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

    // --- sendmail メソッドを Mailable を使うように修正 ---
    public function sendmail(Campaign $campaign)
    {
        Log::info('CampaignController@sendmail が実行されました。キャンペーンID: ' . $campaign->id);

        $visitors = Visitor::all();
        $sendCount = 0;
        $errorCount = 0;

        // テスト用メールアドレスを追加 (常に自分に送りたい場合はこのまま、不要なら削除)
        $testVisitor = new Visitor(['name' => 'テスト受信者', 'email' => 'hmasakix@gmail.com']);
        $visitors->push($testVisitor);

        Log::info('送信対象件数: ' . $visitors->count() . '件');

        foreach ($visitors as $visitor) {
            try {
                // ★★★ Mailable クラスを使って送信 ★★★
                Mail::to($visitor->email)->send(new CampaignMail($campaign, $visitor));

                Log::info('メール送信成功: ' . $visitor->email);
                $sendCount++;

            } catch (\Exception $e) {
                Log::error('メール送信中にエラー発生: ' . $visitor->email . ' - ' . $e->getMessage());
                // エラー詳細もログへ
                Log::error($e);
                $errorCount++;
                // 次のループへ
                continue;
            }

            // 送信間隔（必要に応じて調整・削除）
            sleep(1);
        }

        Log::info('メール一斉送信処理完了。成功: ' . $sendCount . '件, 失敗: ' . $errorCount . '件');

        // 完了後のリダイレクトメッセージ
        if ($errorCount > 0) {
            return redirect()->route('campaigns.index')->with('warning', $sendCount . '件のメール送信に成功しましたが、' . $errorCount . '件でエラーが発生しました。詳細はログを確認してください。');
        } else {
            return redirect()->route('campaigns.index')->with('success', $sendCount . '件のメールを一斉送信しました！');
        }
    }
}
