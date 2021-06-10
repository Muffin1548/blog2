<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blog</title>
    <link href="{{ asset('/css/app.css') }}" rel="stylesheet">
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4"
            crossorigin="anonymous"></script>
</head>
<body>
<nav class="navbar navbar-default">
    <div class="container">
        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
            <a href="{{url('home')}}">
                <button type="button" class="btn btn-primary">Home</button>
            </a>
            @if (Auth::guest())
                <a href="{{ url('/auth/login') }}">
                    <button type="button" class="btn btn-primary">Login</button>
                </a>
                <a href="{{ url('/auth/register') }}">
                    <button type="button" class="btn btn-primary">Register</button>
                </a>
            @else
                <button type="button" class="btn btn-primary">
                    {{ Auth::user()->name }}
                </button>
                @if (Auth::user()->canPost())
                    <a href="{{ url('new-post') }}">
                        <button type="button" class="btn btn-primary">Add new post</button>
                    </a>
                    <a href="{{ url('user/'.Auth::id().'/posts') }}">
                        <button type="button" class="btn btn-primary">My Posts</button>
                    </a>
                @endif
                <a href="{{ url('user/'.Auth::id()) }}">
                    <button type="button" class="btn btn-primary">My Profile</button>
                </a>
                <a href="{{ url('auth/logout') }}">
                    <button type="button" class="btn btn-primary">Logout</button>
                </a>
            @endif
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li>
                    <a href="{{ url('/') }}">Home</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="container">
    @if (Session::has('message'))
        <div class="flash alert-info">
            <p class="panel-body">
                {{ Session::get('message') }}
            </p>
        </div>
    @endif
    @if ($errors->any())
        <div class='flash alert-danger'>
            <ul class="panel-body">
                @foreach ( $errors->all() as $error )
                    <li>
                        {{ $error }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2>@yield('title')</h2>
                    @yield('title-meta')
                </div>
                <div class="panel-body">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Scripts -->
</body>
</html>
