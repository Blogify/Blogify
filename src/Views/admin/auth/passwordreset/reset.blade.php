@extends ('blogify::admin.layouts.plane')
@section ('body')
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <br /><br /><br />
                @section ('login_panel_title', trans("blogify::auth.reset-password.title") )
                @section ('login_panel_body')
                    @if(Session::has('message'))
                        @include('blogify::admin.widgets.alert', ['class'=>'danger', 'message'=> Session::get('message'), 'icon'=> 'remove'])
                    @endif


                    <form method="POST" action="/password/email">
                        <input name="_token" type="hidden" value="{{csrf_token()}}">

                        <fieldset>
                            <div class="form-group {!! $errors->get('email') ? 'has-error' : '' !!} ">
                                {!! Form::label('email', trans("blogify::auth.reset-password.email.label") ) !!}<br>
                                {!! Form::email('email', null, [ 'placeholder' => trans("blogify::auth.reset-password.email.placeholder"), 'class' => 'form-control' ] ) !!}
                            </div>

                            <div class="form-group {!! $errors->get('password') ? 'has-error' : '' !!} ">
                                {!! Form::label('password', trans("blogify::auth.reset-password.password.label") ) !!}<br>
                                {!! Form::password('password', array( 'placeholder' => trans("blogify::auth.reset-password.password.placeholder"), 'class' => 'form-control' ) ) !!}
                            </div>

                            <div class="form-group {!! $errors->get('password_confirmation') ? 'has-error' : '' !!} ">
                                {!! Form::label('password_confirmation', trans("blogify::auth.reset-password.password_confirmation.label") ) !!}<br>
                                {!! Form::password('password_confirmation', array( 'placeholder' => trans("blogify::auth.reset-password.password.placeholder"), 'class' => 'form-control' ) ) !!}
                            </div>

                            {!! Form::submit( trans("blogify::auth.reset-password.submit_button.value") , [ 'class' => 'btn btn-lg btn-block btn-primary' ] ) !!}
                        </fieldset>
                    </form>

                @endsection

                @include('blogify::admin.widgets.panel', ['as'=>'login', 'header'=>true, 'class' => ( (Session::has('message') || ($errors->count() > 0) ) ? 'danger' : 'default' ) ])
            </div>
        </div>
    </div>
@stop