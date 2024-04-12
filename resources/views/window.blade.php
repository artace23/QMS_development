<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
</head>

<style>
.table-user {
    border-collapse: collapse;
}

thead {
    background-color: black;
    color: white;
}

.table-user td,
.table-user th {
    border: 1px solid #ddd;
    align-items: left;
}

.table-user th {
    padding: 10px;
    text-align: left;
    font-weight: lighter;
}

.table-user td {
    padding: 5px;
}

tbody tr:nth-child(even) {
    background-color: #f2f2f2;
}

tr:hover {
    cursor: pointer;
}
</style>

<body>
    <div class="bg-white h-full mt-10 p-10 w-full overflow-scroll rounded-md">
        <form action="/addwindow" method="POST">
            @csrf
            <button class="btn btn-info mb-1">Add Window</button>
            <input type="text" name="wname" placeholder="Enter Window Number"
                class="rounded-md border-2 border-black px-3 py-2">
                @isset($transaction)
                <select name="transaction_id" class="rounded-md border-2 border-black px-3 py-2">
                    @foreach ($transaction as $item)
                    <option value="{{$item->transaction_id}}">{{$item->transaction_name}}</option>
                    @endforeach
                </select>
                @endisset
        </form>
        @isset($prompt)
        <h3>{{ $prompt }}</h3>
        @endisset

        @isset($data)
        <table class="table-user w-full p-5">
            <thead>
                <tr>
                    <th>Window Number</th>
                    <th>Transaction Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $item)
                <tr>
                    <td>{{ $item->window_no }}</td>
                    <td>{{ $item->transaction_name }}</td>
                    <td>
                        <button class="btn btn-warning"
                            onclick="document.getElementById('my_modal_{{$item->window_id}}').showModal()">EDIT</button>

                            <dialog id="my_modal_{{$item->window_id}}" class="modal modal-bottom sm:modal-middle">
                                <div class="modal-box  text-center">
                                    <h3 class="font-bold text-lg">Edit Window</h3>
    
                                    <form action="/edit-window" method="POST">
                                        @csrf
                                        <br>
                                        <!--butang edit dria forms-->
                                        <label for="wname">Window No</label><br>
                                        <input type="text" name="id" value="{{$item->window_id}}" class="rounded-md border-2 border-black px-3 py-2" hidden>
                                        <input type="text" name="wname" value="{{$item->window_no}}" class="rounded-md border-2 border-black px-3 py-2">
                                        <br><br>
                                        <label for="tname">Name</label><br>
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
    
                                    <button class="btn float-start"
                                        onclick="document.getElementById('my_modal_{{$item->window_id}}').close()">Close</button>
                                </div>
                            </dialog> 

                            <button class="btn btn-error"
                            onclick="document.getElementById('my_delmodal_{{$item->window_id}}').showModal()">Delete</button>
                            <!-- Modal -->
                            <dialog id="my_delmodal_{{$item->window_id}}" class="modal modal-bottom sm:modal-middle">
                                <div class="modal-box">
                                    <h3 class="font-bold text-lg">Delete Window</h3>
                                    <!-- Your edit form goes here -->
                                    <form action="/delete-window" method="POST">
                                        @csrf
                                        <!--butang delete dria forms-->
                                        <div class="text-center">
                                            <br>
                                            <input name="id" value="{{$item->window_id}}" hidden>
                                            <h3>Are you sure you want to delete this?</h3>
                                            <br>
                                        </div>

                                        <button class="btn btn-success float-end" type="submit">Yes</button>
                                    </form>
                                    <!-- Close button -->
                                    <button class="btn"
                                        onclick="document.getElementById('my_delmodal_{{$item->transaction_id}}').close()">No</button>
                                </div>
                            </dialog>
                        
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endisset
    </div>

    <script>
    @if(isset($successMessage))
    alert("{{ $successMessage }}");
    @endif
    </script>
</body>

</html>