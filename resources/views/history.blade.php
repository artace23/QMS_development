<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>History</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite('resources/css/app.css')

</head>

<body>
    <div class="flex w-full h-full bg-slate-100 overflow-hidden mt-10">
        <div class="bh-full w-full bg-slate-100 overflow-scroll">
            <div class="mt-10 flex-col w-full flex justify-center items-center">
                <div class="w-[90%] flex gap-5 items-center bg-white p-3">
                    <h1 class="text-xl"> Queue History </h1>
                </div>
                @isset($data)
                <table class="table-user w-[90%] bg-white p-5">
                    <thead class="thead font-bold text-base bg-black text-white p-3">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">ID No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Queue No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Transaction</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Timestamp</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Staff</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($data as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{$item->manange_id}}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{$item->queue_no}}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{$item->transaction_name}}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{$item->timestamp}}</td>
                            @if ($item->status == 1)
                                <td class="px-6 py-4 whitespace-nowrap">Pending</td>
                            @else
                                @if ($item->status == 2)
                                    <td class="px-6 py-4 whitespace-nowrap">Ongoing</td>
                                @else
                                    <td class="px-6 py-4 whitespace-nowrap">Done</td>
                                @endif
                            @endif
                            <td class="px-6 py-4 whitespace-nowrap">{{$item->user_id}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endisset
            </div>
        </div>
    </div>
</body>

</html>