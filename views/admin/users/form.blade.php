<?php
$editable = (isset($user)) ? "disabled" : null;
?>
@extends('blogify::admin.layouts.dashboard')
@section('page_heading', isset($user) ? trans("blogify::users.form.page_title_edit")  : trans("blogify::users.form.page_title_create") )
@section('section')

@include('blogify::admin.snippets.validation-errors')

@if ( isset($user) )
    {!! Form::open( [ 'route' => ['admin.users.update', $user->hash] ] ) !!}
    {!! Form::hidden('_method', 'put') !!}
@else
    {!! Form::open( [ 'route' => 'admin.users.store' ] ) !!}
@endif
    <div class="row form-group {{ $errors->has('name') ? 'has-error' : '' }}">
        <div class="col-sm-2">
            {!! Form::label('name', trans("blogify::users.form.name.label") ) !!}
        </div>
        <div class="col-sm-10">
            {!! Form::text('name', isset($user) ? $user->name : '', ['class' => 'form-control form-small', $editable ]) !!}
        </div>
    </div>

    <div class="row form-group {{ $errors->has('firstname') ? 'has-error' : '' }}">
        <div class="col-sm-2">
            {!! Form::label('firstname', trans("blogify::users.form.firstname.label") ) !!}
        </div>
        <div class="col-sm-10">
            {!! Form::text('firstname', isset($user) ? $user->firstname : '', ['class' => 'form-control form-small', $editable]) !!}
        </div>
    </div>

    <div class="row form-group {{ $errors->has('email') ? 'has-error' : '' }}">
        <div class="col-sm-2">
            {!! Form::label('email', trans("blogify::users.form.email.label") ) !!}
        </div>
        <div class="col-sm-10">
            {!! Form::text('email', isset($user) ? $user->email : '' , ['class' => 'form-control form-small', $editable]) !!}
        </div>
    </div>

    <div class="row form-group {{ $errors->has('role') ? 'has-error' : '' }}">
        <div class="col-sm-2">
            {!! Form::label('role', trans("blogify::users.form.role.label") ) !!}
        </div>
        <div class="col-sm-10">
            <select name="role" class="form-control form-small">
                @foreach ($roles as $role)
                    <option value="{!! $role->hash !!}" {{ ( isset($user) && $role->id == $user->role_id ) ? 'selected' : '' }}>{!! $role->name !!}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-2">
            {!! Form::submit(trans("blogify::users.form.submit_button.value"), ['class'=>'btn btn-success']) !!}
        </div>
    </div>

{!! Form::close() !!}

@stop