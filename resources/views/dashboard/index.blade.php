<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
            background-color: #FFFBD5; /* レモンイエロー */
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
        .btn {
            padding: 10px 20px;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 16px;
            margin: 5px;
        }
        .btn-send {
            background-color: #5bc0de;
        }
        .btn-create {
            background-color: #007bff;
        }
    </style>
</head>

<body>

<div class="container">
    <div class="sidebar">
        <div><strong>ロゴ</strong></div>
        <a href="{{ route('dashboards.index') }}" >ダッシュボード</a>
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
        <div class="header">ダッシュボード</div>
        <table>
            <tr>
                <th>ID</th>
                <th>名前</th>
                <th>メールアドレス</th>
                <th>登録日</th>
            </tr>
            @foreach($visitors as $visitor)
                <tr>
                    <td>{{ $visitor->id }}</td>
                    <td>{{ $visitor->name }}</td>
                    <td>{{ $visitor->email }}</td>
                    <td>{{ $visitor->created_at }}</td>
                </tr>
            @endforeach
        </table>
    </div>

</body>
</html> 
