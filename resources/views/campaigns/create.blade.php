<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campaign登録</title>
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
            width: 120px;
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

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-group input[type="date"] {
            width: 50%; /* 日付は半分の幅 */
        }

        textarea {
            min-height: 120px;
        }

        .button-container {
            margin-top: 20px;
            text-align: center;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 16px;
            background-color: #5bc0de;
            border-radius: 5px;
        }

        .btn:hover {
            background-color: #31b0d5;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- サイドバー -->
    <div class="sidebar">
        <div><strong>AROUNDIE</strong></div>
        <a href="{{ route('dashboards.index') }}">ダッシュボード</a>
        <a href="{{ route('visitors.index') }}">来訪者一覧</a>
        <a href="{{ route('campaigns.index') }}">Campaign一覧</a>
        <a href="{{ route('campaigns.create') }}">Campaign登録</a>
        <a href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">ログアウト</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>

    <!-- メインコンテンツ -->
    <div class="content">
        <div class="header">Campaign登録</div>

        <form action="/campaigns" method="POST">
            @csrf
            <div class="form-group">
                <label for="title">タイトル</label>
                <input type="text" id="title" name="title" required>
            </div>

            <div class="form-group">
                <label for="content">内容</label>
                <textarea id="content" name="content" required></textarea>
            </div>

            <div class="form-group">
                <label for="start_date">開始日</label>
                <input type="date" id="start_date" name="start_date" required>
            </div>

            <div class="form-group">
                <label for="end_date">終了日</label>
                <input type="date" id="end_date" name="end_date" required>
            </div>

            <div class="button-container">
                <button type="submit" class="btn">登録</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>