@extends('admin.layouts.AdminPanel')
@section('title')
	Item Edit
@endsection
@section('content')
	{{ Html::style('resources/views/admin/assets/bower_components/select2/dist/css/select2.min.css') }}
      <section class="content">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Update Sales Item <small class="text-red">* Fields are mendatory </small></h3>
          <div class="box-tools pull-right">
          	<a href="{{ route('sales.index') }}" class="btn-sm"><i class="fa fa-list-ul fa-add-edit-delete-btn"></i></a>
          </div>
        </div>
        <div class="box-body">
			<div class="row">
            {{ Form::model($data, ['url' => ['admin/sales', $data->sales_id], 'method' => 'PUT', 'files' => true, 'class' => '', 'id' => 'form-addedit','enctype' => 'multipart/form-data']) }}
                <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('Invoice No', 'Invoice No *', ['class' => '']) }}
                            {{ Form::text('invoice_no', null, array('required'=>'required', 'class' => 'form-control', 'placeholder' => 'Enter Invoice No')) }}
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
                            {{ Form::label('Dispatch Doc', 'Dispatch Doc *', ['class' => '']) }}
                            {{ Form::text('despatch_doc', null, array('required'=>'required','id' => '', 'class' => 'form-control', 'placeholder' => 'Dispatch Doc')) }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('Challan No', 'Challan Doc *', ['class' => '']) }}
                            {{ Form::text('challan_no', null, array('required'=>'required','id' => '', 'class' => 'form-control', 'placeholder' => 'Challan No')) }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('Despatch Thought', 'Dispatch Thought *', ['class' => '']) }}
                            {{ Form::text('despatch_through', null, array('required'=>'required','id' => '', 'class' => 'form-control', 'placeholder' => 'Despatch Thought')) }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('Company', 'Company*', ['class' => '']) }}
                            <select name="company_id" id="company_id" class="form-control">
                                <option value="">Select Company</option>
                                @if($companies!='' && count($companies))
                                 @foreach($companies as $companiesSingle)
                                  <option value="{{ $companiesSingle->company_id }}" {{ ($companiesSingle->company_id == $data->company_id)?'selected':'' }}>{{ $companiesSingle->company_name }}</option>
                                 @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div id="append_div">
                    <?php 
                    $last_quantity_id = 0;
                    foreach($quantity_details as $quantity_details_row){
                    ?>
                    <div id="deletedv_<?php echo $quantity_details_row->id; ?>" style="width:100%; float:left;">

                    <div class="col-md-4">
                    <div class="form-group">
                    <label>Lot*</label>
                    <select class="form-control" id="lot_no_id_<?php echo $quantity_details_row->id; ?>" name="lot_no_id[<?php echo $quantity_details_row->id; ?>]" required="required">
                      <option value="">Select Lot</option>`;
                      <?php foreach($lot as $lot_item){ ?>
                        <option value="<?php echo $lot_item->id ?>" {{($lot_item->id == $quantity_details_row->lot_no_id)?'selected':'' }}><?php echo strtoupper($item_type->lot_no) ?></option>';
                      <?php }?>
                    </select>
                    </div>
                    </div>

                    <div class="col-md-4">
                    <div class="form-group">
                    <label>Item*</label>
                    <select class="form-control" id="item_id_<?php echo $quantity_details_row->id; ?>" name="item_id[<?php echo $quantity_details_row->id; ?>]" required="required">
                      <option value="">Select Item</option>`;
                      <?php foreach($item as $item_type){ ?>
                        <option value="<?php echo $item_type->id ?>" {{($item_type->id == $quantity_details_row->item_id)?'selected':'' }}><?php echo strtoupper($item_type->item_name) ?></option>';
                      <?php }?>
                    </select>
                    </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>HSN Code*</label>
                            <input required="required" class="form-control" id="hsn_code_<?php echo $quantity_details_row->id; ?>" name="hsn_code[<?php echo $quantity_details_row->id; ?>]" placeholder="HSN Code" type="text" value="<?php echo $quantity_details_row->hsn_code; ?>"/>
                        </div>
                        </div>


                    <div class="col-md-2">
                    <label>Quantity*:</label>
                        <input type="text" required="required" name="quantity[<?php echo $quantity_details_row->id; ?>]" id="quantity_<?php echo $quantity_details_row->id; ?>" class="form-control" value="<?php echo $quantity_details_row->quantity; ?>" placeholder="Enter Quantity">
                    </div>

                    <div class="col-md-2">
                    <label>Rate*:</label>
                    <input type="text" required="required" name="rate[<?php echo $quantity_details_row->id; ?>]" id="rate_<?php echo $quantity_details_row->id; ?>" class="form-control" value="<?php echo $quantity_details_row->rate; ?>" placeholder="Enter Rate">
                    </div>

                    <div class="col-md-2">
                    <div class="form-group">
                    <label>Unit*</label>
                    <select class="form-control" id="unit_id_<?php echo $quantity_details_row->id; ?>" name="unit_id[<?php echo $quantity_details_row->id; ?>]" required="required">
                      <option value="">Select Unit</option>;
                       <?php foreach($unit as $unit_type){ ?>
                       <option value="<?php echo $unit_type->unit_type_id ?>" {{($unit_type->unit_type_id == $quantity_details_row->unit_id)?'selected':'' }}><?php echo strtoupper($unit_type->unit_type_name) ?></option>;
                      <?php }?>
                    </select>
                    </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                        <label>Discount</label>
                        <input class="form-control" id="disc_persentage_<?php echo $quantity_details_row->id; ?>" name="disc_persentage[<?php echo $quantity_details_row->id; ?>]" placeholder="Discount Persentage" type="text" value="<?php echo $quantity_details_row->disc_persentage; ?>"/>
                        </div>
                    </div>

                    <div class="col-md-2">
                    <label>Amount*:</label>
                    <input type="text" required="required" name="amount[<?php echo $quantity_details_row->id; ?>]" id="amount_<?php echo $quantity_details_row->id; ?>" class="form-control" value="<?php echo $quantity_details_row->amount; ?>" placeholder="Enter Amount">
                    </div>

                    <div class="col-md-2">
                    <label style="display:block;">&nbsp;</label>
                    <input type="button" id="deletebtn_<?php echo $quantity_details_row->id; ?>" class="form-control btn btn-danger delete_quantity_button" value="Delete">
                    </div>

                    </div>
                    </br>
                    <?php
                    $last_quantity_id = $quantity_details_row->id;
                    }
                    ?>             
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                              <input type="button" class="btn btn-info" id="quantuty_add" value="Add Quantity">
                        </div>               
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('Other Charges', 'Other Charges', ['class' => '']) }}
                            {{ Form::text('other_charges', null, array('class' => 'form-control', 'placeholder' => 'Enter Other Charges')) }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('GST Type', 'SGST Rate', ['class' => '']) }}
                            {{ Form::text('sgst_persentage', null, array('class' => 'form-control', 'placeholder' => 'Enter SGST Rate')) }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('CGST Type', 'CGST Rate', ['class' => '']) }}
                            {{ Form::text('cgst_persentage', null, array('class' => 'form-control', 'placeholder' => 'Enter CGST Rate')) }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('CGST Type', 'IGST Rate', ['class' => '']) }}
                            {{ Form::text('igst_persentage', null, array('class' => 'form-control', 'placeholder' => 'Enter IGST Rate')) }}
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
        <div class="col-md-4">
          <div class="form-group">
            <label>Lot*</label>
            <select required="required" class="form-control" id="lot_no_id_`+i+`" name="lot_no_id[`+i+`]">
              <option value="">Select Lot</option>`;
              <?php foreach($lot as $lot_item){ ?>
               append_html += '<option value="<?php echo $lot_item->id ?>"><?php echo strtoupper($lot_item->lot_no) ?></option>';
              <?php }?>
            append_html += `</select>
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group">
            <label>Item*</label>
            <select required="required" class="form-control" id="item_id_`+i+`" name="item_id[`+i+`]">
              <option value="">Select Item</option>`;
              <?php foreach($item as $item_type){ ?>
               append_html += '<option value="<?php echo $item_type->id ?>"><?php echo strtoupper($item_type->item_name) ?></option>';
              <?php }?>
            append_html += `</select>
          </div>
        </div>

        <div class="col-md-4">
        <div class="form-group">
            <label>HSN Code*</label>
            <input required="required" class="form-control" id="hsn_code_`+i+`" name="hsn_code[`+i+`]" placeholder="HSN Code" type="text" value=""/>
        </div>
        </div>

        <div class="col-md-2">
        <div class="form-group">
            <label>Quantity*</label>
            <input required="required" onKeyPress="calculateAmount(`+i+`)" onKeyDown="calculateAmount(`+i+`)" onKeyUp="calculateAmount(`+i+`)" class="form-control" id="quantity_`+i+`" name="quantity[`+i+`]" placeholder="Enter Quantity" type="text" value=""/>
        </div>
        </div>

        <div class="col-md-2">
        <div class="form-group">
            <label>Rate*</label>
            <input required="required" onKeyPress="calculateAmount(`+i+`)" onKeyDown="calculateAmount(`+i+`)" onKeyUp="calculateAmount(`+i+`)" class="form-control" id="rate_`+i+`" name="rate[`+i+`]" placeholder="Enter Rate" type="text" value=""/>
        </div>
        </div>

        <div class="col-md-2">
        <div class="form-group">
          <label>Unit*</label>
          <select class="form-control" id="unit_id`+i+`" name="unit_id[`+i+`]" required="required">
              <option value="">Select Unit</option>`;
               <?php foreach($unit as $unit_type){ ?>
               append_html += '<option value="<?php echo $unit_type->unit_type_id ?>"><?php echo strtoupper($unit_type->unit_type_name) ?></option>';
              <?php }?>
            append_html += `</select>
        </div>
        </div>

        <div class="col-md-2">
        <div class="form-group">
            <label>Discount:</label>
            <input class="form-control" id="disc_persentage_`+i+`" name="disc_persentage[`+i+`]" placeholder="Discount Persentage" type="text" value=""/>
        </div>
        </div>

        <div class="col-md-2">
        <div class="form-group">
            <label>Amount*</label>
            <input required="required" class="form-control" id="amount_`+i+`" name="amount[`+i+`]" placeholder="Enter Amount" type="text" value=""/>
        </div>
        </div>

        <div class="col-md-2">
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
    invoice_no: {
    required: true
    },
    invoice_date: {
    required: true
    },
    despatch_doc: {
    required: true
    },
    company_id: {
    required: true
    },
    despatch_through: {
    required: true
    }
    },
    messages: {
    invoice_no: {
    required: "Please Give A Invoice No."
    },
    invoice_date: {
    required: "Please Select Invoice Date"
    },
    despatch_doc: {
    required: "Please Give A Despatch Doc."
    },
    company_id: {
    required: "Please Select Company."
    },
    despatch_through: {
    required: "Please Give A Despatch Through."
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