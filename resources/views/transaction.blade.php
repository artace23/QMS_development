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
        <form action="/manage_transaction" method="POST">
            @csrf
            <button class="btn btn-info mb-1">Add Transaction</button>
            <input type="text" name="tname" placeholder="Enter transaction name"
                class="rounded-md border-2 border-black px-3 py-2">
            <input type="text" name="tsrange" placeholder="Enter Start Range"
            class="rounded-md border-2 border-black px-3 py-2">
            <input type="text" name="tlrange" placeholder="Enter End Range"
            class="rounded-md border-2 border-black px-3 py-2">
        </form>
        @isset($prompt)
        <h3>{{ $prompt }}</h3>
        @endisset

        @isset($data)
        <table class="table-user w-full p-5">
            <thead>
                <tr>
                    <th>Transaction</th>
                    <th>Transaction Name</th>
                    <th>Start Range</th>
                    <th>End Range</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $item)
                <tr>
                    <td>{{ $item->transaction_id }}</td>
                    <td>{{ $item->transaction_name }}</td>
                    <td>{{ $item->queue_srange }}</td>
                    <td>{{ $item->queue_lrange }}</td>
                    <td>

                        <button class="btn btn-warning"
                            onclick="document.getElementById('my_modal_{{$item->transaction_id}}').showModal()">EDIT</button>

                        <!-- Modal -->
                        <dialog id="my_modal_{{$item->transaction_id}}" class="modal modal-bottom sm:modal-middle">
                            <div class="modal-box">
                                <h3 class="font-bold text-lg">Edit Staff</h3>

                                <form action="/edit-transaction" method="POST">
                                    @csrf
                                    <!--butang edit dria forms-->
                                    <label for="id">ID</label>
                                    <input type="text" name="id" value="{{$item->transaction_id}}" class="rounded-md border-2 border-black px-3 py-2" readonly>
                                    <br><br>
                                    <label for="tname">Name</label>
                                    <input type="text" name="tname" value="{{$item->transaction_name}}" class="rounded-md border-2 border-black px-3 py-2">
                                    <br><br>
                                    <label for="tsrange">Strating Range</label>
                                    <input type="text" name="tsrange" value="{{$item->queue_srange}}" class="rounded-md border-2 border-black px-3 py-2">
                                    <br><br>
                                    <label for="tlrange">Ending Range</label>
                                    <input type="text" name="tlrange" value="{{$item->queue_lrange}}" class="rounded-md border-2 border-black px-3 py-2">
                                    <br><br>
                                    <button class="btn btn-success float-end" type="submit">Update</button>
                                </form>

                                <button class="btn"
                                    onclick="document.getElementById('my_modal_{{$item->transaction_id}}').close()">Close</button>
                            </div>
                        </dialog> 
                        <!-- Button to open modal -->
                        <button class="btn btn-error"
                            onclick="document.getElementById('my_delmodal_{{$item->transaction_id}}').showModal()">Delete</button>
                        <!-- Modal -->
                        <dialog id="my_delmodal_{{$item->transaction_id}}" class="modal modal-bottom sm:modal-middle">
                            <div class="modal-box">
                                <h3 class="font-bold text-lg">Delete Staff</h3>
                                <!-- Your edit form goes here -->
                                <form action="/delete-transaction" method="POST">
                                    @csrf
                                    <!--butang delete dria forms-->
                                    <div class="text-center">
                                        <br>
                                        <input name="id" value="{{$item->transaction_id}}" hidden>
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