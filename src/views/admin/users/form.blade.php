@extends('blogify::admin.layouts.dashboard')
@section('page_heading','Create new user')
@section('section')

{!! Form::open( [ 'route' => 'admin.users.store' ] ) !!}
    <div class="row form-group">
        <div class="col-sm-2">
            {!! Form::label('name', 'Name:') !!}
        </div>
        <div class="col-sm-10">
            {!! Form::text('name', '', ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-2">
            {!! Form::label('firstname', 'First name:') !!}
        </div>
        <div class="col-sm-10">
            {!! Form::text('firstname', '', ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-2">
            {!! Form::label('email', 'E-mail:') !!}
        </div>
        <div class="col-sm-10">
            {!! Form::text('email', '', ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-2">
            {!! Form::label('role', 'Role:') !!}
        </div>
        <div class="col-sm-10">
            <select name="role" class="form-control">
                @foreach ($roles as $role)
                    <option value="{!! $role->hash !!}">{!! $role->name !!}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-2">
            {!! Form::submit('Create user', ['class'=>'btn btn-success']) !!}
        </div>
    </div>

{!! Form::close() !!}

@stop