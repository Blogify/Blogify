@extends('blogify::admin.layouts.dashboard')
@section('page_heading',trans("blogify::posts.form.page.title.create"))
@section('section')
    {!! Form::open( ['route' => 'admin.posts.store'] ) !!}
    {!! Form::hidden('hash','') !!}
    <div class="row">
        <div class="col-lg-8 col-md-12">
            <div class="row">
                <div class="col-lg-12 col-md-12 form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                    {!! Form::text('title', '', [ 'class' => 'form-control', 'id' => 'title', 'placeholder' => trans("blogify::posts.form.title.placeholder") ] ) !!}
                    @if ( $errors->has('title') )
                        <span class="help-block text-danger">{{$errors->first('title')}}</span>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 form-group {{ $errors->has('slug') ? 'has-error' : '' }}">
                    {!! Form::text('slug', '', [ 'class' => 'form-control', 'id' => 'slug', 'placeholder' => trans("blogify::posts.form.slug.placeholder") ] ) !!}
                    @if ( $errors->has('slug') )
                        <span class="help-block text-danger">{{$errors->first('slug')}}</span>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 form-group {{ $errors->has('short_description') ? 'has-error' : '' }}">
                    {!! Form::textarea('short_description', '', ['id' => 'short_description', 'class' => 'form-control', 'placeholder' => 'Enter a short description here'] ) !!}
                    @if ( $errors->has('short_description') )
                        <span class="help-block text-danger">{{$errors->first('short_description')}}</span>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 form-group {{ $errors->has('post') ? 'has-error' : '' }}">
                    <textarea name="post" id="post" class="form-control"></textarea>
                    @if ( $errors->has('post') )
                        <span class="text-danger help-block">{{$errors->first('post')}}</span>
                    @endif
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
                                <div class="col-sm-8 {{ $errors->has('status') ? 'has-error' : '' }}">
                                    <select name="status" class="form-control form-small">
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
                                <div class="col-sm-8 {{ $errors->has('visibility') ? 'has-error' : '' }}">
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
                                <div class="col-sm-8 form-group {{ $errors->has('publishdate') ? 'has-error' : '' }}">
                                    {!! Form::text('publishdate', $publish_date , [ 'data-field' => 'datetime', 'class' => 'form-control', 'readonly' ] ) !!}
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
                                    <select name="reviewer" class="form-control">
                                        <option selected value="{{Auth::user()->hash}}">{{Auth::user()->fullName}}</option>
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
                                <div class="col-sm-12 form-group input-group" id="cat-form">
                                    {!! Form::text('newCategory','', [ 'class' => 'form-control', 'id' => 'newCategory', 'placeholder' => trans("blogify::posts.form.category.placeholder") ] ) !!}
                                    <span class="input-group-btn">
                                    <button type="button" id="create-category" class="btn btn-success"><i class="fa fa-plus"></i></button>
                                    </span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 text-danger" id="cat-errors"></div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12" id="categories">
                                    @if ( count($categories) <= 0 )
                                        <span id="helpBlock" class="help-block">{{ trans("blogify::posts.form.category.no_results") }}</span>
                                    @endif

                                    @foreach ( $categories as $category )
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <label for="{{$category->name}}">
                                                    {!!Form::radio('category', $category->hash, ['id' => '$category->name'])!!}
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
                                    {!! Form::text('newTags','', [ 'class' => 'form-control', 'id' => 'newTags', 'placeholder' => trans("blogify::posts.form.tags.placeholder") ] ) !!}
                                    <span class="input-group-btn">
                                    <button type="button" class="btn btn-success" id="tag-btn"><i class="fa fa-plus"></i></button>
                                    </span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    {!! Form::hidden('tags', '', [ 'id' => 'addedTags' ]) !!}
                                    <span id="helpBlock" class="help-block">{{ trans("blogify::posts.form.tags.help_block") }}</span>
                                    <div id="tag-errors" class="text-danger"></div>
                                    <div id="tags">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end tags box -->


        </div>
        {!! Form::close() !!}
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