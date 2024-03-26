<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form action="/register" method="POST">
        @csrf
        <div>
            <label for="name">Name</label>
            <input type="text" name="name">
            <br>
            <label for="username">Username</label>
            <input type="text" name="email">
            <br>
            <label for="password">Password</label>
            <input type="password" name="password">
            
            <select name="transaction_id">
                @foreach ($data as $item)
                        <option value="{{$item->transaction_id}}">{{$item->transaction_name}}</option>
                @endforeach
            </select>

        </div>
        <button>
            Register
        </button>
    </form>
</body>
</html>