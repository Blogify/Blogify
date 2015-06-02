@extends('blogify.templates.master')
@section('content')
    @if(count($posts) <= 0)
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">No Posts found</div>
                    <div class="panel-body">
                       <p>
                           There are currently no posts found.
                       </p>
                    </div>
                    <div class="panel-footer">

                    </div>
                </div>
            </div>
        </div>
    @endif

    @foreach($posts as $post)
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading"><a href="{{route('blog.show', [$post->slug])}}" class="post-title">{{$post->title}}</a></div>
                    <div class="panel-body">
                        {!! $post->content !!}

                        <div class="post-details">
                            <p>
                                <small><em>Posted in <strong>{{$post->category->name}}</strong></em></small>
                            </p>
                            <p>
                                <small><strong>Tags:</strong></small>
                            </p>
                            <p>
                            <div id="tags">
                                @if( count($post->tag) > 0 )
                                    @foreach ( $post->tag as $tag )
                                        <span class="tag {{$tag->hash}}"><a href="#" class="{{$tag->hash}}" title="Remove tag"><span class="fa fa-times-circle"></span></a> {{ $tag->name }} </span>
                                    @endforeach
                                @else
                                    <em>No tags found</em>
                                @endif
                            </div>
                            </p>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-6 col-xs-12">
                                <?php $number_of_comments = 0; ?>
                                @foreach($post->comment as $comment)
                                    @if($comment->revised == 2)
                                        <?php $number_of_comments++; ?>
                                    @endif
                                @endforeach
                                <small><a href="{{route('blog.show', [$post->slug])}}">{{$number_of_comments}} comments</a></small>
                            </div>
                            <div class="col-md-6 col-xs-12 text-right">
                                <small>Posted on {{$post->publish_date}} by {{$post->user->fullName}}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <div class="row">
        <div class="col-md-12">
            {!! $posts->render() !!}
        </div>
    </div>
@stop