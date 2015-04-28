<?php
$trashed        = ($trashed) ? 1 : 0;
$currentPage    = (Request::has('page')) ? Request::get('page') : '1';
?>
@extends('blogify::admin.layouts.dashboard')
@section('page_heading', trans("blogify::posts.overview.page_title") )
@section('section')
    @if ( session()->get('notify') )
        @include('blogify::admin.snippets.notify')
    @endif
    @if ( session()->has('success') )
        @include('blogify::admin.widgets.alert', array('class'=>'success', 'dismissable'=>true, 'message'=> session()->get('success'), 'icon'=> 'check'))
    @endif

    <p>
        <a href="{{ ($trashed) ? route('admin.posts.index') : route('admin.posts.trashed') }}" title=""> {{ ($trashed) ? trans('blogify::posts.overview.links.active') : trans('blogify::posts.overview.links.trashed') }} </a>
    </p>

@section ('cotable_panel_title', ($trashed) ? trans("blogify::posts.overview.table_head.title_trashed") : trans("blogify::posts.overview.table_head.title_active"))
@section ('cotable_panel_body')
    <table class="table table-bordered sortable">
        <thead>
        <tr>
            <th role="hash"><a href="{!! route('admin.api.sort', ['posts', 'hash', 'asc', $trashed]).'?page='.$currentPage !!}" title="Order by hash" class="sort"> {{ trans("blogify::posts.overview.table_head.hash") }} </a></th>
            <th role="title"><a href="{!! route('admin.api.sort', ['posts', 'title', 'asc', $trashed]).'?page='.$currentPage !!}" title="Order by name" class="sort"> {{ trans("blogify::posts.overview.table_head.title") }} </a></th>
            <th role="slug"><a href="{!! route('admin.api.sort', ['posts', 'slug', 'asc', $trashed]).'?page='.$currentPage !!}" title="Order by first name" class="sort"> {{ trans("blogify::posts.overview.table_head.slug") }} </a></th>
            <th> {{ trans("blogify::posts.overview.table_head.actions") }} </th>
        </tr>
        </thead>
        <tbody>
        @if ( count($posts) <= 0 )
            <tr>
                <td colspan="7">
                    <em>@lang('blogify::posts.overview.no_results')</em>
                </td>
            </tr>
        @endif
        @foreach ( $posts as $post )
            <tr>
                <td>{!! $post->hash !!}</td>
                <td>{!! $post->title !!}</td>
                <td>{!! $post->slug !!}</td>
                <td>
                    <a href="{{ route('admin.posts.edit', [$post->hash] ) }}"><span class="fa fa-edit fa-fw"></span></a>
                    {!! Form::open( [ 'route' => ['admin.posts.destroy', $post->hash], 'class' => $post->hash . ' form-delete' ] ) !!}
                    {!! Form::hidden('_method', 'delete') !!}
                    <a href="#" title="{{$post->title}}" class="delete" id="{{$post->hash}}"><span class="fa fa-trash-o fa-fw"></span></a>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection

@include('blogify::admin.widgets.panel', array('header'=>true, 'as'=>'cotable'))

{!! $posts->render() !!}

@stop
