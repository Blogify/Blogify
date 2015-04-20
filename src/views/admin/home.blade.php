@extends('blogify::admin.layouts.dashboard')
@section('page_heading','Dashboard')
@section('section')
    {{var_dump($new_users_since_last_visit)}}
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-users fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"></div>
                            <div>New users!</div>
                        </div>
                    </div>
                </div>
                <a href="{{route('admin.users.index')}}">
                    <div class="panel-footer">
                        <span class="pull-left">View all users</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
    </div>

@stop
