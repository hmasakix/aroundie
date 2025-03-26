<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規登録画面</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .login-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 300px;
        }
        .login-container h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .input-group {
            width: 100%;

            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 15px;
            text-align: left;
        }
        .input-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .input-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }
        .login-button {
            background-color: #8FC7C7;
            color: white;
            border: none;
            padding: 10px;
            width: 100%;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }
        .login-button:hover {
            background-color: #31b0d5;
        }
        .links {
            margin-top: 10px;
            font-size: 14px;
        }
        .links a {
            color: #8FC7C7;
            text-decoration: none;
        }
        .links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h1>新規登録画面</h1>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="input-group">
        <label for="name">お名前</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" required>
        </div>
        <div class="input-group">
            <label for="email">メールアドレス</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
        </div>
        <div class="input-group">
            <label for="password">パスワード</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="input-group">
            <label for="password_confirmation">パスワード（確認）</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>
        <button type="submit" class="login-button">新規登録</button>
    </form>
    <div class="links">
        <a href="{{ route('login') }}">ログインはこちら</a>
    </div>
</div>

</body>
</html>
