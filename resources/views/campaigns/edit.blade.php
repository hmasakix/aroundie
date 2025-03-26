<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campaign編集</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            display: flex;
        }
        .sidebar {
            width: 200px;
            background-color: #f8f9fa;
            padding: 20px;
            height: 100vh;
        }
        .sidebar a {
            display: block;
            color: #007bff;
            text-decoration: none;
            margin: 10px 0;
        }
        .content {
            flex-grow: 1;
            padding: 20px;
        }
        .header {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            padding: 10px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .button-container {
            margin-top: 20px;
            text-align: center;
        }
        .button {
            padding: 10px 20px;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 16px;
            margin: 5px;
            background-color: #5bc0de;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .error-message {
            color: red;
            font-size: 12px;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="sidebar">
            <div><strong>ロゴ</strong></div>
            <a href="#">ダッシュボード</a>
            <div>メルマガ一覧</div>
            <a href="#">・メルマガ新規作成</a>
            <a href="#">・メルガマ実績確認</a>
            <a href="{{ route('visitors.index') }}" class=>来訪者一覧</a>
            <a href="#">・来訪者編集</a>
            <a href="{{ route('campaigns.index') }}" class=>Campaign一覧</a>
            <a href="{{ route('campaigns.create') }}" class=>・Campaign登録</a>
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                ログアウト
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                 @csrf
            </form>
        </div>
        
        <div class="content">
            <div class="header">Campaign編集</div>

            <form method="POST" action="{{ route('campaigns.update', $campaign) }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="title">タイトル</label>
                    <input type="text" name="title" id="title" value="{{ $campaign->title }}">
                    @error('title')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="content">内容</label>
                    <textarea name="content" id="content" rows="4">{{ $campaign->content }}</textarea>
                    @error('content')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="button-container">
                     <a href="{{ route('campaigns.show', $campaign) }}" class="button">詳細に戻る</a>

                    <button type="submit" class="button">更新</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>

