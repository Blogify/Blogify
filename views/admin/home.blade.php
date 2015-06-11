@extends('blogify::admin.layouts.dashboard')
@section('page_heading','Dashboard')
@section('section')

    @if ( isset($new_users_since_last_visit) )
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-users fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge">{{$new_users_since_last_visit}}</div>
                                <div>@lang('blogify::dashboard.new_users')</div>
                            </div>
                        </div>
                    </div>
                    <a href="{{route('admin.users.index')}}">
                        <div class="panel-footer">
                            <span class="pull-left">@lang('blogify::dashboard.view_all_users')</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
        @endif

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

    @if ( isset($activity) )
    <div class="row">
        <div class="col-md-12">
            @section ('activityfeed_panel_title', trans('blogify::dashboard.activity_feed.title'))
            @section ('activityfeed_panel_body')
                <div class="list-group">
                    @foreach ( $activity as $item )
                        @if( $item->table == 'posts' )
                            <?php
                                $icon = 'fa-pencil';
                                $post = jorenvanhocht\Blogify\Models\Post::withTrashed()->find($item->row);
                            ?>
                                <a href="{{route('admin.posts.edit', [$post->hash])}}" class="list-group-item">
                                    <i class="fa {{$icon}} fa-fw"></i> @lang('blogify::dashboard.activity_feed.post', ['title' => $post->title, 'action' => $item->crud_action])
                                    <span class="pull-right text-muted small"><em>{{App\User::find($item->user_id)->fullName}} - {{$item->created_at}}</em>
                                        </span>
                                </a>
                        @endif
                        @if ( $item->table == 'comments' )
                                <?php
                                    $icon = 'fa-comments';
                                    $author = jorenvanhocht\Blogify\Models\Comment::withTrashed()->find($item->row)->user->fullName;
                                ?>
                                <a href="{{route('admin.comments.index', [$item->crud_action])}}" class="list-group-item">
                                    <i class="fa {{$icon}} fa-fw"></i> @lang('blogify::dashboard.activity_feed.comment', ['author' => $author, 'action' => $item->crud_action])
                                    <span class="pull-right text-muted small"><em>{{App\User::find($item->user_id)->fullName}} - {{$item->created_at}}</em>
                                    </span>
                                </a>
                        @endif
                        @if ( $item->table == 'categories' )
                                <?php
                                    $icon = 'fa-th-large';
                                    $category = jorenvanhocht\Blogify\Models\Category::withTrashed()->find($item->row);
                                ?>
                                    <a href="{{route('admin.categories.edit', [$category->hash])}}" class="list-group-item">
                                        <i class="fa {{$icon}} fa-fw"></i> @lang('blogify::dashboard.activity_feed.category', ['name' => $category->name, 'action' => $item->crud_action])
                                        <span class="pull-right text-muted small"><em>{{App\User::find($item->user_id)->fullName}} - {{$item->created_at}}</em>
                                        </span>
                                    </a>
                        @endif
                        @if ( $item->table == 'tags' )
                                <?php
                                    $icon = 'fa-tags';
                                    $tag = jorenvanhocht\Blogify\Models\Tag::withTrashed()->find($item->row)
                                ?>
                                    <a href="{{route('admin.tags.edit', [$tag->hash])}}" class="list-group-item">
                                        <i class="fa {{$icon}} fa-fw"></i> @lang('blogify::dashboard.activity_feed.tag', ['name' => $tag->name, 'action' => $item->crud_action])
                                        <span class="pull-right text-muted small"><em> {{App\User::find($item->user_id)->fullName}} - {{$item->created_at}}</em>
                                        </span>
                                    </a>
                        @endif
                    @endforeach
                </div>
                <!-- /.list-group -->

                <!-- /.panel-body -->
            @endsection
            @include('blogify::admin.widgets.panel', ['header'=>true, 'as'=>'activityfeed'])
        </div>
    </div>
    @endif

@stop
