<aside class="main-sidebar">
    <section class="sidebar">
      <div class="user-panel">
        <div class="pull-left image">
          <?php
          $admin_image_url=url('public/uploads/admin_image/admin_no_image.png');
          $admin_image=Auth::guard('web_admin')->user()->image;
          if($admin_image!=''){
          $image_url=url('public/uploads/admin_image/'.$admin_image);
          if (file_exists(public_path().'/uploads/admin_image/'.Auth::guard('web_admin')->user()->image)) {
          $admin_image_url=$image_url;
          }
          }
          ?> 
          {{ Html::image($admin_image_url, 'Admin Image', array('class' => 'img-circle')) }}
        </div>
        <div class="pull-left info">
          <p><?php echo Auth::guard('web_admin')->user()->name; ?></p>
        </div>
      </div>    
      <ul class="sidebar-menu" data-widget="tree">
        <li class="">
          <a href="{{ url('admin/dashboard') }}">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>           
          </a>        
        </li>    
        <li class="">
          <a href="{{ url('admin/site-settings') }}">
            <i class="fa fa-cogs" aria-hidden="true"></i>
            <span>Site Settings</span>
          </a>         
        </li> 
        <li class="">
          <a href="{{ route('items.index') }}">
            <i class="fa fa-bars"></i>
            <span>Item List</span>
          </a>         
        </li>
        <li class="">
          <a href="{{ route('users.index') }}">
            <i class="fa fa-users"></i>
            <span>Users List</span>
          </a>         
        </li>
      </ul>
    </section>
  </aside>