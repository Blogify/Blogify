@extends('blogify.templates.master')
@section('content')
    <?php $data = session()->get('wrong_password') ?>
    @if(isset($data))
        <div id="notify" class="fixed-to-top">
            @include('blogify::admin.widgets.alert', ['class'=>'danger', 'dismissable'=>true, 'message'=> $data, 'icon'=> 'check'])
        </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Provide a valid password to view this post</div>
                <div class="panel-body">

                    <form class="form-horizontal" role="form" method="POST" action="{{ route('blog.confirmPass', [$slug]) }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="hash" value="{{$slug}}">
                        <div class="form-group">
                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password" placeholder="Password">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-0">
                                <button type="submit" class="btn btn-primary">View post</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop