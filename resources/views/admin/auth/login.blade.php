@extends('admin.layouts.AuthPanel')

@section('title')
	Login
@endsection

@section('content')

<div class="login-box-body">
    <p class="login-box-msg">Sign in to admin panel</p>

    {{ Form::open(['route' => ['admin-login'], 'method' => 'post', 'id' => 'admin-login', 'class' => '']) }}
        
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
        <div class="row">
        <!-- <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
            	{!! Form::checkbox('remember', 1) !!}
              	Remember Me
            </label>
          </div>
        </div> -->
        <div class="col-xs-4">
        </div>
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
        </div>
    
        </div>
    {!! Form::close() !!}  

  </div>
@endsection