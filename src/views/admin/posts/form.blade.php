@extends('blogify::admin.layouts.dashboard')
@section('page_heading','Add new post')
@section('section')
    <div class="row">
        <div class="col-lg-8 col-md-12">
            <textarea id="post"></textarea>
        </div>
        <div class="col-lg-4 col-md-12">

            <!-- publish box -->
            <div class="panel-group" id="accordion">
                <div class="panel panel-{{{ isset($class) ? $class : 'default' }}}">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion"
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
                                    <input type="text" data-field="datetime" class="form-control" readonly>
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
                            <a data-toggle="collapse" data-parent="#accordion"
                               href="#collapsePublish">
                                Reviewer
                            </a>
                        </h4>
                    </div>
                    <div id="collapsePublish" class="panel-collapse collapse in">
                        <div class="panel-body">
                            select or edit the reviewer
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
                            <a data-toggle="collapse" data-parent="#accordion"
                               href="#collapsePublish">
                                Category
                            </a>
                        </h4>
                    </div>
                    <div id="collapsePublish" class="panel-collapse collapse in">
                        <div class="panel-body">
                            list of all categories
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
                            <a data-toggle="collapse" data-parent="#accordion"
                               href="#collapsePublish">
                                Tags
                            </a>
                        </h4>
                    </div>
                    <div id="collapsePublish" class="panel-collapse collapse in">
                        <div class="panel-body">
                            list of all tags
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