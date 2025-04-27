<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campaign一覧</title>
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
    <div><strong>Aroundie</strong></div>
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
        <div class="header">Campaign一覧</div>
            @if (session('success'))
                 <div style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                     {{ session('success') }}
                 </div>
            @endif
            
        <table>
            <colgroup>
                <col style="width: 30px;">  
                <col>
                <col>
                <col>
                <col>
                <col>
            </colgroup>
            <tr>
                <th>CampaignID</th>
                <th>タイトル</th>
                <th>内容</th>
                <th>送信</th>
                <th>編集</th>
                <th>削除</th>
            </tr>
            @foreach ($campaigns as $campaign)
            <tr>
                <td>{{ $campaign->id }}</td>
                <th>{{ $campaign->title }}</th>
                <td>{{ $campaign->content }}</td>
                <td>
                    <form action="{{ route('campaigns.sendmail', ['campaign' => $campaign->id]) }}" method="POST">
                        @csrf
                        <button type="submit" onclick="return confirm('本当に送信しますか？');">送信</button>
                    </form>
                </td>
                <td><a href="{{ route('campaigns.edit', ['campaign' => $campaign->id])  }}" >編集</a></td>
                <td>
                    <form action="{{ route('campaigns.destroy', $campaign->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('本当に削除しますか？');">削除</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </table> 

        <div class="button-container">
            <!-- <a href="http://localhost:8025" target="_blank" class="btn btn-send">送信結果はこちら</a> -->
            <a href="{{ route('campaigns.create') }}" class="btn btn-create">新規作成</a>
        </div>
    </div>
</div>

</body>
</html> 
