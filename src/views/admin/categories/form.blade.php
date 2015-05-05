@extends('blogify::admin.layouts.dashboard')
@section('page_heading', isset($category) ? trans("blogify::categories.form.page_title_edit")  : trans("blogify::categories.form.page_title_create") )
@section('section')

    @include('blogify::admin.snippets.validation-errors')

    @if ( ! isset($category) )
        {!! Form::open( [ 'route' => 'admin.categories.store' ] ) !!}
    @else
        {!! Form::open( [ 'route' => ['admin.categories.update', $category->hash] ] ) !!}
        {!! Form::hidden('_method', 'PUT') !!}
    @endif

    <div class="row form-group {{ $errors->has('name') ? 'has-error' : '' }}">
        <div class="col-sm-1">
            {!! Form::label('tags', trans("blogify::categories.form.tags.label") ) !!}
        </div>
        <div class="col-sm-11">
            {!! Form::text('name', isset($category) ? $category->name : '', ['class' => 'form-control form-small' ]) !!}
        </div>
    </div>

    <div class="row">
        <div class="col-sm-2">
            {!! Form::submit(trans("blogify::categories.form.submit_button.value"), ['class'=>'btn btn-success']) !!}
        </div>
    </div>

    {!! Form::close() !!}

@stop