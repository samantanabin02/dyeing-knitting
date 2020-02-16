@extends('admin.layouts.AdminPanel')

@section('title')
	User Add
@endsection

@section('content')
   {{ Html::style('resources/views/admin/assets/bower_components/select2/dist/css/select2.min.css') }}
   {{ Html::style('resources/views/admin/assets/bower_components/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }} 
    <!--<section class="content-header">
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
          <h3 class="box-title">Create Item Type <small class="text-red">* Fields are mendatory </small></h3>
          <div class="box-tools pull-right">
          	<a href="{{ route('itemtype.index') }}" class="btn-sm"><i class="fa fa-list-ul fa-add-edit-delete-btn"></i></a>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
			<div class="row">
			{{ Form::open(['url' => 'admin/itemtype', 'method' => 'POST', 'files' => true, 'class' => '', 'id' => 'form-addedit','enctype' => 'multipart/form-data']) }}
                <div class="col-md-12">
                    <div class="col-md-12">
                    <div class="form-group">
                        {{ Form::label('Item Type Name', 'Item Type Name *', ['class' => '']) }}
                        {{ Form::text('item_type_name', null, array('class' => 'form-control', 'placeholder' => 'Enter Item Type Name')) }}
                    </div>
                    </div>
                    <div class="col-md-12">
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
					item_type_name: {
						required: true
					},
    			},
				messages: {
					item_type_name: {
						required: "Please enter item type name."
					},
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