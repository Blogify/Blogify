<?php
    if (! empty($post) && count($post->tag) > 0) {
        $hashes = '';
        $i      = 0;
        $count  = count($post->tag);

        foreach ($post->tag as $tag) {
            $hash = $tag->id;

            if ($i < $count - 1) {
                $hash = $hash.',';
            }

            $hashes .= $hash;
            $i++;
        }
    }
?>
@extends('blogify::admin.layouts.dashboard')
@section('page_heading',trans("blogify::posts.form.page.title.create"))
@section('section')

    {!! Form::open( ['route' => 'admin.posts.store'] ) !!}
    {!! Form::hidden('id', (isset($post)) ? $post->id : '') !!}

    <div class="row">
        <div class="col-lg-8 col-md-12">
            <div class="row">
                <div class="col-md-2"><p>Title:</p></div>
                <div class="col-md-10 form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                    {!! Form::text('title', isset($post) ? $post->title : '' , [ 'class' => 'form-control', 'id' => 'title', 'placeholder' => trans("blogify::posts.form.title.placeholder") ] ) !!}
                    @if ( $errors->has('title') )
                        <span class="help-block text-danger">{{$errors->first('title')}}</span>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"><p>Slug:</p></div>
                <div class="col-md-10 form-group {{ $errors->has('slug') ? 'has-error' : '' }} has-feedback">
                    {!! Form::text('slug', isset($post) ? $post->slug : '', [ 'class' => 'form-control', 'id' => 'slug', 'placeholder' => trans("blogify::posts.form.slug.placeholder") ] ) !!}
                    <span class="hidden form-control-feedback" aria-hidden="true"><img src="{{URL::asset('assets/blogify/img/ajax-loader.gif')}}" /></span>
                    @if ( $errors->has('slug') )
                        <span class="help-block text-danger">{{$errors->first('slug')}}</span>
                    @endif
                </div>
            </div>

            <div class="row">

                <div class="col-md-2"><p>Feature post:</p></div>
                <div class="col-md-10 form-group {{ $errors->has('highlight') ? 'has-error' : '' }} has-feedback">
                    {!! Form::select('highlight', [0 => 'No', 1 => 'Yes'], isset($post) ? $post->highlight : 0, [ 'class' => 'form-control', 'id' => 'highlight' ] ) !!}
                    @if ( $errors->has('highlight') )
                        <span class="help-block text-danger">{{$errors->first('highlight')}}</span>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class=col-md-2>
                    <p>Meta description</p>
                </div>
                <div class="col-md-10 form-group {{ $errors->has('meta_desc') ? 'has-error' : '' }}">
                    {!! Form::text('meta_desc', isset($post) ? $post->meta_desc : '' , [ 'class' => 'form-control', 'id' => 'metaDesc' ] ) !!}
                    @if ( $errors->has('meta_desc') )
                        <span class="help-block text-danger">{{$errors->first('meta_desc')}}</span>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-md-2">
                    <p>Meta keywords</p>
                </div>
                <div class="col-md-10 form-group {{ $errors->has('meta_keys') ? 'has-error' : '' }}">
                    {!! Form::text('meta_keys', isset($post) ? $post->meta_keys : '' , [ 'class' => 'form-control', 'id' => 'metaKeys' ] ) !!}
                    @if ( $errors->has('meta_keys') )
                        <span class="help-block text-danger">{{$errors->first('meta_keys')}}</span>
                    @endif
                </div>
            </div>
            
            <div class="row">
                <div class=col-md-2>
                    <p>Meta title</p>
                </div>
                <div class="col-md-10 form-group {{ $errors->has('meta_title') ? 'has-error' : '' }}">
                    {!! Form::text('meta_title', isset($post) ? $post->meta_title : '' , [ 'class' => 'form-control', 'id' => 'metaTitle' ] ) !!}
                    @if ( $errors->has('meta_title') )
                        <span class="help-block text-danger">{{$errors->first('meta_title')}}</span>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-md-2">
                    Register
                </div>
                <div class="col-md-10 form-group {{ $errors->has('popup') ? 'has-error' : '' }}">
                    {!! Form::checkbox('popup', 1, is_null($post) ? false : $post->popup,  ['class' => 'form-check-input', 'id' => 'popup']) !!}
                    <label for="popup">Popup</label>
                    @if ( $errors->has('popup') )
                        <span class="help-block text-danger">{{$errors->first('popup')}}</span>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12 form-group {{ $errors->has('post') ? 'has-error' : '' }}">
                    <textarea name="post" id="post" class="form-control">
                        {{ isset($post) ? $post->content : '' }}
                        {{ Input::old('post') }}
                    </textarea>
                    @if ( $errors->has('post') )
                        <span class="text-danger help-block">{{$errors->first('post')}}</span>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 auto-save-log">

                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12">

            <!-- publish box -->
            <div class="panel-group" id="accordion">
                <div class="panel panel-{{ isset($class) ? $class : 'default' }}">
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
                                    <select name="status" id="status" class="form-control form-small">
                                        @foreach ( $statuses as $status )
                                            @if ( isset($post) )
                                                <option {{ ($status->id === $post->status_id || $status->id == Input::old('status') ) ? 'selected' : '' }} value="{{$status->id}}">{{$status->name}}</option>
                                            @else
                                                <option {{  $status->id == Input::old('status') ? 'selected' : '' }} value="{{$status->id}}">{{$status->name}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    {!! Form::label('visibility', trans("blogify::posts.form.publish.visibility.label") ) !!}
                                </div>
                                <div class="col-sm-8 {{ $errors->has('visibility') ? 'has-error' : '' }}">
                                    <select name="visibility" id="visibility" class="form-control form-small">
                                        @foreach ( $visibility as $item )
                                            @if ( isset($post) )
                                                <option {{ ( $item->id === $post->visibility_id || $item->id == Input::old('visibility') ) ? 'selected' : '' }} value="{{$item->id}}">{{$item->name}}</option>
                                            @else
                                                <option {{  ( $item->id == Input::old('visibility') ) ? 'selected' : '' }} value="{{$item->id}}">{{$item->name}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row" id="password-protected-post">
                                <div class="col-sm-4">
                                    {!! Form::label('password', trans("blogify::posts.form.publish.password.label") ) !!}
                                </div>
                                <div class="col-sm-8 {{ $errors->has('password') ? 'has-error' : '' }}">
                                    {!! Form::password('password', ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    {!! Form::label('date', trans("blogify::posts.form.publish.publish_date.label") ) !!}
                                </div>
                                <div class="col-sm-8 form-group {{ $errors->has('publishdate') ? 'has-error' : '' }}">
                                    {!! Form::text('publishdate', isset($post) ? $post->publish_date : $publish_date , [ 'data-field' => 'datetime', 'class' => 'form-control', 'readonly', 'id' => 'publishdate' ] ) !!}
                                    <div id="dtBox"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    {!! Form::submit( trans("blogify::posts.form.publish.save_button.value"), [ 'class' => 'btn btn-success' ] ) !!}
                                    <a href="{{route('admin.posts.cancel', [isset($post) ? $post->id : ''])}}" class="btn btn-danger" role="button">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end publish box -->

            <!-- categories box -->
            <div class="panel-group" id="accordion">
                <div class="panel panel-{{ isset($class) ? $class : 'default' }}">
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
                                    <select name="reviewer" id="reviewer" class="form-control">
                                        <option {{ (!isset($post) ? 'selected' : '') }} value="{{Auth::user()->id}}">{{Auth::user()->fullName}}</option>
                                        @foreach ( $reviewers as $reviewer )
                                            @if ( isset($post) )
                                                <option {{ ($reviewer->id === $post->reviewer_id || $reviewer->id == Input::old('reviewer') ) ? 'selected' : '' }} value="{{$reviewer->id}}">{{$reviewer->fullName}}</option>
                                            @else
                                                <option {{ ( $reviewer->id == Input::old('reviewer') ) ? 'selected' : '' }} value="{{$reviewer->id}}">{{$reviewer->fullName}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end categories box -->

            <!-- tags box -->
            <div class="panel-group" id="accordion">
                <div class="panel panel-{{ isset($class) ? $class : 'default' }}">
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

                                    <div class="row">


                                        <?php $postTags = isset($post) ? $post->tag->pluck('id')->toArray() : []; ?>

                                        {!! Form::select('tags[]', $tags->pluck('name','id'), $postTags, ['id' => 'tags', 'class' => 'form-control', 'multiple' => 'multiple']) !!}


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
    <link rel="stylesheet" type="text/css" href="/assets/js/datetimepicker/DateTimePicker.css" />
    <link rel="stylesheet" type="text/css" href="/assets/js/bootstrap-multiselect/dist/css/bootstrap-multiselect.css" />
    <script type="text/javascript" src="/assets/js/datetimepicker/DateTimePicker.js"></script>
    <script type="text/javascript" src="/assets/js/bootstrap-multiselect/dist/js/bootstrap-multiselect.js"></script>
    <script type="text/javascript" src="/assets/app/public/blogify/tags-v-1.js"></script>
    <script src="/assets/js/ckeditor4.7/ckeditor.js"></script>
    <!--[if lt IE 9]>
    <link rel="stylesheet" type="text/css" href="/assets/js/datetimepicker/DateTimePicker-ltie9.css" />
    <script type="text/javascript" src="/assets/js/datetimepicker/DateTimePicker-ltie9.js"></script>
    <![endif]-->
@endsection