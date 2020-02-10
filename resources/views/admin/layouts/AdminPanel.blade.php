<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin | @yield('title')</title>
  <link rel="icon" href="{{ asset('assets/frontend/img/favicon.ico') }}">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  {!! Html::style('assets/admin/bower_components/bootstrap/dist/css/bootstrap.min.css') !!}
  <!-- Font Awesome -->
  {!! Html::style('assets/admin/bower_components/font-awesome/css/font-awesome.min.css') !!}
  <!-- Ionicons -->
  {!! Html::style('assets/admin/bower_components/Ionicons/css/ionicons.min.css') !!}
  <!-- Theme style -->
  {!! Html::style('assets/admin/dist/css/AdminLTE.min.css') !!}
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  {!! Html::style('assets/admin/dist/css/skins/_all-skins.min.css') !!}
  <!-- Morris chart -->
  {!! Html::style('assets/admin/bower_components/morris.js/morris.css') !!}
  <!-- jvectormap -->
  {!! Html::style('assets/admin/bower_components/jvectormap/jquery-jvectormap.css') !!}
  <!-- Date Picker -->
  {!! Html::style('assets/admin/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') !!}
  <!-- Daterange picker -->
  {!! Html::style('assets/admin/bower_components/bootstrap-daterangepicker/daterangepicker.css') !!}
  <!-- bootstrap wysihtml5 - text editor -->
  {!! Html::style('assets/admin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') !!}
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

  <!-- jQuery 3 -->
  {!! Html::script('assets/admin/bower_components/jquery/dist/jquery.min.js') !!}
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
        @include('admin.common.Header')
        @include('admin.common.Sidebar')
        <div class="content-wrapper">
            <div class="row">
                <div class="col-md-12">
                    <div class="box-body">
                      @include('admin.common.Messages')
                    </div>
                </div>
            </div>
            @yield('content')
        </div>
        @include('admin.common.Footer')
        @include('admin.common.SidebarControl')
        <div class="control-sidebar-bg"></div>
</div>
<!-- jQuery UI 1.11.4 -->
{!! Html::script('assets/admin/bower_components/jquery-ui/jquery-ui.min.js') !!}
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
{!! Html::script('assets/admin/bower_components/bootstrap/dist/js/bootstrap.min.js') !!}
<!-- Morris.js charts -->
{!! Html::script('assets/admin/bower_components/raphael/raphael.min.js') !!}
{!! Html::script('assets/admin/bower_components/morris.js/morris.min.js') !!}
<!-- Sparkline -->
{!! Html::script('assets/admin/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js') !!}
<!-- jvectormap -->
{!! Html::script('assets/admin/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') !!}
{!! Html::script('assets/admin/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') !!}
<!-- jQuery Knob Chart -->
{!! Html::script('assets/admin/bower_components/jquery-knob/dist/jquery.knob.min.js') !!}
<!-- daterangepicker -->
{!! Html::script('assets/admin/bower_components/moment/min/moment.min.js') !!}
{!! Html::script('assets/admin/bower_components/bootstrap-daterangepicker/daterangepicker.js') !!}
<!-- datepicker -->
{!! Html::script('assets/admin/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') !!}
<!-- Bootstrap WYSIHTML5 -->
{!! Html::script('assets/admin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') !!}
<!-- Slimscroll -->
{!! Html::script('assets/admin/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') !!}
<!-- FastClick -->
{!! Html::script('assets/admin/bower_components/fastclick/lib/fastclick.js') !!}
<!-- AdminLTE App -->
{!! Html::script('assets/admin/dist/js/adminlte.min.js') !!}
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
{!! Html::script('assets/admin/dist/js/pages/dashboard.js') !!}
<!-- AdminLTE for demo purposes -->
{!! Html::script('assets/admin/dist/js/demo.js') !!}
</body>
</html>
