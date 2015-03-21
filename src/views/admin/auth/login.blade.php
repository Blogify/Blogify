@extends ('blogify.admin.layouts.plane')
@section ('body')
<div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
            <br /><br /><br />
               @section ('login_panel_title','Please Sign In')
               @section ('login_panel_body')
                    {!! Form::open( ['route' => 'admin.login.post', 'role' => 'form' ] ) !!}
                            <fieldset>
                                <div class="form-group">
                                    {!! Form::label('email', 'E-mail adress') !!}<br>
                                    {!! Form::email('email', null, [ 'placeholder' => 'example@example.be', 'class' => 'form-control' ] ) !!}
                                </div>
                                <div class="form-group">
                                    {!! Form::label('password', 'Password') !!}<br>
                                    {!! Form::password('password', array( 'placeholder' => 'Password', 'class' => 'form-control' ) ) !!}
                                </div>
                                <div class="checkbox">
                                    <label>
                                        {!! Form::checkbox('rememberme') !!}
                                        Remember me
                                    </label>
                                </div>
                                {!! Form::submit('Login', [ 'class' => 'btn btn-lg btn-block btn-primary' ] ) !!}
                            </fieldset>
                        {!! Form::close() !!}
                    
                @endsection
                @include('blogify.admin.widgets.panel', array('as'=>'login', 'header'=>true))
            </div>
        </div>
    </div>
@stop