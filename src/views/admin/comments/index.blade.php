<?php
$currentPage    = (Request::has('page')) ? Request::get('page') : '1';
?>
@extends('blogify::admin.layouts.dashboard')
@section('page_heading', trans("blogify::comments.overview.page_title") )
@section('section')
    @if ( session()->get('notify') )
        @include('blogify::admin.snippets.notify')
    @endif
    @if ( session()->has('success') )
        @include('blogify::admin.widgets.alert', array('class'=>'success', 'dismissable'=>true, 'message'=> session()->get('success'), 'icon'=> 'check'))
    @endif

    <p>
        @if ( Request::segment(3) == 'pending' || Request::segment(3) == null )
            {{ trans('blogify::comments.overview.links.pending') }} |
            <a href="{{ route('admin.comments.index', ['approved']) }}" title="">{{ trans('blogify::comments.overview.links.approved') }}</a> |
            <a href="{{ route('admin.comments.index', ['disapproved']) }}" title="">{{ trans('blogify::comments.overview.links.disapproved') }}</a>
        @endif

        @if ( Request::segment(3) == 'approved' )
                <a href="{{ route('admin.comments.index', ['pending']) }}" title="">{{ trans('blogify::comments.overview.links.pending') }}</a> |
                {{ trans('blogify::comments.overview.links.approved') }} |
                <a href="{{ route('admin.comments.index', ['disapproved']) }}" title="">{{ trans('blogify::comments.overview.links.disapproved') }}</a>
        @endif

        @if ( Request::segment(3) == 'disapproved' )
                <a href="{{ route('admin.comments.index', ['pending']) }}" title="">{{ trans('blogify::comments.overview.links.pending') }}</a> |
                <a href="{{ route('admin.comments.index', ['approved']) }}" title="">{{ trans('blogify::comments.overview.links.approved') }}</a> |
                {{ trans('blogify::comments.overview.links.disapproved') }}
        @endif
    </p>

@section ('cotable_panel_title', trans("blogify::comments.overview.page_title") )
@section ('cotable_panel_body')
    <table class="table table-bordered sortable">
        <thead>
        <tr>
            <th>@lang("blogify::comments.overview.table_head.hash")</th>
            <th>@lang("blogify::comments.overview.table_head.author")</th>
            <th>@lang("blogify::comments.overview.table_head.comment")</th>
            <th>@lang("blogify::comments.overview.table_head.post")</th>
            <th>@lang("blogify::tags.overview.table_head.created_at")</th>
            <th>@lang("blogify::tags.overview.table_head.actions")</th>
        </tr>
        </thead>
        <tbody>
        @if ( count($comments) <= 0 )
            <tr>
                <td colspan="7">
                    <em>@lang('blogify::comments.overview.no_results')</em>
                </td>
            </tr>
        @endif
        @foreach ( $comments as $comment )
            <tr>
                <td>{!! $comment->hash !!}</td>
                <td>{!! $comment->user->fullName !!}</td>
                <td>{!! nl2br($comment->content) !!}</td>
                <td><a href="{{route('admin.posts.show')}}" title="{{ $comment->post->title }}">{!! $comment->post->title !!}</a></td>
                <td>{!! $comment->created_at !!}</td>
                <td>
                    <a href="{{ route('admin.comments.changeStatus', [$comment->hash, 'approved'] ) }}" title="{{ trans('blogify::comments.overview.actions.approve') }}"><span class="fa fa-check fa-fw"></span></a>
                    <a href="{{ route('admin.comments.changeStatus', [$comment->hash, 'disapproved'] ) }}" title="{{ trans('blogify::comments.overview.actions.disapprove') }}"><span class="fa fa-times fa-fw"></span></a>
                    <a href="{{ route('admin.comments.changeStatus', [$comment->hash, 'pending'] ) }}" title="{{ trans('blogify::comments.overview.actions.pending') }}"><span class="fa fa-question fa-fw"></span></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection

@include('blogify::admin.widgets.panel', array('header'=>true, 'as'=>'cotable'))

{!! $comments->render() !!}

@stop
