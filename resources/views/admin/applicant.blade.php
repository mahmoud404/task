<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
<div class="flex-center position-ref full-height">
    @if (Route::has('login'))
        <div class="top-right links">
            @auth
                <a href="{{ url('/home') }}">Home</a>
            @else
                <a href="{{ route('login') }}">Login</a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}">Register</a>
                @endif
            @endauth
        </div>
    @endif

    <div class="container">
        <div class="title m-b-md">
            Applicant Form
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session()->has('msg'))
            <div class="alert alert-success">
                <h3>{{session()->get('msg')}}</h3>
            </div>
        @endif
        <form method="post" enctype="multipart/form-data">
            @csrf
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Name: </label>
                    <input type="text" name="name" value="{{old('name')}}" class="form-control"/>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label>E-mail: </label>
                    <input type="email" name="email" value="{{old('email')}}" class="form-control"/>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Phone: </label>
                    <input type="text" name="phone" value="{{old('phone')}}" class="form-control"/>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Upload CV: </label>
                    <input type="file" name="cv" class="form-control"/>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Passport Image: </label>
                    <input type="file" name="passport_image" class="form-control"/>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <input type="submit" value="Send" class="btn btn-primary">
            </div>
        </form>
    </div>
</div>
</body>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</html>
