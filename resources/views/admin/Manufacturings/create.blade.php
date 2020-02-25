@extends('admin.layouts.AdminPanel')

@section('title')
	Manufacturing Add
@endsection

@section('content')
   {{ Html::style('resources/views/admin/assets/bower_components/select2/dist/css/select2.min.css') }}
   {{ Html::style('resources/views/admin/assets/bower_components/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }} 
    <section class="content">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Create Manufacturing <small class="text-red">* Fields are mendatory </small></h3>
          <div class="box-tools pull-right">
          	<a href="{{ route('manufacturings.index') }}" class="btn-sm"><i class="fa fa-list-ul fa-add-edit-delete-btn"></i></a>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
			<div class="row">
			{{ Form::open(['route' => ['manufacturings.store'], 'method' => 'POST', 'files' => true, 'class' => '', 'id' => 'form-addedit','enctype' => 'multipart/form-data']) }}
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

                    <div class="col-md-6">
                      <div class="form-group">
                          {{ Form::label('dyeing_company', 'Dyeing Company *', ['class' => '']) }}
                          {{ Form::select('dyeing_company', $companies, null , array( 'class' => 'form-control select2' , 'placeholder' => 'Select Dyeing Company')) }}
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
                             </div>
                        </div>               
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                              <input type="button" class="btn btn-info" id="item_add" value="Add Item">
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
             $(function() {  
                var i=0;
                var item_option='<?php echo $item_options; ?>';
                //alert(item_option);
                $('body').on('click','#item_add', function() {
                  i++;
                  var append_html='<div class="row" id="deletedv_'+i+'"><div class="col-md-2"><label>Item:</label><select name="item['+i+']" id="item_'+i+'" class="form-control">'+item_option+'</select></div><div class="col-md-2"><label>Quantity:</label><input type="text" name="quantity['+i+']" id="quantity_'+i+'" class="form-control" value="" placeholder="Enter Quantity"></div><div class="col-md-2"><label>Unit:</label><input type="text" name="unit['+i+']" id="unit_'+i+'" class="form-control" value="" placeholder="Enter Unit"></div><div class="col-md-2"><label>Rate:</label><input type="text" name="rate['+i+']" id="rate_'+i+'" class="form-control" value="" placeholder="Enter Rate"></div><div class="col-md-2"><label>Amount:</label><input type="text" name="amount['+i+']" id="amount_'+i+'" class="form-control" value="" placeholder="Enter Amount"></div><div class="col-md-2"><label style="display:block;">&nbsp;</label><input type="button" id="deletebtn_'+i+'" class="form-control btn btn-danger delete_quantity_button" value="Delete"></div></div></br>';
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

      			 jQuery("#form-addedit").validate({
      				rules: {
      					    serial_no: {
                        required: true
                    },
                    entry_date: {
                        required: false
                    },
                    knitting_company: {
                        required: true
                    },
                    dyeing_company: {
                        required: true
                    }
      				},
      				messages: {
                    serial_no: {
                        required: "Please enter serial no."
                    },
                    entry_date: {
                        required: "Please choose entry date."
                    },
                    knitting_company: {
                        required: "Please select knitting company."
                    },
                    dyeing_company: {
                        required: "Please select dyeing company."
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