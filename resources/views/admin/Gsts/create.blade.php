@extends('admin.layouts.AdminPanel')
@section('title')
	Gst Add
@endsection
@section('content')
   {{ Html::style('resources/views/admin/assets/bower_components/select2/dist/css/select2.min.css') }}
   {{ Html::style('resources/views/admin/assets/bower_components/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }} 
    <section class="content">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Create Gst <small class="text-red">* Fields are mendatory </small></h3>
          <div class="box-tools pull-right">
          	<a href="{{ route('gsts.index') }}" class="btn-sm"><i class="fa fa-list-ul fa-add-edit-delete-btn"></i></a>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
			<div class="row">
			{{ Form::open(['route' => ['gsts.store'], 'method' => 'POST', 'files' => true, 'class' => '', 'id' => 'form-addedit','enctype' => 'multipart/form-data']) }}
                <div class="col-md-12">
                    <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('name', 'Gst Name *', ['class' => '']) }}
                        {{ Form::text('name', null, array('class' => 'form-control', 'placeholder' => 'Enter gst name')) }}
                        @if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>                 
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                          {{ Form::label('desc', 'Gst Description *', ['class' => '']) }}
                          {{ Form::text('desc', null, array('class' => 'form-control', 'placeholder' => 'Enter gst description')) }}
                      </div>                 
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                          {{ Form::label('rate', 'Gst Rate *', ['class' => '']) }}
                          {{ Form::text('rate', null, array('class' => 'form-control', 'placeholder' => 'Enter gst rate')) }}
                      </div>                 
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                          {{ Form::label('status', 'Status', ['class' => '']) }}
                          {{ Form::select('status', ['1' => 'Active', '2' => 'Inactive'], 1, array('placeholder' => 'Select Status', 'class' => 'form-control select2')) }}
                      </div>
                    </div>
                    
                    <div class="col-md-12" style="height:10px;">
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
              desc: {
                  required: true
              },
              rate: {
                  required: true
              }
    				},
    				messages: {
    					name: {
    						required: "Please enter gst name."
    					},
              desc: {
                  required: "Please enter gst description."
              },
              rate: {
                  required: "Please enter gst rate."
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