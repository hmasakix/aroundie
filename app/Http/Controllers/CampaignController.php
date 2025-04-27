<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail; // Mail ファサードを使用
use App\Models\Campaign;
use App\Models\Visitor;
use Illuminate\Support\Facades\Log;
use Illuminate\Mail\Message; // ★★★ Message クラスを use ★★★

    class CampaignController extends Controller
    {
    /**
     * Campaign 一覧表示 (index)
     */
    public function index()
{
        // ★★★ この中身を正しく記述 ★★★
        try {
            $campaigns = Campaign::latest()->get();
            return view('campaigns.index', compact('campaigns'));
        } catch (\Exception $e) {
            Log::error('CampaignController@index でエラー: ' . $e->getMessage());
            // エラーページを表示するか、エラーメッセージと共にリダイレクトするなど
            // ここでは一旦シンプルなエラーメッセージを返す
            return "キャンペーン一覧の表示中にエラーが発生しました。ログを確認してください。";
        }
    }

    /**
     * Campaign 登録フォーム表示 (create)
     */
    public function create()
    {
        // ★★★ この中身を正しく記述 ★★★
        try {
            return view('campaigns.create');
        } catch (\Exception $e) {
            Log::error('CampaignController@create でエラー: ' . $e->getMessage());
            return "キャンペーン登録ページの表示中にエラーが発生しました。ログを確認してください。";
        }
    }

    /**
     * Campaign 詳細表示 (show)
     */
    public function show(Campaign $campaign)
    {
        // ★★★ この中身を正しく記述 ★★★
        try {
             return view('campaigns.show', compact('campaign'));
        } catch (\Exception $e) {
            Log::error('CampaignController@show でエラー: ' . $e->getMessage());
            return "キャンペーン詳細ページの表示中にエラーが発生しました。ログを確認してください。";
        }
    }

    /**
     * Campaign 保存処理 (store)
     */
    public function store(Request $request)
    {
        // ★★★ この中身を正しく記述 ★★★
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                // start_date, end_date のバリデーションも必要なら追加
            ]);

            Campaign::create($request->all()); // fillable の設定が必要
            return redirect()->route('campaigns.index')->with('success', 'キャンペーンを作成しました！'); // redirect() の引数はルート名が推奨
        } catch (\Illuminate\Validation\ValidationException $e) {
             // バリデーションエラーは通常Laravelが自動でリダイレクトしてくれる
             // ここでログを取るなどの処理は可能
             Log::warning('CampaignController@store バリデーションエラー: ', $e->errors());
             throw $e; // エラーを再スローしてLaravelのハンドリングに任せる
        } catch (\Exception $e) {
            Log::error('CampaignController@store でエラー: ' . $e->getMessage());
            return redirect()->route('campaigns.create')->with('error', 'キャンペーンの作成中にエラーが発生しました。');
        }
    }

    /**
     * Campaign 編集フォーム表示 (edit)
     */
    public function edit($id) // ルートモデルバインディングを使わない場合
    {
        // ★★★ この中身を正しく記述 ★★★
        try {
            $campaign = Campaign::findOrFail($id);
            return view('campaigns.edit', compact('campaign'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('CampaignController@edit 指定されたキャンペーンが見つかりません: ID ' . $id);
            return redirect()->route('campaigns.index')->with('error', '指定されたキャンペーンが見つかりません。');
        } catch (\Exception $e) {
            Log::error('CampaignController@edit でエラー: ' . $e->getMessage());
            return "キャンペーン編集ページの表示中にエラーが発生しました。ログを確認してください。";
        }
    }

    /**
     * Campaign 更新処理 (update)
     */
    public function update(Request $request, Campaign $campaign) // ルートモデルバインディングを使用
    {
      // ★★★ この中身を正しく記述 ★★★
       try {
            $request->validate([
                'title' => 'required|max:255',
                'content' => 'required|string',
            ]);

            $campaign->update($request->only(['title', 'content'])); // fillable の設定が必要

            return redirect()->route('campaigns.show', $campaign)->with('success', '更新されました！');
        } catch (\Illuminate\Validation\ValidationException $e) {
             Log::warning('CampaignController@update バリデーションエラー: ', $e->errors());
             throw $e;
        } catch (\Exception $e) {
            Log::error('CampaignController@update でエラー: ' . $e->getMessage());
            // 更新失敗時は編集画面に戻すのが親切かも
            return redirect()->route('campaigns.edit', $campaign)->with('error', 'キャンペーンの更新中にエラーが発生しました。');
        }
    }

    /**
     * Campaign 削除処理 (destroy)
     */
    public function destroy(Campaign $campaign) // ルートモデルバインディングを使用
    {
        // ★★★ この中身を正しく記述 ★★★
        try {
            $campaign->delete();
            return redirect()->route('campaigns.index')->with('success', '削除されました！');
        } catch (\Exception $e) {
            Log::error('CampaignController@destroy でエラー: ' . $e->getMessage());
            return redirect()->route('campaigns.index')->with('error', 'キャンペーンの削除中にエラーが発生しました。');
        }
    }

    // --- sendmail メソッドを Mail::send() を使う形に戻す ---
    public function sendmail(Campaign $campaign)
    {
        Log::info('CampaignController@sendmail が実行されました。キャンペーンID: ' . $campaign->id);

        $visitors = Visitor::all();
        $sendCount = 0;
        $errorCount = 0;

        // // テスト用 Visitor 追加 
        // $testVisitor = new Visitor(['name' => 'テスト受信者', 'email' => 'root@taroosg.dev']);
        // $visitors->push($testVisitor);

        // // テスト用 Visitor2 追加 
        // $testVisitor2 = new Visitor(['name' => 'テスト受信者', 'email' => 'hmasakix@gmail.com']);
        // $visitors->push($testVisitor2);

        Log::info('送信対象件数: ' . $visitors->count() . '件');

        foreach ($visitors as $visitor) {
            // ★★★ ループの外ではなく、中でデータを準備 ★★★
            $viewData = [
                'data' => [
                    'email' => $visitor->email,
                    'name' => $visitor->name,
                    'subject' => 'キャンペーンのお知らせ', // ビュー用
                    'title' => $campaign->title,
                    'message' => $campaign->content,
                ]
            ];
            $viewName = 'emails.contact-form'; // 使用するビュー名

            try {
                // ★★★ Mail::send を使用 ★★★
                Mail::send($viewName, $viewData, function (Message $message) use ($visitor, $campaign) {
                    $message->to($visitor->email, $visitor->name)
                            ->from('masaki@aroundie.sakura.ne.jp', 'Aroundie') // ★ 送信元をここで指定
                            ->subject('キャンペーンのお知らせ: ' . $campaign->title);
                });
                // ★★★ ここまで ★★★

                Log::info('メール送信成功: ' . $visitor->email);
                $sendCount++;

            } catch (\Exception $e) {
                Log::error('メール送信中にエラー発生: ' . $visitor->email . ' - ' . $e->getMessage());
                Log::error($e);
                $errorCount++;
                continue;
            }

            sleep(1); // 負荷軽減
        }

        Log::info('メール一斉送信処理完了。成功: ' . $sendCount . '件, 失敗: ' . $errorCount . '件');

        // リダイレクト処理
        if ($errorCount > 0) {
            return redirect()->route('campaigns.index')->with('warning', $sendCount . '件のメール送信に成功しましたが、' . $errorCount . '件でエラーが発生しました。詳細はログを確認してください。');
        } else {
            return redirect()->route('campaigns.index')->with('success', $sendCount . '件のメールを一斉送信しました！');
        }
    }
}
