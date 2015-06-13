@extends('blogify::admin.layouts.dashboard')
@section('page_heading', trans('blogify::profiles.form.page_title') )
@section('section')

@include('blogify::admin.snippets.validation-errors')

    {!! Form::open( [ 'route' => ['admin.profile.update', $user->hash], 'files' => true ] ) !!}
    {!! Form::hidden('_method', 'put') !!}
    {!! Form::hidden('hash', $user->hash) !!}
    <div class="row form-group {{ $errors->has('name') ? 'has-error' : '' }}">
        <div class="col-sm-2">
            {!! Form::label('name', trans("blogify::profiles.form.name.label") ) !!}
        </div>
        <div class="col-sm-10">
            {!! Form::text('name', isset($user) ? $user->lastname : '', ['class' => 'form-control form-small' ]) !!}
        </div>
    </div>

    <div class="row form-group {{ $errors->has('firstname') ? 'has-error' : '' }}">
        <div class="col-sm-2">
            {!! Form::label('firstname', trans("blogify::profiles.form.firstname.label") ) !!}
        </div>
        <div class="col-sm-10">
            {!! Form::text('firstname', isset($user) ? $user->firstname : '', ['class' => 'form-control form-small']) !!}
        </div>
    </div>

    <div class="row form-group {{ $errors->has('username') ? 'has-error' : '' }}">
        <div class="col-sm-2">
            {!! Form::label('username', trans("blogify::profiles.form.username.label") ) !!}
        </div>
        <div class="col-sm-10">
            {!! Form::text('username', isset($user) ? $user->username : '', ['class' => 'form-control form-small']) !!}
        </div>
    </div>

    <div class="row form-group {{ $errors->has('email') ? 'has-error' : '' }}">
        <div class="col-sm-2">
            {!! Form::label('email', trans("blogify::profiles.form.email.label") ) !!}
        </div>
        <div class="col-sm-10">
            {!! Form::text('email', isset($user) ? $user->email : '' , ['class' => 'form-control form-small']) !!}
        </div>
    </div>

    <div class="row form-group {{ $errors->has('password') ? 'has-error' : '' }}">
        <div class="col-sm-2">
            {!! Form::label('password', trans("blogify::profiles.form.password.label") ) !!}
        </div>
        <div class="col-sm-10">
            {!! Form::password('password', ['class' => 'form-control form-small']) !!}
        </div>
    </div>

    <div class="row form-group {{ $errors->has('newpassword') ? 'has-error' : '' }}">
        <div class="col-sm-2">
            {!! Form::label('newpassword', trans("blogify::profiles.form.newpassword.label") ) !!}
        </div>
        <div class="col-sm-10">
            {!! Form::password('newpassword', ['class' => 'form-control form-small']) !!}
        </div>
    </div>

    <div class="row form-group {{ $errors->has('newpasswordconfirm') ? 'has-error' : '' }}">
        <div class="col-sm-2">
            {!! Form::label('newpasswordconfirm', trans("blogify::profiles.form.newpasswordconfirm.label") ) !!}
        </div>
        <div class="col-sm-10">
            {!! Form::password('newpasswordconfirm', ['class' => 'form-control form-small']) !!}
        </div>
    </div>

    <div class="row form-group {{ $errors->has('profilepicture') ? 'has-error' : '' }}">
        <div class="col-sm-2">
            {!! Form::label('profilepicture', trans("blogify::profiles.form.profilepicture.label") ) !!}
        </div>
        <div class="col-sm-10">
            @if($user->profilepicture != null)
                <img src="{{URL::asset($user->profilepicture)}}" class="img-rounded">
            @else
                <img src="{{URL::asset('assets/blogify/img/profile-icon.png')}}" class="img-rounded">
            @endif
            {!! Form::file('profilepicture','', ['class' => 'form-control form-small']) !!}
        </div>
    </div>

    <div class="row">
        <div class="col-sm-2">
            {!! Form::submit(trans("blogify::profiles.form.submit_button.value"), ['class'=>'btn btn-success']) !!}
        </div>
    </div>

{!! Form::close() !!}

@stop