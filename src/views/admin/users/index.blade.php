<?php
    $trashed = ($trashed) ? 1 : 0;
?>
@extends('blogify::admin.layouts.dashboard')
@section('page_heading','Users')
@section('section')

@section ('cotable_panel_title','Active users')
@section ('cotable_panel_body')
    <table class="table table-bordered sortable">
        <thead>
            <tr>
                <th role="hash"><a href="{!! route('admin.api.sort', ['users', 'hash', 'asc', $trashed]) !!}" title="Order by hash" class="sort">Hash</a></th>
                <th role="name"><a href="{!! route('admin.api.sort', ['users', 'name', 'asc', $trashed]) !!}" title="Order by name" class="sort">Name</a></th>
                <th role="firstname"><a href="{!! route('admin.api.sort', ['users', 'firstname', 'asc', $trashed]) !!}" title="Order by first name" class="sort">First name</a></th>
                <th role="username"><a href="{!! route('admin.api.sort', ['users', 'username', 'asc', $trashed]) !!}" title="Order by username" class="sort">Username</a></th>
                <th role="email"><a href="{!! route('admin.api.sort', ['users', 'email', 'asc', $trashed]) !!}" title="Order by E-mail" class="sort">E-mail</a></th>
                <th role="role_id"><a href="{!! route('admin.api.sort', ['users', 'role_id', 'asc', $trashed]) !!}" title="Order by Role" class="sort">Role</a></th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ( $users as $user )
                <tr>
                    <td>{!! $user->hash !!}</td>
                    <td>{!! $user->name !!}</td>
                    <td>{!! $user->firstname !!}</td>
                    <td>{!! $user->username !!}</td>
                    <td>{!! $user->email !!}</td>
                    <td>{!! $user->role_id !!}</td>
                    <td>
                        <a href="#"><span class="fa fa-edit fa-fw"></span></a>
                        <a href="#"><span class="fa fa-trash-o fa-fw"></span></a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
@include('blogify::admin.widgets.panel', array('header'=>true, 'as'=>'cotable'))

@stop
