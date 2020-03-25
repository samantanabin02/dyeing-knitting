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
                            {{ Form::label('Invoice Challan No', 'Invoice Challan No *', ['class' => '']) }}
                            {{ Form::text('invoice_challan_no', null, array('required'=>'required', 'class' => 'form-control', 'placeholder' => 'Enter Invoice Challan No')) }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('Invoice Date', 'Invoice Date *', ['class' => '']) }}
                            {{ Form::text('invoice_date', null, array('required'=>'required','id' => 'datepicker', 'class' => 'form-control', 'placeholder' => 'Select Invoice Date')) }}
                        </div>
                    </div>
                    <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('Company', 'Company*', ['class' => '']) }}
                        <select name="purchase_company_id" id="purchase_company_id" class="form-control" required="required">
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
                        {{ Form::label('Transferred Company', 'Transferred Company*', ['class' => '']) }}
                        <select name="material_transfer_company_id" id="material_transfer_company_id" class="form-control" required="required">
                            <option value="">Select Transferred Company</option>
                            @if($companies!='' && count($companies))
                             @foreach($companies as $companiesSingle)
                              <option value="{{ $companiesSingle->company_id }}">{{ $companiesSingle->company_name }}</option>
                             @endforeach
                            @endif
                        </select>
                    </div>
                    </div>
                    <div id="append_div">
                    <div id="deletedv_1" style="width:100%; float:left;">
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>Item*</label>
                            <select class="form-control" id="item_id_1" name="item_id[1]" required="required">
                              <option value="">Select Item</option>`;
                              <?php foreach ($item as $item_type) {?>
                                <option value="<?php echo $item_type->id ?>"><?php echo strtoupper($item_type->item_name) ?></option>';
                              <?php }?>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-2">
                        <div class="form-group">
                          <label>Unit*</label>
                          <select class="form-control" id="unit_id_1" name="unit_id[1]" required="required">
                              <option value="">Select Unit</option>;
                               <?php foreach ($unit as $unit_type) {?>
                               <option value="<?php echo $unit_type->unit_type_id ?>"><?php echo strtoupper($unit_type->unit_type_name) ?></option>;
                              <?php }?>
                          </select>
                        </div>
                        </div>
                        <div class="col-md-2">
                        <div class="form-group">
                            <label>Quantity*</label>
                            <input required="required" onKeyPress="calculateAmount(1)" onKeyDown="calculateAmount(1)" onKeyUp="calculateAmount(1)" class="form-control" id="quantity_1" name="quantity[1]" placeholder="Enter Quantity" type="text" value=""/>
                        </div>
                        </div>
                        <div class="col-md-2">
                        <div class="form-group">
                            <label>Rate*</label>
                            <input required="required" onKeyPress="calculateAmount(1)" onKeyDown="calculateAmount(1)" onKeyUp="calculateAmount(1)" class="form-control" id="rate_1" name="rate[1]" placeholder="Enter Rate" type="text" value=""/>
                        </div>
                        </div>
                        <div class="col-md-2">
                        <div class="form-group">
                            <label>Amount*</label>
                            <input required="required" class="form-control amtt" id="amount_1" name="amount[1]" placeholder="Enter Amount" type="text" value=""/>
                        </div>
                        </div>
                        <div class="col-md-1">
                          <input style="margin-top: 24px;" class="form-control btn btn-danger delete_quantity_button" type="button" value="No"/>
                        </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                              <input type="button" class="btn btn-info" id="quantuty_add" value="Add Quantity">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('Other Charges', 'Other Charges', ['class' => '']) }}
                            {{ Form::text('other_charges', null, array('id' => 'other_charges', 'class' => 'form-control', 'placeholder' => 'Enter Other Charges')) }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('GST Type', 'SGST Rate', ['class' => '']) }}
                            {{ Form::text('sgst_persentage', null, array('id' => 'sgst_persentage', 'class' => 'form-control', 'placeholder' => 'Enter SGST Rate')) }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('CGST Type', 'CGST Rate', ['class' => '']) }}
                            {{ Form::text('cgst_persentage', null, array('id' => 'cgst_persentage', 'class' => 'form-control', 'placeholder' => 'Enter CGST Rate')) }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('CGST Type', 'IGST Rate', ['class' => '']) }}
                            {{ Form::text('igst_persentage', null, array('id' => 'igst_persentage', 'class' => 'form-control', 'placeholder' => 'Enter IGST Rate')) }}
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            {{ Form::label('Total Amount', 'Total Amount', ['class' => '']) }}
                            {{ Form::text('igst_persentage', null, array('id' => 'total_amount','class' => 'form-control', 'disabled' => 'disabled', 'placeholder' => '')) }}
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
      var i=1;
      $("#quantuty_add").click(function(){
      i++;
        var append_html=`<div id="deletedv_`+i+`" style="width:100%; float:left;">
        <div class="col-md-3">
          <div class="form-group">
            <label>Item:</label>
            <select class="form-control" id="item_id_`+i+`" name="item_id[`+i+`]">
              <option value="">Select Item</option>`;
              <?php foreach ($item as $item_type) {?>
               append_html += '<option value="<?php echo $item_type->id ?>"><?php echo strtoupper($item_type->item_name) ?></option>';
              <?php }?>
            append_html += `</select>
          </div>
        </div>
        <div class="col-md-2">
        <div class="form-group">
          <label>Unit:</label>
          <select class="form-control" id="unit_id`+i+`" name="unit_id[`+i+`]">
              <option value="">Select Unit</option>`;
               <?php foreach ($unit as $unit_type) {?>
               append_html += '<option value="<?php echo $unit_type->unit_type_id ?>"><?php echo strtoupper($unit_type->unit_type_name) ?></option>';
              <?php }?>
            append_html += `</select>
        </div>
        </div>
        <div class="col-md-2">
        <div class="form-group">
            <label>Quantity:</label>
            <input onKeyPress="calculateAmount(`+i+`)" onKeyDown="calculateAmount(`+i+`)" onKeyUp="calculateAmount(`+i+`)" class="form-control" id="quantity_`+i+`" name="quantity[`+i+`]" placeholder="Enter Quantity" type="text" value=""/>
        </div>
        </div>
        <div class="col-md-2">
        <div class="form-group">
            <label>Rate:</label>
            <input onKeyPress="calculateAmount(`+i+`)" onKeyDown="calculateAmount(`+i+`)" onKeyUp="calculateAmount(`+i+`)" class="form-control" id="rate_`+i+`" name="rate[`+i+`]" placeholder="Enter Rate" type="text" value=""/>
        </div>
        </div>
        <div class="col-md-2">
        <div class="form-group">
            <label>Amount:</label>
            <input class="form-control amtt" id="amount_`+i+`" name="amount[`+i+`]" placeholder="Enter Amount" type="text" value=""/>
        </div>
        </div>
        <div class="col-md-1">
          <input style="margin-top: 24px;" class="form-control btn btn-danger delete_quantity_button" id="deletebtn_`+i+`" type="button" value="Delete"/>
        </div>
        </div>`;
        $('#append_div').append(append_html);
      });
      $(document).on('click', '.delete_quantity_button', function(){
        var quantity_id_data = $(this).attr("id");
        var quantity_id_array = quantity_id_data.split("_");
        var quantity_id = quantity_id_array[1];
        $("#deletedv_"+quantity_id).remove();
      });
});
$('#datepicker').datepicker({
  format: 'yyyy-mm-dd',
  autoclose: true
});
jQuery("#form-addedit").validate({
    rules: {
      invoice_challan_no: {
      required: true
      },
      invoice_date: {
      required: true
      },
      purchase_company_id: {
      required: true
      },
      material_transfer_company_id: {
      required: true
      }
    },
    messages: {
    invoice_challan_no: {
    required: "Please Give A Invoice Challan No."
    },
    invoice_date: {
    required: "Please Select Invoice Date"
    },
    purchase_company_id: {
    required: "Please Select Company Name."
    },
    material_transfer_company_id: {
    required: "Please Select Transferred Company."
    },
    }
});
});
function calculateAmount(id){
var qty = $("#quantity_" + id).val();
var rat = $("#rate_" + id).val();
var amount = (qty*rat);
$("#amount_" + id).val(amount);
getTotalValue();
}
$( "#other_charges" ).keyup(function() {
getTotalValue();
});
$( "#sgst_persentage" ).keyup(function() {
getTotalValue();
});
$( "#cgst_persentage" ).keyup(function() {
getTotalValue();
});
$( "#igst_persentage" ).keyup(function() {
getTotalValue();
});
function getTotalValue(){
var total = 0;
var main_total = 0;
//Geting the Dynamic Field Value
$( ".amtt" ).each(function( index ) {
total += Number($( this ).val());
});
//Adding Other Charges
total +=  Number($('#other_charges').val());
console.log(total);
//SGST/CGST/IGST
sgst_persentage = (Number($('#sgst_persentage').val()) / total) * 100;
cgst_persentage = (Number($('#cgst_persentage').val()) / total) * 100;
igst_persentage = (Number($('#igst_persentage').val()) / total) * 100;
main_total = (total + sgst_persentage + cgst_persentage + igst_persentage);
//Display
$('#total_amount').val(Math.round(main_total,2));
}
</script>
<style type="text/css">
.error{
color: red;
}
</style>
@endsection
