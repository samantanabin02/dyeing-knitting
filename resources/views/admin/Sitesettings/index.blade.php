@extends('admin.layouts.AdminPanel')

@section('title')
    Site Settings
@endsection

@section('content')

    {{ Html::style('resources/views/admin/assets/bower_components/select2/dist/css/select2.min.css') }}
    
<!--     <section class="content-header">
        <h1>
            Site Settings
            <small>Update</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('admin/dashboard') }}"><i class="fa fa-dashboard"></i>Home</a></li>
            <li class="active"><a href="{{ route('site-settings') }}">Site Settings</a></li>
        </ol>
    </section> -->
    
    <section class="content">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">  Site Settings <small class="text-red">* Fields are mendatory </small></h3>
        </div>
        <div class="box-body">
            <div class="row">
            {{ Form::model($data, ['url' => ['admin/site-settings'], 'method' => 'POST', 'files' => true, 'class' => '', 'id' => 'form-addedit','enctype' => 'multipart/form-data']) }}
                 <div class="col-md-12">
                    <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('title', 'Title *', ['class' => '']) }}
                        {{ Form::text('title', null, array('class' => 'form-control', 'placeholder' => 'Enter title')) }}
                        @if ($errors->has('title'))
                            <span class="help-block">
                                <strong>{{ $errors->first('title') }}</strong>
                            </span>
                        @endif
                    </div>   
                    </div>
                    <div class="col-md-6">               
                    <div class="form-group">
                        {{ Form::label('contact_number', 'Contact Number', ['class' => '']) }}
                        {{ Form::text('contact_number', null, array('class' => 'form-control', 'placeholder' => 'Enter Contact Number')) }}
                    </div> 
                    </div>
                    <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('contact_email', 'Contact Email *', ['class' => '']) }}
                        {{ Form::text('contact_email', null, array('class' => 'form-control', 'placeholder' => 'Enter Contact Email')) }}
                    </div> 
                    </div>
                    <div class="col-md-6"> 
                    <div class="form-group">
                        {{ Form::label('address', 'Address', ['class' => '']) }}
                        {{ Form::text('address', null, array('class' => 'form-control', 'placeholder' => 'Enter Address')) }}
                    </div> 
                    </div>
                    <div class="col-md-12">
                    <div class="form-group">
                        {{ Form::label('embedded_map', 'Embedded Map', ['class' => '']) }}
                        {{ Form::textarea('embedded_map', null, array('class' => 'form-control', 'placeholder' => 'Enter Embedded Map Address','rows'=>'4')) }}
                    </div> 
                    </div>
                    <div class="col-md-6"> 
                    <?php if($data->logo){ ?>      
                    <div class="form-group">
                        <?php 
                        $logo=$data->logo;
                        ?>                                             
                        <img src="{{ URL::to('/public/uploads/site_items/'.$logo) }}" height="60px">
                        <?php                                
                        ?>
                    </div>
                    <?php }?>
                    <div class="form-group">
                        {{ Form::label('logo', 'Logo', ['class' => '']) }}
                        {{ Form::file('logo', null, array('class' => 'form-control')) }}
                    </div>  
                    {{ Form::submit('Submit', array('class' => 'btn btn-primary', 'id' => 'submit-btn')) }}
                    {{ Form::reset('Cancel', array('class' => 'btn btn-warning', 'id' => 'cancel-btn')) }}
                    </div>
                </div>
            {{ Form::close() }}
          </div>
        </div>
<!--         <div class="box-footer">
            <a href="{{ route('site-settings') }}" class="btn btn-info"><i class="fa fa-chevron-circle-left"></i></a>
        </div> -->
      </div>

    </section>
        
   {{ Html::script('assets/admin/plugins/validate/jquery.validate.min.js') }} 
    
    <script type="text/javascript">
        jQuery(document).ready(function(){
            jQuery("#form-addedit").validate({
                rules: {
                    title: {
                        required: true,
                    },
                    contact_number: {
                        required: false,    
                        digits:true                    
                    },
                    contact_email: {
                        required: true,    
                        email: true,                    
                    },
                    address: {
                        required: false,                        
                    }           
                    
                },
                messages: {
                    title: {
                        required: "Please enter site title."
                    },
                    contact_number: {
                        required: "Please enter contact number.",
                        digits: "Please enter numeric number.",
                    },
                    contact_email: {
                        required: "Please enter contact email.",
                        email: "Please enter valid email.",
                    },
                    address: {
                        required: "Please enter address.",
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