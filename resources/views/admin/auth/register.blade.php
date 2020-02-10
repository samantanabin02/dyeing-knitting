@extends('admin.layouts.AuthPanel')

@section('title')
	Register
@endsection

@section('content')
<div class="register-box-body">
    <p class="register-box-msg">Register a new admin</p>
    {{ Form::open(['route' => ['admin-register'], 'method' => 'post', 'id' => 'admin-register', 'class' => '']) }}
        
        <div class="form-group has-feedback">
        {{ Form::text('name', null, ['id' => 'name', 'class' => 'form-control', 'placeholder' => 'Full Name']) }}
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
        @if ($errors->has('name'))
            <span class="help-block">
                <strong>{{ $errors->first('name') }}</strong>
            </span>
        @endif
        </div>
        
        <div class="form-group has-feedback">
        {{ Form::email('email', null, ['id' => 'email', 'class' => 'form-control', 'placeholder' => 'Email Address']) }}
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        @if ($errors->has('email'))
            <span class="help-block">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif
        </div>
        
        <div class="form-group has-feedback">
        {{ Form::password('password', ['id' => 'password', 'class' => 'form-control', 'placeholder' => 'Password']) }}
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
         @if ($errors->has('password'))
            <span class="help-block">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
        @endif
        </div>
        
        <div class="form-group has-feedback">
        {{ Form::password('password_confirmation', ['id' => 'password_confirmation', 'class' => 'form-control', 'placeholder' => 'Retype Password']) }}
        <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
        </div>
        
        <div class="row">
            <div class="col-xs-8">
              <div class="checkbox icheck">
                <label>
                  <!--<input id="agree" type="checkbox" name="agree" value="{{ old('agree') }}"> I agree to the <a href="#">terms</a>-->
                  {!! Form::checkbox('agree', 1) !!}
                  I agree to the <a href="#">terms</a>
                </label>
              </div>
                @if ($errors->has('agree'))
                    <span class="help-block">
                        <strong>{{ $errors->first('agree') }}</strong>
                    </span>
                @endif
            </div>
        <!-- /.col -->
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat">Register</button>
            </div>
        <!-- /.col -->
        </div>
    {!! Form::close() !!}

    <div class="social-auth-links text-center">
    	<p>- OR -</p>
        <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign up using
        Facebook</a>
        <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign up using
        Google+</a>
        </div>
        <a href="{{ route('admin-login') }}" class="text-center">I already have a membership</a>
    </div>

@endsection