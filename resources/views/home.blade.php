<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Page</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    @vite('resources/css/app.css')
</head>

<body>

    @auth
    <div class="h-screen flex bg-slate-100 justify-center items-center relative bg-user">
        <nav class="absolute top-0 flex justify-between items-center w-full bg-black p-3 text-white">
            @isset($userData)
            @foreach($userData as $user)
            <h4>{{ $user }}</h4>
            @endforeach
            <form action="/logout" method="POST">
                @csrf
                <button class="btn btn-error px-3 py-1 rounded-lg">Logout</button>
            </form>
            @endisset
        </nav>

            <div class="grid grid-cols-2 gap-4 w-[70rem] h-[35rem] mt-10 border-[5px] border-black">
                <div class=" p-5 overflow-scroll">
                    <div class="w-full bg-black text-white flex p-5 justify-between items-center mb-2">
                        <p class="text-3xl font-bold">PENDING QUEUES</p>
                    </div>
    
                    <ul class="w-full bg-white h-full" id="pendingQueueList">
                        
                    </ul>
                </div>
    
                <div class=" p-5 bg-white flex flex-col gap-4">
                    <div class="w-full bg-black text-white flex p-5 justify-between mb-2 items-center">
                        <h1 class="text-3xl font-bold">SERVING</h1>
                    </div>

                    
                    <form action="/next_queue" method="POST">
                        @csrf
                        @isset($first)
                        <input type="text"
                            class="h-[200px] w-[500px] text-center text-[100px] font-bold border-2 rounded-md border-black"
                            value="{{ $first->queue_no }}" name="ongoing_no" readonly>
                            <input type="text" id="pending_no" hidden>
                            <button type="submit" class="mt-3 btn bg-black text-white w-full h-[100px] text-xl ">Next</button>
                        @endisset
                    </form>

                    <form action="/call_queue" method="POST">
                        @csrf
                        @isset($first)
                        <input type="text"
                            class="h-[200px] w-[500px] text-center text-[100px] font-bold border-2 rounded-md border-black"
                            value="{{ $first->queue_no }}" name="ongoing_no" hidden>
                        @else
                            <input type="text" name="pending_no" id="pending_no" hidden>
                            <button type="submit" class="mt-3 btn bg-black text-white w-full h-[100px] text-xl ">Call</button>
                        @endisset
                    </form>

                    <div id="timerAndDate" class="w-full flex items-center justify-between">
                        <p id="timer" class="text-2xl font-semibold">TIMER</p>
                        <div class="flex gap-3">
                            <p id="currentDate" class="text-2xl font-semibold">DATE</p>
                            <p id="dayOfWeek" class="text-2xl font-semibold">DAY</p>
                        </div>
                    </div>
                </div>
    
            </div>
        
        
    </div>
    

    @else

    <div class="forms-container">
        <form action="/login" method="POST" class="forms">
            <h1 class="login-title">Login Account</h1>
            @csrf
            <div class="input-container-login">
                <label for="username">Username</label>
                <input type="text" name="lemail" placeholder="Enter your username">
            </div>
            <div class="input-container-login">
                <label for="password">Password</label>
                <input type="password" name="lpassword" placeholder="Enter your username">
            </div>

            <div class="input-container-login">
                <button class="bg-black text-white p-3 mt-5">Login</button>
            </div>
        </form>
    </div>



    {{-- <form action="/register" method="POST">
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
        </div>
        <button>
            Submit
        </button>
    </form> --}}

    @endauth
    <script>
    function updateTimerAndDate() {

        let currentDate = new Date(new Date().toLocaleString("en-US", {
            timeZone: "Asia/Manila"
        }));
        let formattedDate = currentDate.toLocaleDateString();
        let formattedTime = currentDate.toLocaleTimeString();
        let dayOfWeek = currentDate.toLocaleDateString('en-US', {
            weekday: 'long'
        });


        document.getElementById('timer').textContent = formattedTime;
        document.getElementById('currentDate').textContent = formattedDate;
        document.getElementById('dayOfWeek').textContent = dayOfWeek;
    }


    setInterval(updateTimerAndDate, 1000);


    updateTimerAndDate();

    
    // Function to fetch pending queue numbers from the server
    function fetchPendingQueues() {
        fetch('/fetch-staff-queues')
            .then(response => response.json())
            .then(data => {
                const pendingQueueList = document.getElementById('pendingQueueList');
                // Clear previous data
                pendingQueueList.innerHTML = '';

                // Populate the list with the fetched data
                data.forEach(queue => {
                    let li = document.createElement('li');
                    li.className = 'text-3xl font-bold border-2 rounded-md border-black m-3 p-3';
                    li.textContent = queue.queue_no;
                    pendingQueueList.appendChild(li);
                });

                if (data.length > 0) {
                    const firstQueue = data[0];
                    const firstQueueInput = document.getElementById('pending_no');
                    firstQueueInput.value = firstQueue.queue_no;
                    // You can now use firstQueue.queue_no as needed
                } else {
                    const firstQueueInput = document.getElementById('pending_no');
                    firstQueueInput.value = "";
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // Fetch pending queue data initially when the page loads
    fetchPendingQueues();

    // Fetch pending queue data periodically every 5 seconds
    setInterval(fetchPendingQueues, 2000);

    </script>
</body>

</html>