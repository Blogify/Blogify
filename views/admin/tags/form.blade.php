@extends('blogify::admin.layouts.dashboard')
@section('page_heading', isset($tag) ? trans("blogify::tags.form.page_title_edit")  : trans("blogify::tags.form.page_title_create") )
@section('section')

    @include('blogify::admin.snippets.validation-errors')

    @if ( ! isset($tag) )
        {!! Form::open( [ 'route' => 'admin.tags.store' ] ) !!}
    @else
        {!! Form::open( [ 'route' => ['admin.tags.update', $tag->id] ] ) !!}
        {!! Form::hidden('_method', 'PUT') !!}
    @endif

    <div class="row form-group {{ $errors->has('name') ? 'has-error' : '' }}">
        <div class="col-sm-1">
            {!! Form::label('tags', trans("blogify::tags.form.tags.label") ) !!}
        </div>
        <div class="col-sm-11">
            {!! Form::text('tags', isset($tag) ? $tag->name : '', ['class' => 'form-control form-small' ]) !!}
            @if ( ! isset($tag) )
                <span id="helpBlock" class="help-block">{{ trans("blogify::tags.form.help_block") }}</span>
            @endif
        </div>
    </div>

    <div class="row form-group {{ $errors->has('slug') ? 'has-error' : '' }}">
        <div class="col-sm-1">
            Slug
        </div>
        <div class="col-sm-11">
            {!! Form::text('slug', isset($tag) ? $tag->slug : '', ['class' => 'form-control form-small' ]) !!}
        </div>
    </div>

    <div class="row form-group {{ $errors->has('meta_title') ? 'has-error' : '' }}">
        <div class="col-sm-1">
            Meta_title
        </div>
        <div class="col-sm-11">
            {!! Form::text('meta_title', isset($tag) ? $tag->meta_title : '', ['class' => 'form-control form-small' ]) !!}
        </div>
    </div>

    <div class="row form-group {{ $errors->has('meta_description') ? 'has-error' : '' }}">
        <div class="col-sm-1">
            Meta_description
        </div>
        <div class="col-sm-11">
            {!! Form::text('meta_description', isset($tag) ? $tag->meta_description : '', ['class' => 'form-control form-small' ]) !!}
        </div>
    </div>

    <div class="row">
        <div class="col-sm-2">
            {!! Form::submit(trans("blogify::tags.form.submit_button.value"), ['class'=>'btn btn-success']) !!}
        </div>
    </div>

    {!! Form::close() !!}

@stop