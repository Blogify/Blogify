{!! var_dump($errors->all()) !!}

@if ( Session::has('message') )
    {!! Session::get('message') !!}
@endif

{!! Form::open(['route' => 'admin.login.post']) !!}

    {!! Form::email('email') !!}
    {!! Form::password('password') !!}
    {!! Form::submit() !!}

{!! Form::close() !!}