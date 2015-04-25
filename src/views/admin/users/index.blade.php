<?php
    $trashed        = ($trashed) ? 1 : 0;
    $currentPage    = (Request::has('page')) ? Request::get('page') : '1';
?>
@extends('blogify::admin.layouts.dashboard')
@section('page_heading', trans("blogify::users.overview.page_title") )
@section('section')
    @if ( session()->get('notify') )
        @include('blogify::admin.snippets.notify')
    @endif
    @if ( session()->has('success') )
        @include('blogify::admin.widgets.alert', array('class'=>'success', 'dismissable'=>true, 'message'=> session()->get('success'), 'icon'=> 'check'))
    @endif

    <p>
        <a href="{{ ($trashed) ? route('admin.users.index') : route('admin.users.trashed') }}" title=""> {{ ($trashed) ? trans('blogify::users.overview.links.active') : trans('blogify::users.overview.links.trashed') }} </a>
    </p>

@section ('cotable_panel_title', ($trashed) ? trans("blogify::users.overview.table_head.title_trashed") : trans("blogify::users.overview.table_head.title_active"))
@section ('cotable_panel_body')
    <table class="table table-bordered sortable">
        <thead>
            <tr>
                <th role="hash"><a href="{!! route('admin.api.sort', ['users', 'hash', 'asc', $trashed]).'?page='.$currentPage !!}" title="Order by hash" class="sort"> {{ trans("blogify::users.overview.table_head.hash") }} </a></th>
                <th role="name"><a href="{!! route('admin.api.sort', ['users', 'name', 'asc', $trashed]).'?page='.$currentPage !!}" title="Order by name" class="sort"> {{ trans("blogify::users.overview.table_head.name") }} </a></th>
                <th role="firstname"><a href="{!! route('admin.api.sort', ['users', 'firstname', 'asc', $trashed]).'?page='.$currentPage !!}" title="Order by first name" class="sort"> {{ trans("blogify::users.overview.table_head.firstname") }} </a></th>
                <th role="username"><a href="{!! route('admin.api.sort', ['users', 'username', 'asc', $trashed]).'?page='.$currentPage !!}" title="Order by username" class="sort"> {{ trans("blogify::users.overview.table_head.username") }} </a></th>
                <th role="email"><a href="{!! route('admin.api.sort', ['users', 'email', 'asc', $trashed]).'?page='.$currentPage !!}" title="Order by E-mail" class="sort"> {{ trans("blogify::users.overview.table_head.email") }} </a></th>
                <th role="role_id"><a href="{!! route('admin.api.sort', ['users', 'role_id', 'asc', $trashed]).'?page='.$currentPage !!}" title="Order by Role" class="sort"> {{ trans("blogify::users.overview.table_head.role") }} </a></th>
                <th> {{ trans("blogify::users.overview.table_head.actions") }} </th>
            </tr>
        </thead>
        <tbody>
            @if ( count($users) <= 0 )
                <tr>
                    <td colspan="7">
                        <em>@lang('blogify::users.overview.no_results')</em>
                    </td>
                </tr>
            @endif
            @foreach ( $users as $user )
                <tr>
                    <td>{!! $user->hash !!}</td>
                    <td>{!! $user->name !!}</td>
                    <td>{!! $user->firstname !!}</td>
                    <td>{!! $user->username !!}</td>
                    <td>{!! $user->email !!}</td>
                    <td>{!! $user->role_id !!}</td>
                    <td>
                        <a href="{{$user->hash}}/edit"><span class="fa fa-edit fa-fw"></span></a>
                        {!! Form::open( [ 'route' => ['admin.users.destroy', $user->hash], 'class' => $user->hash . ' form-delete' ] ) !!}
                            {!! Form::hidden('_method', 'delete') !!}
                            <a href="#" title="{{$user->firstname . ' ' . $user->name}}" class="delete" id="{{$user->hash}}"><span class="fa fa-trash-o fa-fw"></span></a>
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

@include('blogify::admin.widgets.panel', array('header'=>true, 'as'=>'cotable'))

{!! $users->render() !!}

@stop
