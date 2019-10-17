<?php
$currentPage = (Request::has('page')) ? Request::get('page') : '1';
?>
@extends('blogify::admin.layouts.dashboard')
@section('page_heading', trans("blogify::comments.overview.page_title") )
@section('section')
    @if ( session()->get('notify') )
        @include('blogify::admin.snippets.notify')
    @endif
    @if ( session()->has('success') )
        @include('blogify::admin.widgets.alert', ['class'=>'success', 'dismissable'=>true, 'message'=> session()->get('success'), 'icon'=> 'check'])
    @endif

    <ul class="nav nav-tabs">
        <li role="presentation" class="{{ (Request::segment(3) == 'pending' || Request::segment(3) == null) ? 'active' : '' }}" ><a href="{{ route('admin.comments.index', ['pending']) }}">{{ trans('blogify::comments.overview.links.pending') }}</a></li>
        <li role="presentation" class="{{ ( Request::segment(3) == 'approved' ) ? 'active' : '' }} "><a href="{{ route('admin.comments.index', ['approved']) }}">{{ trans('blogify::comments.overview.links.approved') }}</a></li>
        <li role="presentation" class="{{ ( Request::segment(3) == 'disapproved' ) ? 'active' : '' }} "><a href="{{ route('admin.comments.index', ['disapproved']) }}">{{ trans('blogify::comments.overview.links.disapproved') }}</a></li>
    </ul>

@section ('cotable_panel_title', trans("blogify::comments.overview.page_title") )
@section ('cotable_panel_body')
    <table id="table-comments" class="table table-bordered sortable">
        <thead>
        <tr>
            <th>ID</th>
            <th>@lang("blogify::comments.overview.table_head.author")</th>
            <th>Role</th>
            <th>@lang("blogify::comments.overview.table_head.comment")</th>
            <th>Post</th>
            <th>In response to</th>
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
                <td>{!! $comment->user->id !!}</td>
                <td>{!! $comment->user->fullName !!}</td>
                <td>
                    @if($comment->user->isCandidate)
                        Candidate
                    @elseif($comment->user->isCompany)
                        Company
                    @elseif($comment->user->isAdmin)
                        Admin
                    @else
                        Publisher
                    @endif
                </td>
                <td>{!! nl2br($comment->content) !!}</td>
                <td><a href="{{route('admin.posts.show', [$comment->post->hash])}}" title="{{ $comment->post->title }}">{!! $comment->post->title !!}</a></td>
                <td>
                    @if(is_null($comment->parent_id))
                        Post
                    @else
                        {{$comment->parent_id}}
                    @endif
                </td>
                <td>{!! $comment->created_at !!}</td>
                <td>
                    <a href="{{ route('admin.comments.changeStatus', [$comment->id, 'approved'] ) }}" title="{{ trans('blogify::comments.overview.actions.approve') }}"><span class="fa fa-check fa-fw"></span></a>
                    <a href="{{ route('admin.comments.changeStatus', [$comment->id, 'disapproved'] ) }}" title="{{ trans('blogify::comments.overview.actions.disapprove') }}"><span class="fa fa-times fa-fw"></span></a>
                    <a href="{{ route('admin.comments.changeStatus', [$comment->id, 'pending'] ) }}" title="{{ trans('blogify::comments.overview.actions.pending') }}"><span class="fa fa-question fa-fw"></span></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection

@include('blogify::admin.widgets.panel', ['header'=>true, 'as'=>'cotable'])

{!! $comments->render() !!}

@stop