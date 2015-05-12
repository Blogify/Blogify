@if ( count( $errors->all() ) > 0 )
    @section ('panel6_panel_title', 'Please provide valid content for the listed fields')
    @section ('panel6_panel_body')
        <ul>
            @foreach ( $errors->all() as $error )
                <li>{{$error}}</li>
            @endforeach
        </ul>
    @endsection
    @include('blogify::admin.widgets.panel', ['class'=>'danger', 'header'=>true, 'as'=>'panel6'])
@endif