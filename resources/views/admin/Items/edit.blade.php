@extends('admin.layouts.AdminPanel')
@section('title')
	Item Edit
@endsection
@section('content')
	{{ Html::style('resources/views/admin/assets/bower_components/select2/dist/css/select2.min.css') }}
    
    <!-- <section class="content-header">
        <h1>
            Item
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('admin/dashboard') }}"><i class="fa fa-dashboard"></i>Home</a></li>
            <li><a href="{{ route('items.index') }}">Item</a></li>
            <li class="active">Edit</li>
        </ol>
    </section> -->
    
    <section class="content">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Update Item <small class="text-red">* Fields are mendatory </small></h3>
          <div class="box-tools pull-right">
          	<a href="{{ route('items.index') }}" class="btn-sm"><i class="fa fa-list-ul fa-add-edit-delete-btn"></i></a>
            <a href="{{ route('items.create') }}" class="btn-sm"><i class="fa fa-plus-square-o fa-add-edit-delete-btn"></i></a>
          </div>
        </div>
        <div class="box-body">
			<div class="row">
            {{ Form::model($data, ['url' => ['admin/items', $data->id], 'method' => 'PUT', 'files' => true, 'class' => '', 'id' => 'form-addedit','enctype' => 'multipart/form-data']) }}
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
                        {{ Form::label('user_image', 'Images *', ['class' => '']) }}  
                        {{ Form::hidden('pre_user_image',$data->image_name, array()) }}                     
                        <input type="file" name="user_image"  class="form-control">
                    </div>   
                     </div>                 
                    <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('status', 'Status', ['class' => '']) }}
                        {{ Form::select('status', array('1' => 'Active', '2' => 'Inactive'), null, array('placeholder' => 'Select Status', 'class' => 'select2me form-control')) }}
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
                    },                   
                    user_image: {
                        required: false
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