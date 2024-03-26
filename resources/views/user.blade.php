<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>User print Queue</title>
</head>
<body>
    <form action="/user/print_queue">
        @csrf
        @foreach($data as $items)
        <button name="transaction" value="{{$items['transaction_id']}}">{{$items['transaction_name']}}</button>
        @endforeach
    </form>

    <form action=""></form>
</body>
</html>