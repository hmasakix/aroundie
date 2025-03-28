<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;

// トップ
Route::get('/', function () {
    return view('welcome');
});

// Campaignルート（明示的にすべて定義）
Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns.index');
Route::get('/campaigns/create', [CampaignController::class, 'create'])->name('campaigns.create');
Route::post('/campaigns', [CampaignController::class, 'store'])->name('campaigns.store');
Route::get('/campaigns/{campaign}', [CampaignController::class, 'show'])->name('campaigns.show');
Route::get('/campaigns/{campaign}/edit', [CampaignController::class, 'edit'])->name('campaigns.edit');
Route::put('/campaigns/{campaign}', [CampaignController::class, 'update'])->name('campaigns.update');
Route::delete('/campaigns/{campaign}', [CampaignController::class, 'destroy'])->name('campaigns.destroy');
Route::post('/campaigns/{campaign}/sendmail', [CampaignController::class, 'sendmail'])->name('campaigns.sendmail');

// Dashboard
Route::get('/dashboards', [DashboardController::class, 'index'])->name('dashboards.index');

// Visitor一覧
Route::get('/visitors', [VisitorController::class, 'index'])->name('visitors.index');

// 認証
Auth::routes([
    'register' => true,
]);

Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

Route::get('/home', fn() => redirect('/dashboards'));

Route::get('/contact', fn() => view('contact'));

Route::get('/send-test-mail', function () {
    $data = [
        'name' => 'テスト太郎',
        'email' => 'test@example.com',
        'subject' => 'これはテスト件名です',
        'message' => "これはテストの本文です。\n改行もOK!"
    ];
    Mail::to('test@example.com')->send(new TestMail($data));
    return 'メール送信完了！';
});
