<?php
$admin_image_url = url('public/uploads/admin_image/admin_no_image.png');
$admin_image     = Auth::guard('web_admin')->user()->image;
if ($admin_image != '') {
    $image_url = url('public/uploads/admin_image/' . $admin_image);
    if (file_exists(public_path() . '/uploads/admin_image/' . Auth::guard('web_admin')->user()->image)) {
        $admin_image_url = $image_url;
    }
}
?>
@extends('admin.layouts.AdminPanel')

@section('title')
    Change Profile
@endsection

@section('content')
   <!--<section class="content-header">
      <h1>
        Profile
        <small>Change Profile</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('admin/dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Change Profile</li>
      </ol>
    </section> -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Change Profile</h3>
            </div>
             {{ Form::open(array('url' => 'admin/change-profile', 'method' => 'post', 'role' => 'form', 'id' => 'change_profile', 'enctype' => 'multipart/form-data')) }}
              <div class="box-body">
                <div class="col-md-6">
                <div class="form-group">
                  {!! Form::label('name', 'Name : ', array()) !!}
                  <input type="text" name="name" id="name" class="form-control" placeholder="Enter Name" value="<?php echo $admin_data->name; ?>">
                </div>
                </div>
                <div class="col-md-6">
                <div class="form-group">
                {!! Form::label('profile_picture', 'Profile Picture : ', array()) !!}
                <input type="file" name="profile_picture" id="profile_picture" class="form-control">
                </div>
                <!--<div class="form-group">
                {{ Html::image($admin_image_url, 'Admin Image', array('class' => 'user-image','height'=>'100'))}}
                </div> -->
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
            jQuery("#change_profile").validate({
                rules: {
                    name: {
                        required: true
                    }

                },
                messages: {
                    name: {
                        required: "Please Enter Name."
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