@extends('admin.layouts.LoginPanelLayout');

@section('title')
	Reset Password
@endsection

@section('content')
<!-- BEGIN REGISTRATION FORM -->
    {!! Form::open(array('route' => 'admin-reset-password-post', 'class' => 'login-form')) !!}
        <h3>Reset Password ?</h3>
        <p>
			 Enter your password and confirm passsword.
		</p>

        <div class="form-group">
            <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
            {!! Form::label('password', 'Password', array('class' => 'control-label visible-ie8 visible-ie9')) !!}
            <div class="input-icon">
                <i class="fa fa-lock"></i>
                {!! Form::password('password', array('class' => 'form-control placeholder-no-fix', 'placeholder' => 'Password')) !!}
            </div>
        </div>
        
        <div class="form-group">
            <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
            {!! Form::label('password_confirmation', 'Confirm Password', array('class' => 'control-label visible-ie8 visible-ie9'))!!}
            <div class="input-icon">
                <i class="fa fa-lock"></i>
                {!! Form::password('password_confirmation', array('class' => 'form-control placeholder-no-fix', 'placeholder' => 'Confirm Password')) !!}
            </div>
        </div>

        <div class="form-actions">
            <a href="{!! URL::route('admin-login') !!}" id="register-btn">
                 Login to my account
            </a>
            {!! Form::hidden('token', $token, array('class' => 'form-control placeholder-no-fix', 'placeholder' => 'Token')) !!}
			{!! Form::submit('Send', array('class' => 'btn green pull-right', 'id' => 'login-submit-btn')) !!}
		</div>
		
		
		<div class="create-account">
			<p>
				 Don't have an account yet ?&nbsp;
				<a href="{!! URL::route('admin-signup') !!}" id="register-btn">
					 Create an account
				</a>
			</p>
		</div>

    {!! Form::close() !!}
<!-- END REGISTRATION FORM --> 
       
@endsection