<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    @auth
    <h1>Hello Welcome to Queue System</h1>
    <div>
        @isset($userData)
            <h4>{{ $userData }}</h4>

            @isset($queue)
                @foreach($queue as $item)
                    <h3>{{ $item->queue_no }}</h3>
                @endforeach
                @isset($first)
                    <h1>{{ $first->queue_no }}</h1>
                @endisset
            @else
            <h3>No Pending Queue</h3>
            @endisset
        @else
            <p>No transaction name available</p>
        @endisset
    </div>
    {{-- Dashboard of the super admin will be placed here --}}
    <form action="/logout" method="POST">
        @csrf
        <button>Logout</button>
    </form>
    @else
    <form action="/login" method="POST">
        @csrf
        <div>
            <label for="username">Username</label>
            <input type="text" name="lemail">
            <br>
            <label for="password">Password</label>
            <input type="password" name="lpassword">
        </div>
        <button>
            Submit
        </button>
    </form>

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
    
</body>
</html>