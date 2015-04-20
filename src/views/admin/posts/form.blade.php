@extends('blogify::admin.layouts.dashboard')
@section('page_heading','Add new post')
@section('section')
    <div class="row">
        <div class="col-lg-8 col-md-12">
            <div class="row">
                <div class="col-lg-12 col-md-12 form-group">
                    {!! Form::text('title', '', [ 'class' => 'form-control', 'placeholder' => 'Enter title here' ] ) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 form-group">
                    {!! Form::text('slug', '', [ 'class' => 'form-control', 'placeholder' => 'Enter slug here' ] ) !!}
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
                                Publish
                            </a>
                        </h4>
                    </div>
                    <div id="collapsePublish" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-4">
                                    {!! Form::label('status', 'Status:') !!}
                                </div>
                                <div class="col-sm-8">
                                    <select name="role" class="form-control form-small">
                                            <option value="">Draft</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    {!! Form::label('visebility', 'Visebility:') !!}
                                </div>
                                <div class="col-sm-8">
                                    <select name="role" class="form-control form-small">
                                        <option value="">Public</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    {!! Form::label('date', 'Publish date:') !!}
                                </div>
                                <div class="col-sm-8">
                                    {!! Form::text('datetime','', [ 'data-field' => 'datetime', 'class' => 'form-control', 'readonly' ] ) !!}
                                    <div id="dtBox"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    {!! Form::submit('Save post', [ 'class' => 'btn btn-success' ] ) !!}
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
                                Reviewer
                            </a>
                        </h4>
                    </div>
                    <div id="collapseRevieuwer" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-12 form-group">
                                    <select class="form-control">
                                        <option>reviewer nams come in this dropdown box</option>
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
                                Category
                            </a>
                        </h4>
                    </div>
                    <div id="collapseCategory" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-12 form-group input-group">
                                    {!! Form::text('newCategory','', [ 'class' => 'form-control', 'placeholder' => 'Create new category' ] ) !!}
                                    <span class="input-group-btn">
                                    <button type="button" class="btn btn-success"><i class="fa fa-plus"></i></button>
                                    </span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label for="test">
                                                {!!Form::radio('test', 'value', ['id' => 'test'])!!}
                                                Category 1
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label for="test">
                                                {!!Form::radio('test', 'value', ['id' => 'test'])!!}
                                                Category 2
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label for="test">
                                                {!!Form::radio('test', 'value', ['id' => 'test'])!!}
                                                Category 3
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label for="test">
                                                {!!Form::radio('test', 'value', ['id' => 'test'])!!}
                                                Category 4
                                            </label>
                                        </div>
                                    </div>
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
                                Tags
                            </a>
                        </h4>
                    </div>
                    <div id="collapseTags" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-12 form-group input-group">
                                    {!! Form::text('tags','', [ 'class' => 'form-control', 'placeholder' => 'Add tags...' ] ) !!}
                                    <span class="input-group-btn">
                                    <button type="button" class="btn btn-success"><i class="fa fa-plus"></i></button>
                                    </span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <span id="helpBlock" class="help-block">Separate tags with commas</span>
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