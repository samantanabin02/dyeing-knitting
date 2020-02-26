@extends('admin.layouts.AdminPanel')
@section('title')
	Manufacturing Edit
@endsection
@section('content')
	{{ Html::style('resources/views/admin/assets/bower_components/select2/dist/css/select2.min.css') }}
    <section class="content">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Update Manufacturing <small class="text-red">* Fields are mendatory </small></h3>
          <div class="box-tools pull-right">
          	<a href="{{ route('manufacturings.index') }}" class="btn-sm"><i class="fa fa-list-ul fa-add-edit-delete-btn"></i></a>
          </div>
        </div>
        <div class="box-body">
			<div class="row">
            {{ Form::model($data, ['url' => ['admin/manufacturings', $data->id], 'method' => 'PUT', 'files' => true, 'class' => '', 'id' => 'form-addedit','enctype' => 'multipart/form-data']) }}
                <div class="col-md-12">
                     
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('serial_no', 'Serial No *', ['class' => '']) }}
                            {{ Form::text('serial_no', null, array('class' => 'form-control', 'placeholder' => 'Enter serial no')) }}
                        </div>                 
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('entry_date', 'Entry Date', ['class' => '']) }}
                            {{ Form::text('entry_date', null, array('class' => 'form-control', 'placeholder' => 'Choose entry date')) }}
                        </div>                 
                    </div>
                    
                  
                    <div class="col-md-6">
                      <div class="form-group">
                          {{ Form::label('knitting_company', 'Knitting Company *', ['class' => '']) }}
                          {{ Form::select('knitting_company', $companies, null , array( 'class' => 'form-control select2' , 'placeholder' => 'Select Knitting Company')) }}
                      </div>
                    </div>

                    <?php 
                      $item_options='<option value="">Select Item</option>';
                        if($items!='' && count($items)){
                         foreach($items as $key=>$value){
                          $item_options.='<option value="'.$key.'">'.$value.'</option>';
                         }
                        }
                    ?>

                    <div class="col-md-12">
                        <div class="form-group">
                            <div id="append_div">
                                    <?php 
                                    $last_quantity_id=0;
                                    foreach($quantity_details as $quantity_details_row){
                                    ?>

                                     <div class="row" id="deletedv_<?php echo $quantity_details_row->id; ?>">

                                        <div class="col-md-2"><label>Item:</label>
                                            <select name="item[<?php echo $quantity_details_row->id; ?>]" id="item_<?php echo $quantity_details_row->id; ?>" class="form-control item">
                                                <option value="">Select Item</option>
                                                <?php   
                                                    if($items!='' && count($items)){
                                                     foreach($items as $key=>$value){
                                                     ?>
                                                      <option value="{{ $key }}" <?php if($key==$quantity_details_row->item_id){ echo 'selected';} ?> >{{ $value }}</option>
                                                     <?php
                                                     }
                                                    }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="col-md-2">
                                            <label>Quantity:</label>
                                                <input type="text" name="quantity[<?php echo $quantity_details_row->id; ?>]" id="quantity_<?php echo $quantity_details_row->id; ?>" class="form-control quantity" value="<?php echo $quantity_details_row->quantity; ?>" placeholder="Enter Quantity">
                                        </div>

                                        <div class="col-md-2">
                                            <label>Unit:</label>
                                            <input type="text" name="unit[<?php echo $quantity_details_row->id; ?>]" id="unit_<?php echo $quantity_details_row->id; ?>" class="form-control unit" value="<?php echo $quantity_details_row->unit; ?>" placeholder="Enter Unit">
                                        </div>
                                     
                                        <div class="col-md-2">
                                        <label>Rate:</label>
                                            <input type="text" name="rate[<?php echo $quantity_details_row->id; ?>]" id="rate_<?php echo $quantity_details_row->id; ?>" class="form-control rate" value="<?php echo $quantity_details_row->rate; ?>" placeholder="Enter Rate">
                                        </div>
                                        
                                        <div class="col-md-2">
                                        <label>Amount:</label>
                                            <input type="text" name="amount[<?php echo $quantity_details_row->id; ?>]" id="amount_<?php echo $quantity_details_row->id; ?>" class="form-control amount" value="<?php echo $quantity_details_row->amount; ?>" placeholder="Enter Amount">
                                        </div>

                                        <div class="col-md-2">
                                        <label style="display:block;">&nbsp;</label>
                                            <input type="button" id="deletebtn_<?php echo $quantity_details_row->id; ?>" class="form-control btn btn-danger delete_quantity_button" value="Delete">
                                        </div>

                                     </div>
                                     </br>
                                    <?php
                                    $last_quantity_id=$quantity_details_row->id;
                                    }
                                    ?>
                             </div>
                        </div>               
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                              <input type="button" class="btn btn-info" id="item_add" value="Add Knitting Item">
                        </div>               
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('tot_knit_quan', 'Total Knitting Quantity', ['class' => '']) }}
                            {{ Form::text('tot_knit_quan', null, array('class' => 'form-control')) }}
                        </div>                 
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('tot_knit_amount', 'Total Knitting Amount', ['class' => '']) }}
                            {{ Form::text('tot_knit_amount', null, array('class' => 'form-control')) }}
                        </div>                 
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                          {{ Form::label('dyeing_company', 'Dyeing Company *', ['class' => '']) }}
                          {{ Form::select('dyeing_company', $companies, null , array( 'class' => 'form-control select2' , 'placeholder' => 'Select Dyeing Company')) }}
                      </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <div id="dyeing_append_div">
                                    <?php 
                                    $dlast_quantity_id=0;
                                    foreach($dquantity_details as $dquantity_details_row){
                                    ?>
                                     <div class="row" id="ddeletedv_<?php echo $dquantity_details_row->id; ?>">

                                        <div class="col-md-2"><label>Item:</label>
                                            <select name="ditem[<?php echo $dquantity_details_row->id; ?>]" id="ditem_<?php echo $dquantity_details_row->id; ?>" class="form-control ditem">
                                                <option value="">Select Item</option>
                                                <?php   
                                                    if($items!='' && count($items)){
                                                     foreach($items as $key=>$value){
                                                     ?>
                                                      <option value="{{ $key }}" <?php if($key==$dquantity_details_row->item_id){ echo 'selected';} ?> >{{ $value }}</option>
                                                     <?php
                                                     }
                                                    }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="col-md-2">
                                            <label>Quantity:</label>
                                                <input type="text" name="dquantity[<?php echo $dquantity_details_row->id; ?>]" id="dquantity_<?php echo $dquantity_details_row->id; ?>" class="form-control dquantity" value="<?php echo $dquantity_details_row->quantity; ?>" placeholder="Enter Quantity">
                                        </div>

                                        <div class="col-md-2">
                                            <label>Unit:</label>
                                            <input type="text" name="dunit[<?php echo $dquantity_details_row->id; ?>]" id="dunit_<?php echo $dquantity_details_row->id; ?>" class="form-control dunit" value="<?php echo $dquantity_details_row->unit; ?>" placeholder="Enter Unit">
                                        </div>
                                     
                                        <div class="col-md-2">
                                        <label>Rate:</label>
                                            <input type="text" name="drate[<?php echo $dquantity_details_row->id; ?>]" id="drate_<?php echo $dquantity_details_row->id; ?>" class="form-control drate" value="<?php echo $dquantity_details_row->rate; ?>" placeholder="Enter Rate">
                                        </div>
                                        
                                        <div class="col-md-2">
                                        <label>Amount:</label>
                                            <input type="text" name="damount[<?php echo $dquantity_details_row->id; ?>]" id="damount_<?php echo $dquantity_details_row->id; ?>" class="form-control damount" value="<?php echo $dquantity_details_row->amount; ?>" placeholder="Enter Amount">
                                        </div>

                                        <div class="col-md-2">
                                        <label style="display:block;">&nbsp;</label>
                                            <input type="button" id="ddeletebtn_<?php echo $dquantity_details_row->id; ?>" class="form-control btn btn-danger ddelete_quantity_button" value="Delete">
                                        </div>

                                     </div>
                                     </br>
                                    <?php
                                    $dlast_quantity_id=$dquantity_details_row->id;
                                    }
                                    ?>
                             </div>
                        </div>               
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                              <input type="button" class="btn btn-info" id="dyeing_item_add" value="Add Dyeing Item">
                        </div>               
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('tot_dyeing_quan', 'Total Dyeing Quantity', ['class' => '']) }}
                            {{ Form::text('tot_dyeing_quan', null, array('class' => 'form-control')) }}
                        </div>                 
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('tot_dyeing_amount', 'Total Dyeing Amount', ['class' => '']) }}
                            {{ Form::text('tot_dyeing_amount', null, array('class' => 'form-control')) }}
                        </div>                 
                    </div>


                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('wastage_quantity', 'Wastage Quantity', ['class' => '']) }}
                            {{ Form::text('wastage_quantity', null, array('class' => 'form-control', 'placeholder' => 'Enter wastage quantity')) }}
                        </div>                 
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('wastage_amount', 'Wastage Amount', ['class' => '']) }}
                            {{ Form::text('wastage_amount', null, array('class' => 'form-control', 'placeholder' => 'Enter wastage amount')) }}
                        </div>                 
                    </div>
                   
                    
                    <div class="col-md-12" style="height:10px;">
                    </div>                 
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
            
            $('#entry_date').datepicker({
              format: 'yyyy-mm-dd',
              autoclose: true
            }); 

            $('body').on('keyup','.quantity', function() {
                var quantity_data = $(this).attr("id");  
                var quantity_array = quantity_data.split("_");
                var quantity_id = quantity_array[1];
                calculate_amount(quantity_id);
                total_quantity();
             });

             $('body').on('keyup','.rate', function() {
                var quantity_data = $(this).attr("id");  
                var quantity_array = quantity_data.split("_");
                var quantity_id = quantity_array[1];
                calculate_amount(quantity_id);
             });

             function calculate_amount(quantity_id){
                var quantity = $('#quantity_'+quantity_id).val();
                var rate = $('#rate_'+quantity_id).val();
                if(quantity && rate){
                var amount=parseInt(quantity)*parseInt(rate);
                $('#amount_'+quantity_id).val(amount);
                }else{
                $('#amount_'+quantity_id).val('');
                }
                total_amount();
             }

             $('body').on('keyup','.dquantity', function() {
                var quantity_data = $(this).attr("id");  
                var quantity_array = quantity_data.split("_");
                var quantity_id = quantity_array[1];
                calculate_damount(quantity_id);
                total_dquantity();
             });

             $('body').on('keyup','.drate', function() {
                var quantity_data = $(this).attr("id");  
                var quantity_array = quantity_data.split("_");
                var quantity_id = quantity_array[1];
                calculate_damount(quantity_id);
             });

             function calculate_damount(quantity_id){
                var quantity = $('#dquantity_'+quantity_id).val();
                var rate = $('#drate_'+quantity_id).val();
                if(quantity && rate){
                var amount=parseInt(quantity)*parseInt(rate);
                $('#damount_'+quantity_id).val(amount);
                }else{
                $('#damount_'+quantity_id).val('');
                }
                total_damount();
             }

             function total_quantity(){
                var sum = 0;
                $('.quantity').each(function(){
                    if($(this).val()){
                    sum += parseFloat($(this).val());
                    }
                });
                $('#tot_knit_quan').val(sum);
             }

             function total_amount(){
                var sum = 0;
                $('.amount').each(function(){
                    if($(this).val()){
                      sum += parseFloat($(this).val());
                    }
                });
                $('#tot_knit_amount').val(sum);
             }

             function total_dquantity(){
                var sum = 0;
                $('.dquantity').each(function(){
                    if($(this).val()){
                    sum += parseFloat($(this).val());
                    }
                });
                $('#tot_dyeing_quan').val(sum);
             }

             function total_damount(){
                var sum = 0;
                $('.damount').each(function(){
                    if($(this).val()){
                    sum += parseFloat($(this).val());
                    }
                });
                $('#tot_dyeing_amount').val(sum);
             }

            $(function() {  
                var i='{{ $last_quantity_id }}';
                var j='{{ $dlast_quantity_id }}';
                var item_option='<?php echo $item_options; ?>';
                $('body').on('click','#item_add', function() {
                  i++;
                  var append_html='<div class="row" id="deletedv_'+i+'"><div class="col-md-2"><label>Item:</label><select name="item['+i+']" id="item_'+i+'" class="form-control item">'+item_option+'</select></div><div class="col-md-2"><label>Quantity:</label><input type="text" name="quantity['+i+']" id="quantity_'+i+'" class="form-control quantity" value="" placeholder="Enter Quantity"></div><div class="col-md-2"><label>Unit:</label><input type="text" name="unit['+i+']" id="unit_'+i+'" class="form-control unit" value="" placeholder="Enter Unit"></div><div class="col-md-2"><label>Rate:</label><input type="text" name="rate['+i+']" id="rate_'+i+'" class="form-control rate" value="" placeholder="Enter Rate"></div><div class="col-md-2"><label>Amount:</label><input type="text" name="amount['+i+']" id="amount_'+i+'" class="form-control amount" value="" placeholder="Enter Amount"></div><div class="col-md-2"><label style="display:block;">&nbsp;</label><input type="button" id="deletebtn_'+i+'" class="form-control btn btn-danger delete_quantity_button" value="Delete"></div></div></br>';
                    $('#append_div').append(append_html);
                });

                $(document).on('click', '.delete_quantity_button', function(){
                   var quantity_id_data = $(this).attr("id");  
                   var quantity_id_array = quantity_id_data.split("_");
                   var quantity_id = quantity_id_array[1];
                   //alert(quantity_id);
                   $("#deletedv_"+quantity_id).remove();
                });


                $('body').on('click','#dyeing_item_add', function() {
                  j++;
                  var dyeing_append_html='<div class="row" id="ddeletedv_'+j+'"><div class="col-md-2"><label>Item:</label><select name="ditem['+j+']" id="ditem_'+j+'" class="form-control ditem">'+item_option+'</select></div><div class="col-md-2"><label>Quantity:</label><input type="text" name="dquantity['+j+']" id="dquantity_'+j+'" class="form-control dquantity" value="" placeholder="Enter Quantity"></div><div class="col-md-2"><label>Unit:</label><input type="text" name="dunit['+j+']" id="dunit_'+j+'" class="form-control dunit" value="" placeholder="Enter Unit"></div><div class="col-md-2"><label>Rate:</label><input type="text" name="drate['+j+']" id="drate_'+j+'" class="form-control drate" value="" placeholder="Enter Rate"></div><div class="col-md-2"><label>Amount:</label><input type="text" name="damount['+j+']" id="damount_'+j+'" class="form-control damount" value="" placeholder="Enter Amount"></div><div class="col-md-2"><label style="display:block;">&nbsp;</label><input type="button" id="ddeletebtn_'+j+'" class="form-control btn btn-danger ddelete_quantity_button" value="Delete"></div></div></br>';
                    $('#dyeing_append_div').append(dyeing_append_html);
                });

                $(document).on('click', '.ddelete_quantity_button', function(){
                   var dquantity_id_data = $(this).attr("id");  
                   var dquantity_id_array = dquantity_id_data.split("_");
                   var dquantity_id = dquantity_id_array[1];
                   //alert(quantity_id);
                   $("#ddeletedv_"+dquantity_id).remove();
                });

             });

            $('#datepicker').datepicker({
              format: 'yyyy-mm-dd',
              autoclose: true
            });

             $('body').on('change','#unit_type_id', function() {
                calculate_price();
             });

             $('body').on('keyup','#manufacturing_quantity', function() {
                calculate_price();
             });

             function calculate_price(){
                var unit_price = $('#unit_type_id option:selected').attr('pricetag');
                var manufacturing_quantity = $('#manufacturing_quantity').val();
                if(unit_price && manufacturing_quantity){
                var manufacturing_price=parseInt(unit_price)*parseInt(manufacturing_quantity);
                $('#manufacturing_price').val(manufacturing_price);
                }else{
                $('#manufacturing_price').val('');    
                }
             }
            jQuery("#form-addedit").validate({
                rules: {
                    manufacturing_name: {
                        required: true
                    },
                    manufacturing_type_id: {
                        required: true
                    },
                    units: {
                        required: true
                    },
                    gst_applicable: {
                        required: true
                    },
                    gst_no: {
                        required: true
                    },
                    gst_percentage: {
                        required: true
                    },
                    supply_type: {
                        required: true
                    }
                },
                messages: {
                    manufacturing_name: {
                        required: "Please enter manufacturing name."
                    },
                    manufacturing_type_id: {
                        required: "Please select manufacturing type."
                    },
                    units: {
                        required: "Please enter unit."
                    },
                    gst_applicable: {
                        required: "Please select gst applicable or not."
                    },
                    gst_no: {
                        required: "Please enter gst no."
                    },
                    gst_percentage: {
                        required: "Please enter gst percentage."
                    },
                    supply_type: {
                        required: "Please enter supply type."
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