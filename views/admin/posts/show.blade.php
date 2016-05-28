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

    @foreach($post->comment as $comment)
        @if($comment->revised == 2)
            <div class="media">
                <div class="media-left">
                    <a href="#">
                        <img class="media-object" src="{{URL::asset($comment->user->profilepicture)}}" alt="..." width="64px" height="64px">
                    </a>
                </div>
                <div class="media-body">
                    <p>
                        {!!nl2br($comment->content)!!}
                    </p>
                    <span class="media-heading"><em>posted  {{\Carbon\Carbon::createFromTimeStamp(strtotime($comment->created_at))->diffForHumans()}} by {{$comment->user->fullName}}</em></span>
                </div>
            </div>
        @endif
    @endforeach

    <hr>
@stop
