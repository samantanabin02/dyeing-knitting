@extends('admin.layouts.AuthPanel')

@section('title')
	Login
@endsection

@section('content')

<div class="login-box-body">
    <p class="login-box-msg">Sign in to admin panel</p>

       {{ Form::open(['route' => ['admin-login'], 'method' => 'post', 'id' => 'admin-login', 'class' => '']) }}


        <div class="form-group has-feedback">
        <input type="email" name="email" id="email" class="form-control" value="<?php echo $admin_username; ?>">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>


        <div class="form-group has-feedback">
        {{ Form::password('password', ['id' => 'password', 'class' => 'form-control', 'placeholder' => 'Password', 'autocomplete'=>'off']) }}
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        @if ($errors->has('password'))
            <span class="help-block">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
        @endif
        </div>
        <div class="form-group" style="color:green;">
            Password sent to your email.Please check and login.
        </div>
        
        <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
            	{!! Form::checkbox('remember', 1) !!}
              	Remember Me
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat" id="admin_submit">Submit</button>
        </div>
        <!-- /.col -->
        </div>
    {!! Form::close() !!}  

  </div>
@endsection
 {!! Html::script('assets/admin/bower_components/jquery/dist/jquery.min.js') !!}
 {!! Html::script('assets/frontend/js/jquery.validate.min.js') !!}
 {!! Html::script('assets/admin/bower_components/bootstrap/dist/js/bootstrap.min.js') !!}
 {!! Html::script('assets/admin/plugins/iCheck/icheck.min.js') !!}

<script type="text/javascript"> 
jQuery(document).ready(function(){     
    $("#admin_submitss").click(function(){
         //alert('ok');
         var entered_email=$("#email").val(); 
         var entered_password=$("#password").val();

         

         var APP_URL = {!! json_encode(url('/')) !!}              

          if(entered_email!='' && entered_password!=''){  

               alert(entered_email);
               alert(entered_password);           
               $.ajax({
                  type:'POST',
                     url:APP_URL +'/admin/login',
                     data: {"_token": "{{ csrf_token() }}","email":entered_email,"password":entered_password},
                     success:function(data){
                         alert(data);
                         if(data==0){
                         alert('Invalid password!');
                         }else if(data==1){
                          window.location.href = APP_URL+ '/admin/login-success';
                         }else{

                         }                        
                  }
               });
          }
    });
});


</script>

<script type="text/javascript"> 
jQuery(document).ready(function(){
            
            jQuery("#admin-login").validate({
                rules: { 
                   email: {
                        required: true,
                        email: true
                    },                  
                   password: {
                        required: true
                    }
                   
                },
                messages: {
                    email: {
                        required: "Please Enter Email.",
                        email: "Please Enter Valid Email."
                    },
                    password: {
                        required: "Please Enter Password."
                    }
                                      
                }
            });            
         
        });

</script>

<style type="text/css">
.error{ color:red; }
</style>