<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css"
        integrity="sha512-LCTI5w2CEHSZZhR4jMKJjphcu7ZD30JTRFOnDvX+10GKntXGtE6wTkx3svyNmz1bxrZUEBtZkt2IVQMV3Crq4Q=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />@vite('resources/css/app.css')
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>

<body class="h-screen flex justify-center items-center bg-slate-100 client-container ">

    <form action="/register" method="POST" class="w-[30%] h-[70%] bg-white shadow-md flex flex-col p-10">
        @csrf
        <div class="flex flex-col mb-3">
            <h1 class="text-2xl text-center font-semibold mb-2">Register Account</h1>
        </div>
        <div class="flex flex-col mb-3">
            <label for="name" class="">Name</label>
            <input type="text" name="name" placeholder="Enter your name" class="border border-black px-3 py-2">
        </div>

        <div class="flex flex-col mb-3">
            <label for="username">Username</label>
            <input type="text" name="email" placeholder="Enter your username" class="border border-black px-3 py-2">
        </div>

        <div class="flex flex-col mb-3">
            <label for="password">Password</label>
            <input type="password" name="password" placeholder="Enter your password"
                class="border border-black px-3 py-2">
        </div>

        <div class="flex flex-col mb-3">
            <label for="transaction_id">Transaction</label>
            <select name="transaction_id" class="border border-black px-3 py-2">
                @foreach ($data as $item)
                <option value="{{$item->transaction_id}}">{{$item->transaction_name}}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 mt-2">
            Register
        </button>
    </form>
</body>

</html>