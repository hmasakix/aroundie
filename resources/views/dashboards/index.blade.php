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
        <div><strong>AROUNDIE</strong></div>
        <a href="{{ route('dashboards.index') }}" >ダッシュボード</a>
        <!-- <div>メルマガ一覧</div>
        <a href="#">・メルマガ新規作成</a>
        <a href="#">・メルガマ実績確認</a> -->
        <a href="{{ route('visitors.index') }}" class=>来訪者一覧</a>
        <!-- <a href="#">・来訪者編集</a> -->
        <a href="{{ route('campaigns.index') }}" class=>Campaign一覧</a>
        <a href="{{ route('campaigns.create') }}" class=>Campaign登録</a>
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

    <table style="margin-top: 20px; width: 240px; table-layout: fixed; border-collapse: collapse; background-color: #ffffff;">
        <colgroup>
            <col style="width: 150px;">
            <col style="width: 120px;">
        </colgroup>
        <thead>
            <tr>
                <th style="padding: 10px; text-align: left; background-color: #f2f2f2;"></th>
                <th style="padding: 10px; text-align: right; background-color: #f2f2f2;">件数</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="padding: 10px;">来訪者リスト数</td>
                <td style="padding: 10px; text-align: right; font-weight: bold;">{{ $visitorCount }} 件</td>
            </tr>
            <tr>
                <td style="padding: 10px; color: #007bff;">Campaign登録数</td>
                <td style="padding: 10px; text-align: right; color: #007bff; font-weight: bold;">{{ $campaignCount }} 件</td>
            </tr>
        </tbody>
    </table>
</div>

</body>
</html> 
