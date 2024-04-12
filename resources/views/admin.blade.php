<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite('resources/css/app.css')
</head>

<body>
    <div class="h-screen flex">
        <div class="drawer lg:drawer-open bg-base-200">
            <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />
            <div class="drawer-content flex flex-col items-center justify-center relative">
                <div class="absolute top-0 w-full bg-base-100  shadow-md flex justify-between p-1">
                    <div class="flex items-center justify-center gap-5">
                        <label class="btn btn-circle swap swap-rotate drawer-button lg:hidden" for="my-drawer-2">
                            <input type="checkbox" id="menu-icon" />
                            <svg class="swap-off fill-current" xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                viewBox="0 0 512 512">
                                <path d="M64,384H448V341.33H64Zm0-106.67H448V234.67H64ZM64,128v42.67H448V128Z" />
                            </svg>
                            <svg class="swap-on fill-current" xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                viewBox="0 0 512 512">
                                <polygon
                                    points="400 145.49 366.51 112 256 222.51 145.49 112 112 145.49 222.51 256 112 366.51 145.49 400 256 289.49 366.51 400 400 366.51 289.49 256 400 145.49" />
                            </svg>
                        </label>
                        <p class="texl-2xl font-base ml-5">Dashboard / <a href="#" class="text-sky-600"
                                id="textTitle">Home</a></p>
                    </div>
                    <div class="dropdown dropdown-bottom dropdown-end">
                        <div tabindex="0" role="button" class="btn m-1 btn-error px-1">LOGOUT</div>
                        <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                            <li>
                                <form action="/logout" method="POST"
                                    class="logout-forms flex flex-col justify-center items-center">
                                    @csrf
                                    <p>do you really want to exit ?</p>
                                    <button type="submit" class="btn w-full btn-warning">EXIT</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
                <div id="content-area" class=" h-full w-full   ">
                    <div class="flex w-full items-center gap-3 justify-center  mt-20 flex-wrap  overflow-scroll ">
                        <div class="shadow-md h-[150px] bg-info w-[250px] flex justify-around items-center">
                            <div class="flex flex-col mt-5 ">
                                <i class="fa-solid fa-users text-6xl"></i>
                                <h1 class="text-base font-bold mt-3 uppercase"> Total Users</h1>
                            </div>
                            @isset($userCount)
                            <h1 class="text-5xl mr-5 font-bold mt-3 uppercase">{{$userCount}}</h1>
                            @else
                            <h1 class="text-5xl mr-5 font-bold mt-3 uppercase">0</h1>
                            @endisset
                        </div>
                        <div class="shadow-md h-[150px] bg-warning w-[250px] flex justify-around items-center">
                            <div class="flex flex-col mt-5 ">
                                <i class="fa-solid fa-user  text-6xl"></i>
                                <h1 class="text-base font-bold mt-3 uppercase">Total Staff</h1>
                            </div>
                            @isset($staffCount)
                            <h1 class="text-5xl mr-5 font-bold mt-3 uppercase">{{$staffCount}}</h1>
                            @else
                            <h1 class="text-5xl mr-5 font-bold mt-3 uppercase">0</h1>
                            @endisset
                        </div>
                        <div class="shadow-md h-[150px] bg-success w-[250px] flex justify-around items-center">
                            <div class="flex flex-col mt-5 ">
                                <i class="fa-solid fa-user  text-6xl"></i>
                                <h1 class="text-base font-bold mt-3 uppercase">Total Queue</h1>
                            </div>
                            @isset($queueCount)
                            <h1 class="text-5xl mr-5 font-bold mt-3 uppercase">{{$queueCount}}</h1>
                            @else
                            <h1 class="text-5xl mr-5 font-bold mt-3 uppercase">0</h1>
                            @endisset
                        </div>
                        <div class="shadow-md h-[150px] bg-error w-[250px] flex justify-around items-center">
                            <div class="flex flex-col mt-5 ">
                                <i class="fa-solid fa-user  text-6xl"></i>
                                <h1 class="text-base font-bold mt-3 uppercase">Total Windows</h1>
                            </div>
                            @isset($windowCount)
                            <h1 class="text-5xl mr-5 font-bold mt-3 uppercase">{{$windowCount}}</h1>
                            @else
                            <h1 class="text-5xl mr-5 font-bold mt-3 uppercase">0</h1>
                            @endisset
                        </div>
                    </div>

                </div>
            </div>
            <div class="drawer-side ">
                <label for="my-drawer-2" aria-label="close sidebar" class="drawer-overlay"></label>
                <ul class="menu p-4 w-80 min-h-full bg-base-100 text-base-content border border-base-200">
                    <h1 class="text-2xl font-semibold text-center p-5">QMS</h1>
                    <li class="flex">
                        <a href="javascript:void(0);" onclick="loadDashboard()"> <i
                                class="fa-solid fa-gauge text-lg"></i>Dashboard</a>
                    </li>
                    <li><a href="javascript:void(0);" onclick="loadStaffManagement()"> <i
                                class="fa-regular fa-user text-lg"></i>Staff Management</a></li>
                    <li><a href="javascript:void(0);" onclick="loadTransaction()"> <i
                                class="fa-solid fa-bars-progress text-lg"></i></i>Manage Transaction</a></li>
                    <li><a href="javascript:void(0);" onclick="loadWindow()"> <i
                        class="fa-solid fa-bars-progress text-lg"></i></i>Manage Window</a></li>
                    <li><a href="javascript:void(0);" onclick="loadHistory()"> <i
                                class="fa fa-history text-lg"></i>History</a></li>
                    <li><a href="javascript:void(0);" onclick="loadSettings()"> <i
                                class="fa-solid fa-gear text-lg"></i>Settings</a></li>
                </ul>
            </div>
        </div>
    </div>

    <script>
    function loadStaffManagement() {
        fetch('http://127.0.0.1:8000/staff ')
            .then(response => response.text())
            .then(data => {
                document.getElementById('content-area').innerHTML = data;
                const textTitle = document.querySelector('#textTitle').innerHTML = "Staff Management"
            })
            .catch(error => {
                console.error('Error loading content:', error);
            });
    }

    function loadWindow() {
        fetch('http://127.0.0.1:8000/window ')
            .then(response => response.text())
            .then(data => {
                document.getElementById('content-area').innerHTML = data;
                const textTitle = document.querySelector('#textTitle').innerHTML = "Window Management"
            })
            .catch(error => {
                console.error('Error loading content:', error);
            });
    }

    function loadDashboard() {
        window.location.href = 'http://127.0.0.1:8000/';
    }


    function loadHistory() {
        fetch('http://127.0.0.1:8000/history')
            .then(response => response.text())
            .then(data => {
                document.getElementById('content-area').innerHTML = data;
                const textTitle = document.querySelector('#textTitle').innerHTML = "History"
            })
            .catch(error => {
                console.error('Error loading content:', error);
            });
    }

    function loadTransaction() {
        fetch('http://127.0.0.1:8000/transaction')
            .then(response => response.text())
            .then(data => {
                document.getElementById('content-area').innerHTML = data;
                const textTitle = document.querySelector('#textTitle').innerHTML = "Transaction Management"
            })
            .catch(error => {
                console.error('Error loading content:', error);
            });
    }


    function loadSettings() {
        fetch('settings.blade.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('content-area').innerHTML = data;
            })
            .catch(error => {
                console.error('Error loading content:', error);
            });
    }
    </script>

</body>

</html>