<?php
$trashed        = ($trashed) ? 1 : 0;
$currentPage    = (Request::has('page')) ? Request::get('page') : '1';
?>
@extends('blogify::admin.layouts.dashboard')
@section('page_heading', trans("blogify::posts.overview.page_title") )
@section('section')
    @if ( session()->get('notify') )
        @include('blogify::admin.snippets.notify')
    @endif
    @if ( session()->has('success') )
        @include('blogify::admin.widgets.alert', ['class'=>'success', 'dismissable'=>true, 'message'=> session()->get('success'), 'icon'=> 'check'])
    @endif

    <p>
        <a href="{{ ($trashed) ? route('admin.posts.index') : route('admin.posts.overview', ['trashed']) }}" title=""> {{ ($trashed) ? trans('blogify::posts.overview.links.active') : trans('blogify::posts.overview.links.trashed') }} </a>
    </p>

    @section ('cotable_panel_title', ($trashed) ? trans("blogify::posts.overview.table_head.title_trashed") : trans("blogify::posts.overview.table_head.title_active"))
    @section ('cotable_panel_body')

    <table id="table-apps" class="table table-hover table-striped">
        <thead>
        <tr>
            <th>Title</th>
            <th>Slug</th>
            <th>Status</th>
            <th>Publish date</th>
            <th>Feature</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @if ( count($posts) <= 0 )
            <tr>
                <td colspan="7">
                    <em>@lang('blogify::posts.overview.no_results')</em>
                </td>
            </tr>
        @endif
        @foreach ( $posts as $post )
            <tr>
                <td>{!! $post->title !!}</td>
                <td>{!! $post->slug !!}</td>
                <td>{!! $post->status->name !!}</td>
                <td data-order="{{ $post->carbon_publish_date->timestamp }}">{!! $post->publish_date !!}</td>
                <td>{{ $post->highlight == 1 ? 'Yes' : 'No' }}</td>
                <td>
                    @if(!$trashed)
                        <a href="{{ route('admin.posts.edit', [$post->id] ) }}"><span class="fa fa-edit fa-fw"></span></a>
                        <a href="{{ route('admin.posts.show', [$post->id] ) }}"><span class="fa fa-eye fa-fw"></span></a>
                        <a href="{{ route('admin.posts.clear',[$post->id] ) }}"><span class="fa fa-unlock-alt fa-fw"></span></a>
                        {!! Form::open( [ 'route' => ['admin.posts.destroy', $post->id], 'class' => $post->id . ' form-delete' ] ) !!}

                        {!! Form::hidden('_method', 'delete') !!}
                        <a href="#" title="{{$post->name}}" class="delete" id="{{$post->id}}"><span class="fa fa-trash-o fa-fw"></span></a>
                        {!! Form::close() !!}
                    @else
                        <a href="{{route('admin.posts.restore', [$post->id])}}" title="">Restore</a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th>Title</th>
                <th>Slug</th>
                <th>Status</th>
                <th>Publish date</th>
                <th>Feature</th>
                <th data-searchable="false">Action</th>
            </tr>
        </tfoot>
    </table>

@endsection

@include('blogify::admin.widgets.panel', ['header'=>true, 'as'=>'cotable'])

@stop

@section('scripts')
<link href="/assets/js/DataTables-1.10.7/media/css/jquery.dataTables.css" rel="stylesheet" type="text/css"/>
<script src="/assets/js/DataTables-1.10.7/media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="/assets/js/handlebars.js" type="text/javascript"></script>
<script>
        $('#table-apps tfoot th').each( function () {
            var title = $(this).text();
            $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
        } );

        var table = $('#table-apps').DataTable({
            "iDisplayLength": 20
        });

        table.columns().every( function () {
            var that = this;

            $( 'input', this.footer() ).on( 'keyup change', function () {
                if ( that.search() !== this.value ) {
                    that
                            .search( this.value )
                            .draw();
                }
            } );
        } );
</script>

@endsection