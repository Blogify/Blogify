@extends ('blogify::admin.layouts.plane')
@section ('body')
<div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
            <br /><br /><br />
               @section ('login_panel_title', trans("blogify::auth.login.title") )
               @section ('login_panel_body')
                    @if(Session::has('message'))
                        @include('blogify::admin.widgets.alert', ['class'=>'danger', 'message'=> Session::get('message'), 'icon'=> 'remove'])
                    @endif
                    {!! Form::open( ['route' => 'admin.login.post', 'role' => 'form' ] ) !!}
                        <fieldset>
                            <div class="form-group {!! $errors->get('email') ? 'has-error' : '' !!} ">
                                {!! Form::label('email', trans("blogify::auth.login.email.label") ) !!}<br>
                                {!! Form::email('email', null, [ 'placeholder' => trans("blogify::auth.login.email.placeholder"), 'class' => 'form-control' ] ) !!}
                            </div>
                            <div class="form-group {!! $errors->get('password') ? 'has-error' : '' !!} ">
                                {!! Form::label('password', trans("blogify::auth.login.password.label") ) !!}<br>
                                {!! Form::password('password', array( 'placeholder' => trans("blogify::auth.login.password.placeholder"), 'class' => 'form-control' ) ) !!}
                            </div>
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox('rememberme') !!}
                                    {{ trans("blogify::auth.login.remember_me.label") }}
                                </label>
                            </div>
                            {!! Form::submit( trans("blogify::auth.login.submit_button.value") , [ 'class' => 'btn btn-lg btn-block btn-primary' ] ) !!}
                        </fieldset>
                    {!! Form::close() !!}
                    <hr>
                   <p>
                       <a href="/password/email/" title="{{trans("blogify::auth.login.forgot-password.title")}}">{{trans("blogify::auth.login.forgot-password.value")}}</a>
                   </p>

                @endsection

                @include('blogify::admin.widgets.panel', ['as'=>'login', 'header'=>true, 'class' => ( (Session::has('message') || ($errors->count() > 0) ) ? 'danger' : 'default' ) ])
            </div>
        </div>
    </div>
@stop