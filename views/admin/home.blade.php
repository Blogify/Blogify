@extends('blogify::admin.layouts.dashboard')
@section('page_heading','Dashboard')
@section('section')

        <div class="row">

        @if ( isset($published_posts) )
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-green">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-check fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge">{{$published_posts}}</div>
                                <div>@lang('blogify::dashboard.published_posts')</div>
                            </div>
                        </div>
                    </div>
                    <a href="{{route('admin.posts.index')}}">
                        <div class="panel-footer">
                            <span class="pull-left">@lang('blogify::dashboard.view_all_posts')</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
        @endif

        @if ( isset($pending_review_posts) )
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-yellow">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-eye fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge">{{$pending_review_posts}}</div>
                                <div>@lang('blogify::dashboard.posts_need_review')</div>
                            </div>
                        </div>
                    </div>
                    <a href="{{route('admin.posts.index')}}">
                        <div class="panel-footer">
                            <span class="pull-left">@lang('blogify::dashboard.view_all_posts')</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
        @endif

        @if ( isset($pending_comments) )
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-red">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-comments fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge">{{$pending_comments}}</div>
                                <div>@lang('blogify::dashboard.pending_comments')</div>
                            </div>
                        </div>
                    </div>
                    <a href="{{route('admin.comments.index')}}">
                        <div class="panel-footer">
                            <span class="pull-left">@lang('blogify::dashboard.view_all_comments')</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
        @endif

    </div>




@stop
