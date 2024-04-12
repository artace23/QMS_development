<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>User print Queue</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}"> @vite('resources/css/app.css')

</head>

<body>
    <div class="client-container">
        <div class="wrapper-s-trans">
            <h1 class="txt-select-trans p-5">SELECT TRANSACTION</h1>
        </div>
        <form action="/user/print_queue" class="forms-client">
            @csrf
            @foreach($data as $items)
            <div class="client-trans-container  ">
                <button class="btn-client" name="transaction"
                    value="{{$items['transaction_id']}}">{{$items['transaction_name']}}</button>
            </div>
            @endforeach
        </form>
        @if(isset($latestQueueNo))
        <div class="">
            <h1 class="text-3xl">Your Number is are {{ $latestQueueNo }}</h1>
        </div>
        @endif
    </div>
</body>

</html>