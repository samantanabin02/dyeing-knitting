<?php //echo 'ok';die;?>
@extends('admin.layouts.AdminPanel')

@section('title')
    Change Password
@endsection

@section('content')

    <!-- <section class="content-header">
      <h1>
        Profile
        <small>Change Password</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('admin/dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Change Password</li>
      </ol>
    </section> -->

    <section class="content">
      <div class="row">
     
        <div class="col-md-12">
      
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Change Password</h3>
            </div>         

             {{ Form::open(array('url' => 'admin/change-password', 'method' => 'post', 'role' => 'form', 'id' => 'change_password')) }}

              <div class="box-body">
                <div class="col-md-4">

                <div class="form-group">                  

                  {!! Form::label('old_password', 'Old Password : ', array()) !!}

                  {!! Form::password('old_password', array('class' => 'form-control', 'placeholder' => 'Enter Old Password')) !!}

                </div>
              </div>

 <div class="col-md-4">
                <div class="form-group">                  

                 {!! Form::label('password', 'New Password : ', array()) !!}

                 {!! Form::password('password', array('class' => 'form-control', 'placeholder' => 'Enter Password')) !!}

                </div>

</div>

 <div class="col-md-4">
                <div class="form-group">                  

                 {!! Form::label('password_confirmation', 'Confirm Password : ', array()) !!}

                 {!! Form::password('password_confirmation', array('class' => 'form-control', 'placeholder' => 'Enter Password')) !!}

                </div>
                </div>
              </div>
         

              <div class="box-footer">                

                {!! Form::submit('Submit', array('class' => 'btn btn-primary')) !!}

                {!! Form::reset('Cancel', array('class' => 'btn default')) !!}

              </div>
            {!! Form::close() !!}
          </div>
          
       


        </div>
        
        
      </div>
     
    </section>

    {{ Html::script('assets/admin/plugins/validate/jquery.validate.min.js') }}

    <script type="text/javascript">
        jQuery(document).ready(function(){
            
            jQuery("#change_password").validate({
                rules: {
                    old_password: {
                        required: true
                    },
                    password: {
                        required: true
                    }
                    ,
                    password_confirmation: {
                        required: true,
                        equalTo: "#password"
                    }                                     
                    
                },
                messages: {
                    old_password: {
                        required: "Please Enter Old Password."
                    },
                    password: {
                        required: "Please Enter New Password."
                    },
                    password_confirmation: {
                        required: "Please Enter Confirm Password.",
                        equalTo: "Confirm Password Not Matched."
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