@extends('blogify::admin.layouts.dashboard')
@section('page_heading', $post->title )
@section('section')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            {!!($post->content)!!}
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <h3>Comments</h3>
        </div>
    </div>

    @foreach ($post->comment as $comment)
        <div class="row">
            <div class="col-lg-2 col-md-2 author">
                <img src="{{asset($comment->user->profilepicture)}}" alt="" class="profile-picture-small" />
                <p>
                    <span class="name">{{$comment->user->fullName}}</span>
                </p>

            </div>
            <div class="col-lg-10 col-md-10">
                <p>{{nl2br($comment->content)}}</p>
            </div>

        </div>
    @endforeach
@stop
@stop
