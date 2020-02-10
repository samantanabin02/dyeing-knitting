@extends('admin.layouts.LoginPanelLayout');

@section('title')
	Dashboard
@endsection

@section('content')
<!-- BEGIN REGISTRATION FORM -->
	{{ Form::open(array('route' => 'admin-signup-post', 'method' => 'post', 'class' => 'register',)) }}
        <h3>Sign Up</h3>
        <p>
             Enter your personal details below:
        </p>
        <div class="form-group">
            {{ Form::label('name', 'Full Name', array('class' => 'control-label visible-ie8 visible-ie9')) }}
            <div class="input-icon">
                <i class="fa fa-font"></i>
                {{ Form::text('name', '', array('class' => 'form-control placeholder-no-fix', 'placeholder' => 'Full Name')) }}
            </div>
        </div>
        <div class="form-group">
            <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
            {{ Form::label('email', 'Email', array('class' => 'control-label visible-ie8 visible-ie9')) }}
            <div class="input-icon">
                <i class="fa fa-envelope"></i>
                {{ Form::text('email', '', array('class' => 'form-control placeholder-no-fix', 'placeholder' => 'Email')) }}
            </div>
        </div>

        <div class="form-group">
            {{ Form::label('password', 'Password', array('class' => 'control-label visible-ie8 visible-ie9')) }}
            <div class="input-icon">
                <i class="fa fa-lock"></i>
                {{ Form::password('password', array('class' => 'form-control placeholder-no-fix', 'placeholder' => 'Password',  'autocomplete' => 'off', 'id' => 'register_password')) }}
            </div>
        </div>
        <div class="form-group">
            {{ Form::label('rpassword', 'Re-type Your Password', array('class' => 'control-label visible-ie8 visible-ie9')) }}
            <div class="controls">
                <div class="input-icon">
                    <i class="fa fa-check"></i>
                    <!--<input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Re-type Your Password" name="rpassword"/>-->
                    {{ Form::password('rpassword', array('class' => 'form-control placeholder-no-fix', 'placeholder' => 'Re-type Your Password', 'autocomplete' => 'off')) }}
                </div>
            </div>
        </div>
        <div class="form-group">
            <label>
            <input type="checkbox" name="tnc"/> I agree to the
            <a href="#">
                 Terms of Service
            </a>
             and
            <a href="#">
                 Privacy Policy
            </a>
            </label>
            <div id="register_tnc_error">
            </div>
        </div>
        <div class="form-actions">
            {{ Form::submit('Sign Up', array('class' => 'btn green pull-right', 'id' => 'register-submit-btn')) }}
        </div>
        
        <div class="forget-password">
			<h4>Already registed user?</h4>
			<p>
				<a href="{!! URL::route('admin-login') !!}" id="forget-password">
					 Login to My Account
				</a>
			</p>
		</div>
    {{ Form::close() }}
<!-- END REGISTRATION FORM --> 
       
@endsection