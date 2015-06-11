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
        @include('blogify::admin.widgets.alert', ['class'=>'success', 'dismissable'=>true, 'message'=> session()->get('success'), 'icon'=> 'check'])
    @endif

    <p>
        <a href="{{ ($trashed) ? route('admin.posts.index') : route('admin.posts.overview', ['trashed']) }}" title=""> {{ ($trashed) ? trans('blogify::posts.overview.links.active') : trans('blogify::posts.overview.links.trashed') }} </a>
    </p>

@section ('cotable_panel_title', ($trashed) ? trans("blogify::posts.overview.table_head.title_trashed") : trans("blogify::posts.overview.table_head.title_active"))
@section ('cotable_panel_body')
    <table class="table table-bordered sortable">
        <thead>
        <tr>
            <th role="title"><a href="{!! route('admin.api.sort', ['posts', 'title', 'asc', $trashed]).'?page='.$currentPage !!}" title="Order by title" class="sort"> {{ trans("blogify::posts.overview.table_head.title") }} </a></th>
            <th role="slug"><a href="{!! route('admin.api.sort', ['posts', 'slug', 'asc', $trashed]).'?page='.$currentPage !!}" title="Order by slug" class="sort"> {{ trans("blogify::posts.overview.table_head.slug") }} </a></th>
            <th role="status_id"><a href="{!! route('admin.api.sort', ['posts', 'status_id', 'asc', $trashed]).'?page='.$currentPage !!}" title="Order by status" class="sort"> {{ trans("blogify::posts.overview.table_head.status") }} </a></th>
            <th role="publish_date"><a href="{!! route('admin.api.sort', ['posts', 'publish_date', 'asc', $trashed]).'?page='.$currentPage !!}" title="Order by publish date" class="sort"> {{ trans("blogify::posts.overview.table_head.publish_date") }} <span class="fa fa-sort-down fa-fw"></span> </a></th>
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
                <td>{!! $post->title !!}</td>
                <td>{!! $post->slug !!}</td>
                <td>{!! $post->status->name !!}</td>
                <td>{!! $post->publish_date !!}</td>
                <td>
                    @if(!$trashed)
                        <a href="{{ route('admin.posts.edit', [$post->hash] ) }}"><span class="fa fa-edit fa-fw"></span></a>
                        <a href="{{ route('admin.posts.show', [$post->hash] ) }}"><span class="fa fa-eye fa-fw"></span></a>
                        {!! Form::open( [ 'route' => ['admin.posts.destroy', $post->hash], 'class' => $post->hash . ' form-delete' ] ) !!}

                        {!! Form::hidden('_method', 'delete') !!}
                        <a href="#" title="{{$post->name}}" class="delete" id="{{$post->hash}}"><span class="fa fa-trash-o fa-fw"></span></a>
                        {!! Form::close() !!}
                    @else
                        <a href="{{route('admin.posts.restore', [$post->hash])}}" title="">Restore</a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection

@include('blogify::admin.widgets.panel', ['header'=>true, 'as'=>'cotable'])

{!! $posts->render() !!}

@stop
