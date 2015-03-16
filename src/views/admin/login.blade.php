@extends('blogify.admin.layouts.login')
@section('content')

<div class="center-block v-align grid-row-small">
    <div class="col12 box-login">
        <div id="logo">
            <img src="{!! asset('assets/blogify/img/blogify-logo.png') !!}" alt="Blogify logo" class="img-responsive" />
        </div>
        {!! Form::open( ['route' => 'admin.login.post' ] ) !!}

        <div class="grid-row col12 form-group">
            {!! Form::label('email', 'E-mail adress') !!}<br>
            {!! Form::email('email', null, [ 'placeholder' => 'example@example.be', 'class' => 'form-control' ] ) !!}
        </div>
        <div class="grid-row col12 form-group">
            {!! Form::label('password', 'Password') !!}<br>
            {!! Form::password('password', array( 'placeholder' => 'Password', 'class' => 'form-control' ) ) !!}
        </div>
        <div class="grid-row form-group">
            <div class="col6">
                {!! Form::checkbox('rememberme') !!}
                {!! Form::label('rememberme', 'Remember me') !!}
            </div>
            <div class="col6 text-right">
                {!! Form::submit('Login', [ 'class' => 'btn btn-primary' ] ) !!}
            </div>
        </div>

        {!! Form::close() !!}

    </div>
</div>

@stop