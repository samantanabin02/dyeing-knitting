@extends('admin.layouts.AdminPanel')
@section('title')
  Item Add
@endsection
@section('content')
   {{ Html::style('resources/views/admin/assets/bower_components/select2/dist/css/select2.min.css') }}
   {{ Html::style('resources/views/admin/assets/bower_components/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }}
    <section class="content">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Create Purcahsed Item <small class="text-red">* Fields are mendatory </small></h3>
          <div class="box-tools pull-right">
            <a href="{{ route('purchase.index') }}" class="btn-sm"><i class="fa fa-list-ul fa-add-edit-delete-btn"></i></a>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
      <div class="row">
      {{ Form::open(['route' => ['purchase.store'], 'method' => 'POST', 'files' => true, 'class' => '', 'id' => 'form-addedit','enctype' => 'multipart/form-data']) }}
                <div class="col-md-12">
                    <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('company', 'Company*', ['class' => '']) }}
                        <select name="purchase_company_id" id="purchase_company_id" class="form-control">
                            <option value="">Select Company</option>
                            @if($companies!='' && count($companies))
                             @foreach($companies as $companiesSingle)
                              <option value="{{ $companiesSingle->company_id }}">{{ $companiesSingle->company_name }}</option>
                             @endforeach
                            @endif
                        </select>
                    </div>
                    </div>
                    <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('item', 'Item*', ['class' => '']) }}
                        <select name="item_id" id="item_id" class="form-control">
                            <option value="">Select Item</option>
                            @if($item!='' && count($item))
                             @foreach($item as $item_type)
                              <option value="{{ $item_type->id }}">{{ $item_type->item_name }}</option>
                             @endforeach
                            @endif
                        </select>
                    </div>
                    </div>
                    <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('transfer company', 'Transfer Company*', ['class' => '']) }}
                        <select name="material_transfer_company_id" id="material_transfer_company_id" class="form-control">
                            <option value="">Select Transfer Company</option>
                            @if($companies!='' && count($companies))
                             @foreach($companies as $companiesSingle)
                              <option value="{{ $companiesSingle->company_id }}">{{ $companiesSingle->company_name }}</option>
                             @endforeach
                            @endif
                        </select>
                    </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('purchase date', 'Purchase Date *', ['class' => '']) }}
                            {{ Form::text('purchased_date', null, array('id' => 'datepicker', 'class' => 'form-control', 'placeholder' => 'Enter unit')) }}
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <div id="append_div"></div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                              <input type="button" class="btn btn-info" id="quantuty_add" value="Add Quantity">
                        </div>
                    </div>
                    <div class="col-md-12" style="height:10px;"></div>
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
    $(function() {
          var i=0;
          $("#quantuty_add").click(function(){
          i++;
            var append_html='<div class="row" id="deletedv_'+i+'"><div class="col-md-2"><label>Quantity:</label><input type="text" name="quantity['+i+']" id="quantity_'+i+'" class="form-control" value="" placeholder="Enter Quantity"></div><div class="col-md-2"><label>Rate:</label><input type="text" name="rate['+i+']" id="rate_'+i+'" class="form-control" value="" placeholder="Enter Rate"></div><div class="col-md-2"><label>Amount:</label><input type="text" name="amount['+i+']" id="amount_'+i+'" class="form-control" value="" placeholder="Enter Amount"></div><div class="col-md-2"><label style="display:block;">&nbsp;</label><input type="button" id="deletebtn_'+i+'" class="form-control btn btn-danger delete_quantity_button" value="Delete"></div></div></br>';
            $('#append_div').append(append_html);
          });
          $(document).on('click', '.delete_quantity_button', function(){
            var quantity_id_data = $(this).attr("id");
            var quantity_id_array = quantity_id_data.split("_");
            var quantity_id = quantity_id_array[1];
            //alert(quantity_id);
            $("#deletedv_"+quantity_id).remove();
          });
    });
    $('#datepicker').datepicker({
      format: 'yyyy-mm-dd',
      autoclose: true
    });
    jQuery("#form-addedit").validate({
        rules: {
        purchase_company_id: {
        required: true
        },
        item_id: {
        required: true
        },
        material_transfer_company_id: {
        required: true
        },
        purchased_date: {
        required: true
        }
        },
        messages: {
        purchase_company_id: {
        required: "Select company."
        },
        item_id: {
        required: "Select Item."
        },
        material_transfer_company_id: {
        required: "Select transfer company."
        },
        purchased_date: {
        required: "Select purchase date."
        },
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
