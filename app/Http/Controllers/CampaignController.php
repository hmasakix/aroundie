<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail; // Mail ファサードを使用
use App\Models\Campaign;
use App\Models\Visitor;
// use App\Mail\CampaignMail; // ★★★ Mailable の use を削除 ★★★
use Illuminate\Support\Facades\Log;
use Illuminate\Mail\Message; // ★★★ Message クラスを use ★★★

class CampaignController extends Controller
{
    // --- index から destroy までは変更なし ---
    public function index() { /* ... */ }
    public function create() { /* ... */ }
    public function show(Campaign $campaign) { /* ... */ }
    public function store(Request $request) { /* ... */ }
    public function edit($id) { /* ... */ }
    public function update(Request $request, Campaign $campaign) { /* ... */ }
    public function destroy(Campaign $campaign) { /* ... */ }


    // --- sendmail メソッドを Mail::send() を使う形に戻す ---
    public function sendmail(Campaign $campaign)
    {
        Log::info('CampaignController@sendmail が実行されました。キャンペーンID: ' . $campaign->id);

        $visitors = Visitor::all();
        $sendCount = 0;
        $errorCount = 0;

        // テスト用 Visitor 追加 (不要なら削除)
        $testVisitor = new Visitor(['name' => 'テスト受信者', 'email' => 'hmasakix@gmail.com']);
        $visitors->push($testVisitor);

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
