<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>キャンペーンのお知らせ</title>
    <style>
        body {
            font-family: sans-serif;
            line-height: 1.8;
            color: #333;
        }
        .container {
            padding: 20px;
        }
        .label {
            font-weight: bold;
        }
        .value {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>キャンペーンのお知らせ</h2>

        <p><span class="label">お名前:</span><br>
        <span class="value">{{ $data['name'] }}</span></p>

        <p><span class="label">メールアドレス:</span><br>
        <span class="value">{{ $data['email'] }}</span></p>

        <p><span class="label">件名:</span><br>
        <span class="value">{{ $data['subject'] }}</span></p>
    
        <p><span class="label">タイトル:</span><br>
        <span class="value">{{ nl2br(e($data['title'])) }}</span></p>
        
        <p><span class="label">キャンペーン内容:</span><br>
        <span class="value">{!! nl2br(e($data['message'])) !!}</span></p>
    </div>
</body>
</html>
