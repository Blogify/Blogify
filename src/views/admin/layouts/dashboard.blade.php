@extends('blogify::admin.layouts.plane')

@section('body')
 <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{ url ('') }}">Blogify</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-envelope fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-messages">
                        <li>
                            <a href="#">
                                <div>
                                    <strong>John Smith</strong>
                                    <span class="pull-right text-muted">
                                        <em>Yesterday</em>
                                    </span>
                                </div>
                                <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <strong>John Smith</strong>
                                    <span class="pull-right text-muted">
                                        <em>Yesterday</em>
                                    </span>
                                </div>
                                <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <strong>John Smith</strong>
                                    <span class="pull-right text-muted">
                                        <em>Yesterday</em>
                                    </span>
                                </div>
                                <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a class="text-center" href="#">
                                <strong>Read All Messages</strong>
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </li>
                    </ul>
                    <!-- /.dropdown-messages -->
                </li>
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-tasks fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-tasks">
                        <li>
                            <a href="#">
                                <div>
                                    <p>
                                        <strong>Task 1</strong>
                                        <span class="pull-right text-muted">40% Complete</span>
                                    </p>
                                   
                                        <div>
                                        @include('blogify::admin.widgets.progress', ['animated'=> true, 'class'=>'success', 'value'=>'40'])
                                            <span class="sr-only">40% Complete (success)</span>
                                        </div>
                                   
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <p>
                                        <strong>Task 2</strong>
                                        <span class="pull-right text-muted">20% Complete</span>
                                    </p>
                                   
                                        <div>
                                        @include('blogify::admin.widgets.progress', ['animated'=> true, 'class'=>'info', 'value'=>'20'])
                                            <span class="sr-only">20% Complete</span>
                                        </div>
                                   
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <p>
                                        <strong>Task 3</strong>
                                        <span class="pull-right text-muted">60% Complete</span>
                                    </p>
                                    
                                        <div>
                                        @include('blogify::admin.widgets.progress', ['animated'=> true, 'class'=>'warning', 'value'=>'60'])
                                            <span class="sr-only">60% Complete (warning)</span>
                                        </div>
                                   
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <p>
                                        <strong>Task 4</strong>
                                        <span class="pull-right text-muted">80% Complete</span>
                                    </p>
                                    
                                        <div>
                                        @include('blogify::admin.widgets.progress', ['animated'=> true,'class'=>'danger', 'value'=>'80'])
                                            <span class="sr-only">80% Complete (danger)</span>
                                        </div>
                                    
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a class="text-center" href="#">
                                <strong>See All Tasks</strong>
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </li>
                    </ul>
                    <!-- /.dropdown-tasks -->
                </li>
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-bell fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-alerts">
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-comment fa-fw"></i> New Comment
                                    <span class="pull-right text-muted small">4 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-twitter fa-fw"></i> 3 New Followers
                                    <span class="pull-right text-muted small">12 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-envelope fa-fw"></i> Message Sent
                                    <span class="pull-right text-muted small">4 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-tasks fa-fw"></i> New Task
                                    <span class="pull-right text-muted small">4 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-upload fa-fw"></i> Server Rebooted
                                    <span class="pull-right text-muted small">4 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a class="text-center" href="#">
                                <strong>See All Alerts</strong>
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </li>
                    </ul>
                    <!-- /.dropdown-alerts -->
                </li>
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="{{route('admin.profile.edit', Auth::user()->hash)}}"><i class="fa fa-user fa-fw"></i> {{ trans("blogify::navigation.profile") }} </a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="{!! route('admin.logout') !!}"><i class="fa fa-sign-out fa-fw"></i> {{ trans("blogify::navigation.logout") }} </a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li>
                            <a href="{{ url ('admin') }}"><i class="fa fa-dashboard fa-fw"></i> {{ trans("blogify::navigation.dashboard.title") }} </a>
                        </li>
                        <li >
                            <a href="#"><i class="fa fa-pencil fa-fw"></i>{{ trans("blogify::navigation.posts.title") }}<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                @if ( Auth::user()->role->name == 'Admin' || Auth::user()->role->name == 'Author' )
                                    <li>
                                        <a href="{{ route ('admin.posts.create') }}"><span class="fa fa-plus fa-fw"></span> {{ trans("blogify::navigation.posts.new") }}</a>
                                    </li>
                                @endif
                                <li>
                                    <a href="{{ route ('admin.posts.index' ) }}"><span class="fa fa-th-list fa-fw"></span> {{ trans("blogify::navigation.posts.overview") }}</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>

                        @if ( Auth::user()->role->name != 'Reviewer' )
                            <li>
                                <a href="{{ route('admin.comments.index') }}"><i class="fa fa-comment fa-fw"></i>{{ trans("blogify::navigation.comments.title") }}</a>
                            </li>
                        @endif

                        @if ( Auth::user()->role->name == 'Admin' )
                            <li >
                                <a href="#"><i class="fa fa-th-large fa-fw"></i>{{ trans("blogify::navigation.categories.title") }}<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="{{ route ('admin.categories.create') }}"><span class="fa fa-plus fa-fw"></span> {{ trans("blogify::navigation.categories.new") }}</a>
                                    </li>
                                    <li>
                                        <a href="{{ route ('admin.categories.index' ) }}"><span class="fa fa-th-list fa-fw"></span> {{ trans("blogify::navigation.categories.overview") }}</a>
                                    </li>
                                </ul>
                                <!-- /.nav-second-level -->
                            </li>
                        @endif

                        @if ( Auth::user()->role->name != 'Reviewer' )
                            <li >
                                <a href="#"><i class="fa fa-tags fa-fw"></i>{{ trans("blogify::navigation.tags.title") }}<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="{{ route ('admin.tags.create') }}"><span class="fa fa-plus fa-fw"></span> {{ trans("blogify::navigation.tags.new") }}</a>
                                    </li>
                                    <li>
                                        <a href="{{ route ('admin.tags.index' ) }}"><span class="fa fa-th-list fa-fw"></span> {{ trans("blogify::navigation.tags.overview") }}</a>
                                    </li>
                                </ul>
                                <!-- /.nav-second-level -->
                            </li>
                        @endif

                        @if ( Auth::user()->role->name == 'Admin' )
                            <li >
                                <a href="#"><i class="fa fa-users fa-fw"></i>{{ trans("blogify::navigation.users.title") }}<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="{{ route ('admin.users.create') }}"><span class="fa fa-plus fa-fw"></span> {{ trans("blogify::navigation.users.new") }}</a>
                                    </li>
                                    <li>
                                        <a href="{{ route ('admin.users.index' ) }}"><span class="fa fa-th-list fa-fw"></span> {{ trans("blogify::navigation.users.overview") }}</a>
                                    </li>
                                </ul>
                                <!-- /.nav-second-level -->
                            </li>
                            <li >
                                <a href="#"><i class="fa fa-database fa-fw"></i>{{ trans("blogify::navigation.database.title") }}</a>
                            </li>
                        @endif
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        <div id="page-wrapper">
			 <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">@yield('page_heading')</h1>
                </div>
                <!-- /.col-lg-12 -->
           </div>
			<div class="row">  
				@yield('section')

            </div>
            <!-- /#page-wrapper -->
        </div>
    </div>
@stop

