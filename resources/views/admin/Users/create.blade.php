@extends('admin.layouts.AdminPanel')

@section('title')
	User Add
@endsection

@section('content')
   {{ Html::style('resources/views/admin/assets/bower_components/select2/dist/css/select2.min.css') }}
   {{ Html::style('resources/views/admin/assets/bower_components/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }} 
<!--     <section class="content-header">
        <h1>
            User
            <small>Create</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('admin/home') }}"><i class="fa fa-dashboard"></i>Home</a></li>
            <li><a href="{{ route('users.index') }}">User</a></li>
            <li class="active">Add</li>
        </ol>
    </section> -->
    <section class="content">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Create User <small class="text-red">* Fields are mendatory </small></h3>
          <div class="box-tools pull-right">
          	<a href="{{ route('users.index') }}" class="btn-sm"><i class="fa fa-list-ul fa-add-edit-delete-btn"></i></a>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
			<div class="row">
			{{ Form::open(['url' => 'admin/users', 'method' => 'POST', 'files' => true, 'class' => '', 'id' => 'form-addedit','enctype' => 'multipart/form-data']) }}
                <div class="col-md-12">
                    <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('name', 'Name *', ['class' => '']) }}
                        {{ Form::text('name', null, array('class' => 'form-control', 'placeholder' => 'Enter name')) }}
                        @if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>                 
                    </div>
                    <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('email', 'Email *', ['class' => '']) }}
                        {{ Form::text('email', null, array('class' => 'form-control', 'placeholder' => 'Enter email')) }}
                    </div>
                    </div>
                    <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('password', 'Password *', ['class' => '']) }}
                        {{ Form::text('password', null, array('class' => 'form-control', 'placeholder' => 'Enter password')) }}
                    </div>  
                    </div>
                    <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('password_confirmation', 'Confirm Password *', ['class' => '']) }}
                        {{ Form::text('password_confirmation', null, array('class' => 'form-control', 'placeholder' => 'Enter confirm password')) }}
                    </div>                     
                    </div>
                    <div class="col-md-6">
                     <div class="form-group">
                        {{ Form::label('user_image', 'Image *', ['class' => '']) }}                     
                        <input type="file" name="user_image" id="user_image"  class="form-control">
                    </div>     
                    </div>
                    <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('status', 'Status', ['class' => '']) }}
                        {{ Form::select('status', ['1' => 'Active', '2' => 'Inactive'], 1, array('placeholder' => 'Select Status', 'class' => 'form-control select2')) }}
                    </div>
                    </div>
                    <div class="col-md-6">
                    {{ Form::submit('Submit', array('class' => 'btn btn-primary', 'id' => 'submit-btn')) }}
                    {{ Form::reset('Cancel', array('class' => 'btn btn-warning', 'id' => 'cancel-btn')) }}
                    </div>
                </div>
            {{ Form::close() }}
          </div>
        </div>
      </div>      
    </section>
    {{ Html::script('assets/admin/plugins/validate/jquery.validate.min.js') }} 
	<script type="text/javascript">
        jQuery(document).ready(function(){
			jQuery("#form-addedit").validate({
				rules: {
					name: {
						required: true
					},
					email: {
						required: true,
                        email: true
					}
					,
					password: {
                        required: true
                    }
                    ,
                    password_confirmation: {
                        required: true,
                        equalTo: "#password"
                    } 				
					,
					user_image: {
						required: true
					}
				},
				messages: {
					name: {
						required: "Please enter name."
					},
					email: {
						required: "Please enter email.",
                        email: "Please enter a valid email."
					},
                    password: {
                        required: "Please Enter New Password."
                    },
                    password_confirmation: {
                        required: "Please Enter Confirm Password.",
                        equalTo: "Confirm Password Not Matched."
                    },
					user_image: {
						required: "Please select a image."
					}
					
				}
			});
        });
    </script>
    <style type="text/css">
    .error{
     color: red;   
    }
    </style>
@endsection