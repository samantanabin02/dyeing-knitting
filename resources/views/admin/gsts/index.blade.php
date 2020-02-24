@extends('admin.layouts.AdminPanel')
@section('title')
  Gst Show
@endsection
@section('content')
<section class="content-header">
  <h1>Gst List </h1>
</section>
<section class="content">
  <div class="row">
    <div class="col-xs-12 clearfix">
      <div class=" row form-group">
        <div class="col-md-2 col-xs-6 mrgb" style="float:right;">
          <a href="{{ route('gsts.create') }}" class="form-control btn btn-info">Create New</a>
        </div>
      </div>
      @if (count($errors) > 0)
      <div class = "alert alert-danger" style="text-transform: uppercase;">
        <ul>
          @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
      @endif
      <?php if (!empty(session('msg'))) {?>
        <div class="alert <?php echo session('msg_class'); ?>">
          <?php echo session('msg'); ?>
        </div>
      <?php }?>
      <div class="box">
        <div class="box-body table-responsive">
          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <th width="3%">{{ Form::checkbox('multi_check',null,null, array('id'=>'multi_check')) }}</th>
                <th>Gst Name</th>
                <th>Gst Desc</th>
                <th>Gst Rate</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              if (!empty($datums)) {
                  foreach ($datums as $data) {
                      ?>
                    <tr>
                      <td>{{ Form::checkbox('single_check',$data->id,null, array('id'=>'single_check','class'=>'single_check')) }}</td>
                      <td>{{ $data->name }}</td>
                      <td>{{ $data->desc }}</td>
                      <td>{{ $data->rate }}</td>
                      <td>
                        <a style="margin-right: 10px; font-size: 16px;" href="{{ route('gsts.edit',  $data->id) }}" title="Edit">
                           <i class="fa fa-edit"></i>
                        </a>
                        <a style="font-size: 16px;" href="#myModal" data-toggle="modal"  title="Delete">
                          <i class="fa fa-trash"> </i>
                        </a>
                        {{ Form::open(['route' => ['gsts.destroy', $data->id], 'class' => 'form-horizontal', 'id' => "delete-form" ]) }}
                        {{ Form::hidden('_method', 'DELETE') }}
                        {{ Form::close() }}
                      </td>
                    </tr>
                  <?php }}?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
    <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <p>
          Are you sure to delete this item?
          <button type="button" style="float:right; margin-left:10px;"class="btn btn-default" data-dismiss="modal">No</button>
          <button type="button"  style="float:right;" onclick="event.preventDefault();document.getElementById('delete-form').submit();" class="btn btn-default" data-dismiss="modal">Yes</button>
          </p>
        </div>
      </div>
    </div>
    </div>
    <div class="modal fade" id="multi_delete" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <p id="mltdltchkp">
          Please select any item to delete.<button type="button" style="float:right;"class="btn btn-default" data-dismiss="modal">Ok</button>
          </p>
          <p id="mltdltp">
          Are you sure to delete these item?
          <button type="button" style="float:right; margin-left:10px;"class="btn btn-default" data-dismiss="modal">No</button>
          <button type="button"  style="float:right;" onclick="event.preventDefault();document.getElementById('multi_delete_form').submit();" class="btn btn-default" data-dismiss="modal">Yes</button>
          </p>
        </div>
      </div>
    </div>
  </div>
  <style type="text/css">
    .mrgb{
       margin-bottom: 5px;
    }
  </style>
  <script type="text/javascript">
    var allVals = [];
    jQuery(document).ready(function(){
      jQuery('#multi_check').click(function(){
        if (jQuery("#multi_check").is(':checked')) {
          jQuery("input[type=checkbox]").each(function () {
            jQuery(this).attr("checked", true);
          });
        } else {
          jQuery("input[type=checkbox]").each(function () {
            jQuery(this).attr("checked", false);
          });
        }
      });
      jQuery('#multi_delete_btn').click(function(){
        jQuery("input:checkbox[name=single_check]:checked").each(function(){
          allVals.push(jQuery(this).val());
        });
        jQuery('#deletable_ids').val(allVals);
          if(allVals==''){
            jQuery('#mltdltchkp').show();
            jQuery('#mltdltp').hide();
          }else{
            jQuery('#mltdltchkp').hide();
            jQuery('#mltdltp').show();
          }
      });
    });
</script>
  @endsection
