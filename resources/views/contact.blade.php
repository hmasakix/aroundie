<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>お問い合わせフォーム</title>
    <style>
        body {
            font-family: sans-serif;
            padding: 2rem;
            max-width: 600px;
            margin: auto;
        }
        label {
            display: block;
            margin-top: 1rem;
            font-weight: bold;
        }
        input, textarea {
            width: 100%;
            padding: 0.5rem;
            margin-top: 0.3rem;
        }
        .error {
            color: red;
            font-size: 0.9rem;
        }
        .success {
            background-color: #d4edda;
            padding: 1rem;
            margin-top: 1rem;
            border-left: 5px solid #28a745;
            color: #155724;
        }
    </style>
</head>
<body>
    <h1>お問い合わせフォーム</h1>

    {{-- 成功メッセージ --}}
    @if(session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    {{-- バリデーションエラー --}}
    @if($errors->any())
        <div class="error">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- フォーム本体 --}}
    <form action="{{ url('/contact') }}" method="POST">
        @csrf

        <label for="name">お名前</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}">

        <label for="email">メールアドレス</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}">

        <label for="subject">件名</label>
        <input type="text" id="subject" name="subject" value="{{ old('subject') }}">

        <label for="message">お問い合わせ内容</label>
        <textarea id="message" name="message" rows="6">{{ old('message') }}</textarea>

        <button type="submit" style="margin-top: 1rem;">送信</button>
    </form>
</body>
</html>
