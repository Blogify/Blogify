<!doctype html>
<html>
    <head>
        @include('blogify.admin.includes.head')
    </head>
    <body>
        <div class="outer-container">
            @include('blogify.admin.includes.header')
            <div class="grid-row">
                <div class="col2">
                    <ul class="nav nav-pills nav-stacked">
                        <li role="presentation" class="active"><a href="#">Home</a></li>
                        <li role="presentation"><a href="#">Profile</a></li>
                        <li role="presentation"><a href="#">Messages</a></li>
                        <li role="presentation"><a href="#">ultra long link in nav</a></li>
                    </ul>
                </div>
                <div class="col10 shift-left">
                    @yield('content')
                </div>
            </div>
        </div>

        @include('blogify.admin.includes.footer')
    </body>
</html>