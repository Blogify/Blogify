@extends('blogify::admin.layouts.dashboard')
@section('page_heading', isset($tag) ? trans("blogify::tags.form.page_title_edit")  : trans("blogify::tags.form.page_title_create") )
@section('section')

    @include('blogify::admin.snippets.validation-errors')

    {!! Form::open( [ 'route' => 'admin.tags.store' ] ) !!}

    <div class="row form-group {{ $errors->has('tags') ? 'has-error' : '' }}">
        <div class="col-sm-1">
            {!! Form::label('tags', trans("blogify::tags.form.tags.label") ) !!}
        </div>
        <div class="col-sm-11">
            {!! Form::text('tags', isset($tag) ? $tag->name : '', ['class' => 'form-control form-small' ]) !!}
            <span id="helpBlock" class="help-block">{{ trans("blogify::tags.form.help_block") }}</span>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-2">
            {!! Form::submit(trans("blogify::tags.form.submit_button.value"), ['class'=>'btn btn-success']) !!}
        </div>
    </div>

    {!! Form::close() !!}

@stop