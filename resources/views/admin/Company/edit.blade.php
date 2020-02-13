@extends('admin.layouts.AdminPanel')
@section('title')
	User Edit
@endsection
@section('content')
	{{ Html::style('resources/views/admin/assets/bower_components/select2/dist/css/select2.min.css') }}
    
    <!-- <section class="content-header">
        <h1>
            User
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('admin/dashboard') }}"><i class="fa fa-dashboard"></i>Home</a></li>
            <li><a href="{{ route('users.index') }}">User</a></li>
            <li class="active">Edit</li>
        </ol>
    </section> -->
    
    <section class="content">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Update Company <small class="text-red">* Fields are mendatory </small></h3>
          <div class="box-tools pull-right">
          	<a href="{{ route('company.index') }}" class="btn-sm"><i class="fa fa-list-ul fa-add-edit-delete-btn"></i></a>
            <a href="{{ route('company.create') }}" class="btn-sm"><i class="fa fa-plus-square-o fa-add-edit-delete-btn"></i></a>
          </div>
        </div>
        <div class="box-body">
			<div class="row">
            {{ Form::model($data, ['url' => ['admin/company', $data->id], 'method' => 'PUT', 'files' => true, 'class' => '', 'id' => 'form-addedit','enctype' => 'multipart/form-data']) }}
                               <div class="col-md-12">
                    <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('Company Name', 'Company Name *', ['class' => '']) }}
                        {{ Form::text('company_name', null, array('class' => 'form-control', 'placeholder' => 'Enter Company Name')) }}
                        @if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('company_name') }}</strong>
                            </span>
                        @endif
                    </div>                 
                    </div>
                    <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('Company Email', 'Company Email *', ['class' => '']) }}
                        {{ Form::text('company_email', null, array('class' => 'form-control', 'placeholder' => 'Enter Company Email')) }}
                    </div>
                    </div>
                    <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('Phone No 1', 'Phone No 1 *', ['class' => '']) }}
                        {{ Form::text('company_number_1', null, array('class' => 'form-control', 'placeholder' => 'Enter Phone No 1')) }}
                    </div>  
                    </div>
                    <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('Phone No 2', 'Phone No 2 *', ['class' => '']) }}
                        {{ Form::text('company_number_2', null, array('class' => 'form-control', 'placeholder' => 'Enter Phone No 2')) }}
                    </div>                     
                    </div>
                    <div class="col-md-6">
                     <div class="form-group">
                        {{ Form::label('Company Address', 'Company Address *', ['class' => '']) }}                     
                        {{ Form::text('company_address', null, array('class' => 'form-control', 'placeholder' => 'Enter Company Address')) }}
                    </div>     
                    </div>
                    <div class="col-md-6">
                     <div class="form-group">
                        {{ Form::label('Country', 'Country *', ['class' => '']) }}                     
                        {{ Form::text('company_country', null, array('class' => 'form-control', 'placeholder' => 'Enter Country')) }}
                    </div>     
                    </div>
                    <div class="col-md-6">
                     <div class="form-group">
                        {{ Form::label('State', 'State *', ['class' => '']) }}                     
                        {{ Form::text('company_state', null, array('class' => 'form-control', 'placeholder' => 'Enter State')) }}
                    </div>     
                    </div>
                    <div class="col-md-6">
                     <div class="form-group">
                        {{ Form::label('City', 'City *', ['class' => '']) }}                     
                        {{ Form::text('company_city', null, array('class' => 'form-control', 'placeholder' => 'Enter City')) }}
                    </div>     
                    </div>
                    <div class="col-md-6">
                     <div class="form-group">
                        {{ Form::label('GST NO', 'GST NO *', ['class' => '']) }}                     
                        {{ Form::text('company_gst_no', null, array('class' => 'form-control', 'placeholder' => 'Enter GST NO')) }}
                    </div>     
                    </div>
                    <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('status', 'Status', ['class' => '']) }}
                        {{ Form::select('status', ['1' => 'Active', '2' => 'Inactive'], 1, array('placeholder' => 'Select Status', 'class' => 'form-control select2')) }}
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
                    company_name: {
                        required: true
                    },
                    company_email: {
                        required: true,
                        email: true
                    },
                    company_number_1: {
                        required: true
                    },
                    company_number_2: {
                        required: true
                    },
                    company_address: {
                        required: true
                    },
                    company_country: {
                        required: true
                    },
                    company_state: {
                        required: true
                    },
                    company_city: {
                        required: true
                    },
                    company_gst_no: {
                        required: true
                    },
                },
                messages: {
                    company_name: {
                        required: "Please enter company name."
                    },
                    company_email: {
                        required: "Please enter company email.",
                        email: "Please enter a valid email."
                    },
                    company_number_1: {
                        required: "Please enter company phone no 1."
                    },
                    company_number_2: {
                        required: "Please enter company phone no 2."
                    },
                    company_address: {
                        required: "Please enter company address."
                    },
                    company_country: {
                        required: "Please enter country."
                    },
                    company_state: {
                        required: "Please enter state."
                    },
                    company_city: {
                        required: "Please enter city."
                    },
                    company_gst_no: {
                        required: "Please enter GST NO."
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