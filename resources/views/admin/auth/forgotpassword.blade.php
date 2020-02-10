@extends('admin.layouts.LoginPanelLayout');

@section('title')
	Login
@endsection

@section('content')
<!-- BEGIN REGISTRATION FORM -->
    {{ Form::open(array('route' => 'admin-forgot-password-post', 'class' => 'login-form')) }}
        <h3>Forget Password ?</h3>
        <p>
			 Enter your e-mail address below to reset your password.
		</p>

        <div class="form-group">
            <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
            {{ Form::label('email', 'Email', array('class' => 'control-label visible-ie8 visible-ie9')) }}
            <div class="input-icon">
                <i class="fa fa-envelope"></i>
                {{ Form::text('email', '', array('class' => 'form-control placeholder-no-fix', 'placeholder' => 'Email')) }}
            </div>
        </div>

        <div class="form-actions">
            <a href="{!! URL::route('admin-login') !!}" id="register-btn">
                 Login to my account
            </a>
			{{ Form::submit('Send', array('class' => 'btn green pull-right', 'id' => 'login-submit-btn')) }}
		</div>
		
		
		<div class="create-account">
			<p>
				 Don't have an account yet ?&nbsp;
				<a href="{!! URL::route('admin-signup') !!}" id="register-btn">
					 Create an account
				</a>
			</p>
		</div>

    {{ Form::close() }}
<!-- END REGISTRATION FORM --> 
       
@endsection