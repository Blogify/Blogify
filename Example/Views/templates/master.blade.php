<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blogify | Blog package for Laravel 5</title>

    <link href="{{ asset('/css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset("assets/blogify/assets/stylesheets/custom.css") }}" />

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Blogify</a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><a href="{{ url('/') }}">Home</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-8">
                    @yield('content')
                </div>
                <div class="col-md-4">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">Archive</div>
                                <div class="panel-body">
                                    <ul class="list-unstyled">
                                        @if (count($archives) <= 0)
                                            <p><em>No archives found</em></p>
                                        @endif
                                        @foreach($archives as $date => $archive)
                                            <?php $parts = explode(' ', $date); ?>
                                            <li><a href="{{ route('blog.archive', [$parts[1], $parts[0] ] ) }}" title="">{{$date}}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">Categories</div>
                                <div class="panel-body">
                                    <ul class="list-unstyled">
                                        @if (count($categories) <= 0)
                                            <p><em>No categories found</em></p>
                                        @endif
                                        @foreach($categories as $category)
                                            <li><a href="{{route('blog.category', [$category->name])}}" title="">{{$category->name}}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
        <div class="col-md-2"></div>
    </div>
</div>


<!-- Scripts -->
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
<script src="{{ asset("assets/custom.js") }}" type="text/javascript"></script>
</body>
</html>
