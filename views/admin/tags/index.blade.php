<?php
$trashed        = ($trashed) ? 1 : 0;
$currentPage    = (Request::has('page')) ? Request::get('page') : '1';
?>
@extends('blogify::admin.layouts.dashboard')
@section('page_heading', trans("blogify::tags.overview.page_title") )
@section('section')
    @if ( session()->get('notify') )
        @include('blogify::admin.snippets.notify')
    @endif
    @if ( session()->has('success') )
        @include('blogify::admin.widgets.alert', ['class'=>'success', 'dismissable'=>true, 'message'=> session()->get('success'), 'icon'=> 'check'])
    @endif

    <p>
        <a href="{{ ($trashed) ? route('admin.tags.index') : route('admin.tags.overview', ['trashed']) }}" title=""> {{ ($trashed) ? trans('blogify::tags.overview.links.active') : trans('blogify::tags.overview.links.trashed') }} </a>
    </p>

@section ('cotable_panel_title', ($trashed) ? trans("blogify::tags.overview.table_head.title_trashed") : trans("blogify::tags.overview.table_head.title_active"))
@section ('cotable_panel_body')
    <table class="table table-bordered sortable">
        <thead>
        <tr>
            <th role="name"><a href="{!! route('admin.api.sort', ['tags', 'name', 'asc', $trashed]).'?page='.$currentPage !!}" title="Order by name" class="sort"> {{ trans("blogify::tags.overview.table_head.name") }} </a></th>
            <th role="created_at"><a href="{!! route('admin.api.sort', ['tags', 'created_at', 'asc', $trashed]).'?page='.$currentPage !!}" title="Order by created at" class="sort"> {{ trans("blogify::tags.overview.table_head.created_at") }} <span class="fa fa-sort-down fa-fw"></span> </a></th>
            <th> {{ trans("blogify::tags.overview.table_head.actions") }} </th>
        </tr>
        </thead>
        <tbody>
        @if ( count($tags) <= 0 )
            <tr>
                <td colspan="7">
                    <em>@lang('blogify::tags.overview.no_results')</em>
                </td>
            </tr>
        @endif
        @foreach ( $tags as $tag )
            <tr>
                <td>{!! $tag->name !!}</td>
                <td>{!! $tag->created_at !!}</td>
                <td>
                    @if(!$trashed)
                        <a href="{{ route('admin.tags.edit', [$tag->id] ) }}"><span class="fa fa-edit fa-fw"></span></a>
                        {!! Form::open( [ 'route' => ['admin.tags.destroy', $tag->id], 'class' => $tag->id . ' form-delete' ] ) !!}

                        {!! Form::hidden('_method', 'delete') !!}
                        <a href="#" title="{{$tag->name}}" class="delete" id="{{$tag->id}}"><span class="fa fa-trash-o fa-fw"></span></a>
                        {!! Form::close() !!}
                    @else
                        <a href="{{route('admin.tags.restore', [$tag->id])}}" title="">Restore</a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection

@include('blogify::admin.widgets.panel', ['header'=>true, 'as'=>'cotable'])

{!! $tags->render() !!}

@stop
