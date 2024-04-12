<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
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
                    <form action="/manage_staff" method="GET">
                        @csrf
                        <button class="btn btn-info mb-1">Add Staff</button>
                    </form>
                    <h1 class="text-xl"> Staff Management</h1>
                </div>
                @isset($data)
                <table class="table-user w-[90%] bg-white p-5">
                    <thead class="thead font-bold text-base bg-black text-white p-3">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Created At</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Updated At</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Transaction
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Window
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($data as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{$item->id}}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{$item->name}}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{$item->email}}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{$item->created_at}}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{$item->updated_at}}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{$item->transaction_name}}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{$item->window_id}}</td>
                            <td>

                                <button class="btn btn-warning"
                                    onclick="document.getElementById('my_modal_{{$item->id}}').showModal()">EDIT</button>

                                <!-- Modal -->
                                <dialog id="my_modal_{{$item->id}}" class="modal modal-bottom sm:modal-middle">
                                    <div class="modal-box">
                                        <h3 class="font-bold text-lg">Edit Staff</h3>

                                        <form action="/edit-data" method="POST">
                                            @csrf
                                            <!--butang edit dria forms-->
                                            <label for="id">ID</label>
                                            <input type="text" name="id" value="{{$item->id}}" class="rounded-md border-2 border-black px-3 py-2" readonly>
                                            <br><br>
                                            <label for="sname">Name</label>
                                            <input type="text" name="sname" value="{{$item->name}}" class="rounded-md border-2 border-black px-3 py-2">
                                            <br><br>
                                            <label for="email">Email</label>
                                            <input type="text" name="email" value="{{$item->email}}" class="rounded-md border-2 border-black px-3 py-2">
                                            <br><br>
                                            <label for="tname">Transaction</label>
                                            <select name="tname" class="rounded-md border-2 border-black px-3 py-2">
                                                <option value="{{$item->transaction_id}}" selected>{{$item->transaction_name}}</option>
                                                @foreach ($transaction as $items)
                                                    @if($item->transaction_id !== $items->transaction_id)
                                                    <option value="{{$items->transaction_id}}">{{$items->transaction_name}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <br><br>
                                            <button class="btn btn-success float-end" type="submit">Update</button>
                                        </form>

                                        <button class="btn"
                                            onclick="document.getElementById('my_modal_{{$item->id}}').close()">Close</button>
                                    </div>
                                </dialog> <!-- Button to open modal -->
                                <button class="btn btn-error"
                                    onclick="document.getElementById('my_delmodal_{{$item->id}}').showModal()">Delete</button>
                                <!-- Modal -->
                                <dialog id="my_delmodal_{{$item->id}}" class="modal modal-bottom sm:modal-middle">
                                    <div class="modal-box">
                                        <h3 class="font-bold text-lg">Delete Staff</h3>
                                        <!-- Your edit form goes here -->
                                        <form action="/delete-data" method="POST">
                                            @csrf
                                            <!--butang delete dria forms-->
                                            <div class="text-center">
                                                <br>
                                                <input name="id" value="{{$item->id}}" hidden>
                                                <h3>Are you sure you want to delete this?</h3>
                                                <br>
                                            </div>

                                            <button class="btn btn-success float-end" type="submit">Yes</button>
                                        </form>
                                        <!-- Close button -->
                                        <button class="btn"
                                            onclick="document.getElementById('my_delmodal_{{$item->id}}').close()">No</button>
                                    </div>
                                </dialog>
                            </td>
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