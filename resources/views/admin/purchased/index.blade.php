@extends('admin.layouts.AdminPanel')
@section('title')
  Item Show
@endsection
@section('content')
<section class="content-header">
  <h1>Purchased List </h1>
</section>
<section class="content">
  <div class="row">
    <div class="col-xs-12 clearfix">
      <div class=" row form-group">
        {{ Form::open(['route' => 'purchase-index', 'method' => 'post', 'class' => '', 'id' => 'form-search']) }}
            <div class="col-md-2 mrgb">
            {{ Form::text('search_key', request()->input('search_key'), ['id' => 'search_key', 'class' => 'form-control', 'placeholder' => 'Enter search key']) }}
            </div>
            <div class="col-md-2 mrgb">
            {{ Form::select('purchase_company', $companies , request()->input('purchase_company'), ['placeholder' => 'Select Purchase Company', 'id' => 'purchase_company', 'class' => 'form-control select2']) }}
            </div>
            <div class="col-md-2 mrgb">
            {{ Form::select('transfer_company', $companies , request()->input('transfer_company'), ['placeholder' => 'Select Transfer Company', 'id' => 'transfer_company', 'class' => 'form-control select2']) }}
            </div>
            <div class="col-md-1 col-xs-6 mrgb">
            {{ Form::button('Search', ['type' => 'submit', 'class' => 'form-control btn btn-primary btn-block-xs', 'id' => 'submit-btn'] )  }}
            </div>
            <div class="col-md-1 col-xs-6 mrgb">
            <a href="{{ route('purchase.index') }}" id="cancel-btn" class="form-control btn btn-warning btn-block-xs">Reset</a>
            </div>
        {{ Form::close() }}

        <div class="col-md-2 col-xs-6 mrgb" >
          {{ Form::open(['route' => ['purchase-delete'], 'method' => 'post', 'id' => 'multi_delete_form', 'class' => '']) }}
          {{ Form::hidden('deletable_ids','', array('id'=>'deletable_ids')) }}
          {{ Form::close() }} 
          <a href="#" data-toggle="modal" data-target="#multi_delete" id="multi_delete_btn" class="form-control btn btn-danger">Multiple Delete</a> 
        </div>
        <div class="col-md-1 col-xs-6 mrgb">
          {{ Form::open(['route' => ['purchase.export'], 'method' => 'post', 'id' => 'export_form', 'class' => '']) }}
          {{ Form::hidden('export_search_key',request()->input('search_key'), array('id'=>'export_search_key')) }}
          {{ Form::hidden('export_purchase_company',request()->input('purchase_company'), array('id'=>'export_purchase_company')) }}
          {{ Form::hidden('export_transfer_company',request()->input('transfer_company'), array('id'=>'export_transfer_company')) }}
          {{ Form::button('Export', ['type' => 'submit', 'class' => 'form-control btn btn-primary btn-block-xs', 'id' => 'export-submit-btn'] )  }}
          {{ Form::close() }} 
        </div>
        <div class="col-md-1 col-xs-6 mrgb">
          <a href="{{ route('purchase.create') }}" class="form-control btn btn-info" style="font-size: 12px !important;">Create New</a>
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
                <th>Invoice Challan No</th>
                <th>Invoice Date</th>
                <th>Purchase Company</th>
                <th>Transfer Company</th>
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
                      <td>{{ Form::checkbox('single_check',$data->purchase_id,null, array('id'=>'single_check','class'=>'single_check')) }}</td>
                      <td>{{ $data->invoice_challan_no }}</td>
                      <td>{{ $data->invoice_date }}</td>
                      <td>{{ (isset($companies[$data->purchase_company_id]))?$companies[$data->purchase_company_id]:'' }}</td>
                      <td>{{ (isset($companies[$data->material_transfer_company_id]))?$companies[$data->material_transfer_company_id]:'' }}</td>
                      <td>
                      <a style="margin-right: 10px; font-size: 16px;" href="{{ route('purchase.edit',  $data->purchase_id) }}" title="Edit">
                           <i class="fa fa-edit"></i>
                      </a>
                      <a style="font-size: 16px;" href="#myModal" data-toggle="modal"  title="Delete" data-id="{{ $data->purchase_id }}" class="single_delete">
                        <i class="fa fa-trash"> </i>
                      </a>
                      {{ Form::open(['route' => ['purchase.destroy', $data->purchase_id], 'class' => 'form-horizontal', 'id' => "delete-form-".$data->purchase_id ]) }}
                      {{ Form::hidden('_method', 'DELETE') }}
                      {{ Form::close() }}
                      </td>
                    </tr>
                  <?php }}?>
                </tbody>
              </table>
              <div style="float:right;">
                {!! $datums->links() !!}
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <input type="hidden" id="element_id" value="">
          <p>
          Are you sure to delete this purchase item?
          <button type="button" style="float:right; margin-left:10px;"class="btn btn-default" data-dismiss="modal">No</button>
          <button type="button"  style="float:right;" id="single_delete_button" class="btn btn-default" data-dismiss="modal">Yes</button>
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
          Please select any purchase item to delete.<button type="button" style="float:right;"class="btn btn-default" data-dismiss="modal">Ok</button>
          </p>
          <p id="mltdltp">
          Are you sure to delete these purchase item?
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
        var allVals = [];
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
      
      jQuery('.single_delete').click(function(){
        var element_id=$(this).data("id");
        $('#element_id').val(element_id);
      });

      jQuery('#single_delete_button').click(function(){
        var element_id=$('#element_id').val();
        $('#delete-form-'+element_id).submit();
      });

    });
</script>
  @endsection
