<header class="main-header">
<!-- Logo -->
<a href="" class="logo">
<!-- mini logo for sidebar mini 50x50 pixels -->
<span class="logo-mini"><b>Adm</b></span>
<!-- logo for regular state and mobile devices -->
<span class="logo-lg"><b>Admin</b></span>
</a>
<!-- Header Navbar: style can be found in header.less -->
<nav class="navbar navbar-static-top">
<!-- Sidebar toggle button-->
<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
<span class="sr-only">Toggle navigation</span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
</a>
<div class="navbar-custom-menu">
<ul class="nav navbar-nav">
<?php
$user_noti = App\Admin::find(1);
$a         = 0;
foreach ($user_noti->unreadNotifications as $notification) {
if ($notification->receiver_seen == '0') {
$a = $a + 1;
}
$visibi = '';
}
if ($a == 0 || $a == '0') {
$a      = 'no';
$visibi = "style=visibility:hidden";
}
?>
<!-- <li class="dropdown notifications-menu">
<a href="#" class="dropdown-toggle" data-toggle="dropdown">
  <i class="fa fa-bell-o"></i>
  <span class="label label-warning" {{$visibi}}> {{$a}}</span>
</a>
<ul class="dropdown-menu">
  <li class="header">You have {{$a}} notifications</li>
  <li>
    <ul class="menu">
      @foreach ($user_noti->notifications  as $notification)
      <li>
        <a href="<?php print_r(url('admin/dashboard'));?>">
          <i class="fa fa-users text-aqua"></i><?php print_r($notification->data['message'])?>
        </a>
      </li>
       @endforeach
    </ul>
  </li>
  <li class="footer"><a href="#">View all</a></li>
</ul>
</li> -->
<li class="dropdown user user-menu">
<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" >
<?php
$admin_image_url = url('public/uploads/admin_image/admin_no_image.png');
$admin_image = Auth::guard('web_admin')->user()->image;
if ($admin_image != '') {
    $image_url = url('public/uploads/admin_image/' . $admin_image);
    if (file_exists(public_path() . '/uploads/admin_image/' . Auth::guard('web_admin')->user()->image)) {
        $admin_image_url = $image_url;
    }
}
?>
{{ Html::image($admin_image_url, 'Admin Image', array('class' => 'user-image')) }}
<span class="hidden-xs">{{ Auth::guard('web_admin')->user()->name }}</span>
</a>
<ul class="dropdown-menu">
  <li class="user-header">
    {{ Html::image($admin_image_url, 'Admin Image', array('class' => 'img-circle')) }}
    <p>
      {{ Auth::guard('web_admin')->user()->name }}
      <!-- <small>Member since Nov. 2012</small> -->
    </p>
  </li>
  <li class="user-body">
    <div class="row">
      <div class="col-xs-12 text-center">
        <a href="{{ url('admin/change-profile') }}">Update Profile</a>
      </div>
      <!-- <div class="col-xs-4 text-center">
        <a href="#">Sales</a>
      </div>
      <div class="col-xs-4 text-center">
        <a href="#">Friends</a>
      </div> -->
    </div>
  </li>
  @if (Auth::guard('web_admin')->guest())
  <li class="user-footer">
    <div class="pull-left">
      <a href="{{ route('admin-register') }}" class="btn btn-default btn-flat">Register</a>
    </div>
    <div class="pull-right">
       <a href="{{ route('admin-login') }}" class="btn btn-default btn-flat">Login</a>
    </div>
  </li>
    @else
  <li class="user-footer">
    <div class="pull-left">
      <a href="{{ url('admin/change-password') }}" class="btn btn-default btn-flat">Change Password</a>
    </div>
    <div class="pull-right">
      <a
        href="{{ route('admin-logout') }}"
        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
        class="btn btn-default btn-flat">
        Logout
      </a>
        <form id="logout-form" action="{{ route('admin-logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
    </div>
  </li>
    @endif
</ul>
</li>
<!-- Control Sidebar Toggle Button -->
<!-- <li>
<a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
</li> -->
</ul>
</div>
</nav>
</header>