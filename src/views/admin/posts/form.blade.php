@extends('blogify::admin.layouts.dashboard')
@section('page_heading',trans("blogify::posts.form.page.title.create"))
@section('section')
    <div class="row">
        <div class="col-lg-8 col-md-12">
            <div class="row">
                <div class="col-lg-12 col-md-12 form-group">
                    {!! Form::text('title', '', [ 'class' => 'form-control', 'id' => 'title', 'placeholder' => trans("blogify::posts.form.title.placeholder") ] ) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 form-group">
                    {!! Form::text('slug', '', [ 'class' => 'form-control', 'id' => 'slug', 'placeholder' => trans("blogify::posts.form.slug.placeholder") ] ) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <textarea id="post"></textarea>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12">

            <!-- publish box -->
            <div class="panel-group" id="accordion">
                <div class="panel panel-{{{ isset($class) ? $class : 'default' }}}">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse"
                               href="#collapsePublish">
                                {{ trans("blogify::posts.form.publish.title") }}
                            </a>
                        </h4>
                    </div>
                    <div id="collapsePublish" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-4">
                                    {!! Form::label('status', trans("blogify::posts.form.publish.status.label") ) !!}
                                </div>
                                <div class="col-sm-8">
                                    <select name="role" class="form-control form-small">
                                        @foreach ( $statuses as $status )
                                            <option value="{{$status->hash}}">{{$status->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    {!! Form::label('visibility', trans("blogify::posts.form.publish.visibility.label") ) !!}
                                </div>
                                <div class="col-sm-8">
                                    <select name="visibility" class="form-control form-small">
                                        @foreach ( $visibility as $item )
                                            <option value="{{$item->hash}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    {!! Form::label('date', trans("blogify::posts.form.publish.publish_date.label") ) !!}
                                </div>
                                <div class="col-sm-8">
                                    {!! Form::text('datetime','', [ 'data-field' => 'datetime', 'class' => 'form-control', 'readonly' ] ) !!}
                                    <div id="dtBox"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    {!! Form::submit( trans("blogify::posts.form.publish.save_button.value"), [ 'class' => 'btn btn-success' ] ) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end publish box -->

            <!-- categories box -->
            <div class="panel-group" id="accordion">
                <div class="panel panel-{{{ isset($class) ? $class : 'default' }}}">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse"
                               href="#collapseRevieuwer">
                                {{ trans("blogify::posts.form.reviewer.title") }}
                            </a>
                        </h4>
                    </div>
                    <div id="collapseRevieuwer" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-12 form-group">
                                    <select class="form-control">
                                        @foreach ( $reviewers as $reviewer )
                                            <option value="{{$reviewer->hash}}">{{$reviewer->fullName}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end categories box -->

            <!-- categories box -->
            <div class="panel-group" id="accordion">
                <div class="panel panel-{{{ isset($class) ? $class : 'default' }}}">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse"
                               href="#collapseCategory">
                                {{ trans("blogify::posts.form.category.title") }}
                            </a>
                        </h4>
                    </div>
                    <div id="collapseCategory" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-12 form-group input-group">
                                    {!! Form::text('newCategory','', [ 'class' => 'form-control', 'placeholder' => trans("blogify::posts.form.category.placeholder") ] ) !!}
                                    <span class="input-group-btn">
                                    <button type="button" class="btn btn-success"><i class="fa fa-plus"></i></button>
                                    </span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    @if ( count($categories) <= 0 )
                                        <span id="helpBlock" class="help-block">{{ trans("blogify::posts.form.category.no_results") }}</span>
                                    @endif

                                    @foreach ( $categories as $category )
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <label for="{{$category->name}}">
                                                    {!!Form::radio($category->name, $category->hash, ['id' => '$category->name'])!!}
                                                    {{$category->name}}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end categories box -->

            <!-- tags box -->
            <div class="panel-group" id="accordion">
                <div class="panel panel-{{{ isset($class) ? $class : 'default' }}}">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse"
                               href="#collapseTags">
                                {{ trans("blogify::posts.form.tags.title") }}
                            </a>
                        </h4>
                    </div>
                    <div id="collapseTags" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-12 form-group input-group">
                                    {!! Form::text('tags','', [ 'class' => 'form-control', 'placeholder' => trans("blogify::posts.form.tags.placeholder") ] ) !!}
                                    <span class="input-group-btn">
                                    <button type="button" class="btn btn-success"><i class="fa fa-plus"></i></button>
                                    </span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <span id="helpBlock" class="help-block">{{ trans("blogify::posts.form.tags.help_block") }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end tags box -->


        </div>
    </div>

@stop
@section('scripts')
    <link rel="stylesheet" type="text/css" href="{{asset('datetimepicker/DateTimePicker.css')}}" />
    <script type="text/javascript" src="{{asset('datetimepicker/DateTimePicker.js')}}"></script>
    <script src="{{asset('ckeditor/ckeditor.js')}}"></script>
    <!--[if lt IE 9]>
    <link rel="stylesheet" type="text/css" href="{{asset('datetimepicker/DateTimePicker-ltie9.css')}}" />
    <script type="text/javascript" src="{{asset('datetimepicker/DateTimePicker-ltie9.js')}}"></script>
    <![endif]-->
@endsection