@extends('admin.layouts.AdminPanel')

@section('title')
	Delivery Add
@endsection

@section('content')
   {{ Html::style('resources/views/admin/assets/bower_components/select2/dist/css/select2.min.css') }}
   {{ Html::style('resources/views/admin/assets/bower_components/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }} 
    <section class="content">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Create Delivery <small class="text-red">* Fields are mendatory </small></h3>
          <div class="box-tools pull-right">
          	<a href="{{ route('deliveries.index') }}" class="btn-sm"><i class="fa fa-list-ul fa-add-edit-delete-btn"></i></a>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
			<div class="row">
			{{ Form::open(['route' => ['deliveries.store'], 'method' => 'POST', 'files' => true, 'class' => '', 'id' => 'form-addedit','enctype' => 'multipart/form-data']) }}
                <div class="col-md-12">

                   <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('lot_no', 'Lot No *', ['class' => '']) }}
                            {{ Form::text('lot_no', null, array('class' => 'form-control', 'placeholder' => 'Enter lot no')) }}
                        </div>                 
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('entry_date', 'Entry Date', ['class' => '']) }}
                            {{ Form::text('entry_date', null, array('class' => 'form-control', 'placeholder' => 'Choose entry date')) }}
                        </div>                 
                    </div>

                    <div class="col-md-4">
                      <div class="form-group">
                          {{ Form::label('dyeing_company', 'Dyeing Company *', ['class' => '']) }}
                          {{ Form::select('dyeing_company', $companies, null , array( 'class' => 'form-control select2' , 'placeholder' => 'Select Dyeing Company')) }}
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group">
                          {{ Form::label('serial_no', 'Serial No *', ['class' => '']) }}
                          {{ Form::select('serial_no', $manufacturings, null , array( 'class' => 'form-control select2' , 'placeholder' => 'Select Serial No')) }}
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group">
                          {{ Form::label('knitting_company', 'Knitting Company ', ['class' => '']) }}
                          {{ Form::text('knitting_company', null, array('class' => 'form-control', 'placeholder' => '' , 'readonly'=>true)) }}
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
                            {{ Form::label('tot_gross_quantity', 'Total Gross Quantity', ['class' => '']) }}
                            {{ Form::text('tot_gross_quantity', 0, array('class' => 'form-control')) }}
                        </div>                 
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('tot_finish_quantity', 'Total Finish Amount', ['class' => '']) }}
                            {{ Form::text('tot_finish_quantity', 0, array('class' => 'form-control')) }}
                        </div>                 
                    </div>

                  
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('delivery_date', 'Delivery Date', ['class' => '']) }}
                            {{ Form::text('delivery_date', null, array('class' => 'form-control', 'placeholder' => 'Choose delivery date')) }}
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

               $('#entry_date,#delivery_date').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
               });  

               $('body').on('keyup','.grossquantity', function() {
                let quantity_data = $(this).attr("id");  
                let quantity_array = quantity_data.split("_");
                let quantity_id = quantity_array[1];
                total_gross_quantity();
               });

               function total_gross_quantity(){
                let sum = 0;
                $('.grossquantity').each(function(){
                    if($(this).val()){
                    sum += parseFloat($(this).val());
                    }
                });
                $('#tot_gross_quantity').val(sum);
               }

               $('body').on('keyup','.finishquantity', function() {
                let quantity_data = $(this).attr("id");  
                let quantity_array = quantity_data.split("_");
                let quantity_id = quantity_array[1];
                total_finish_quantity();
               });

               function total_finish_quantity(){
                let sum = 0;
                $('.finishquantity').each(function(){
                    if($(this).val()){
                    sum += parseFloat($(this).val());
                    }
                });
                $('#tot_finish_quantity').val(sum);
               }

              $(function() {  
                var i=0;
                var item_option='<?php echo $item_options; ?>';
                //alert(item_option);
                $('body').on('click','#item_add', function() {
                  i++;
                  var append_html='<div class="row" id="deletedv_'+i+'"><div class="col-md-2"><label>Item:</label><select name="item['+i+']" id="item_'+i+'" class="form-control item">'+item_option+'</select></div><div class="col-md-2"><label>Quantity One:</label><input type="text" name="quantityone['+i+']" id="quantityone_'+i+'" class="form-control quantityone" value="" placeholder="Enter Quantity One"></div><div class="col-md-2"><label>Quantity Two:</label><input type="text" name="quantitytwo['+i+']" id="quantitytwo_'+i+'" class="form-control quantitytwo" value="" placeholder="Enter Quantity Two"></div><div class="col-md-2"><label>Gross Quantity:</label><input type="text" name="grossquantity['+i+']" id="grossquantity_'+i+'" class="form-control grossquantity" value="" placeholder="Enter Gross Quantity"></div><div class="col-md-2"><label>Finish Quantity:</label><input type="text" name="finishquantity['+i+']" id="finishquantity_'+i+'" class="form-control finishquantity" value="" placeholder="Enter Finish Quantity"></div><div class="col-md-2"><label style="display:block;">&nbsp;</label><input type="button" id="deletebtn_'+i+'" class="form-control btn btn-danger delete_quantity_button" value="Delete"></div></div></br>';
                    $('#append_div').append(append_html);
                });

                $(document).on('click', '.delete_quantity_button', function(){
                   var quantity_id_data = $(this).attr("id");  
                   var quantity_id_array = quantity_id_data.split("_");
                   var quantity_id = quantity_id_array[1];
                   //alert(quantity_id);
                   $("#deletedv_"+quantity_id).remove();
                   total_gross_quantity();
                   total_finish_quantity();
                });

             });


      			 jQuery("#form-addedit").validate({
      				rules: {
      					    lot_no: {
                        required: true
                    },
                    entry_date: {
                        required: true
                    },
                    dyeing_company: {
                        required: true
                    },
                    serial_no: {
                         required: true
                    },
                    delivery_date: {
                        required: true
                    }
      				},
      				messages: {
                    lot_no: {
                        required: "Please enter serial no."
                    },
                    entry_date: {
                        required: "Please choose entry date."
                    },                    
                    dyeing_company: {
                        required: "Please select dyeing company."
                    },
                    serial_no: {
                         required: "Please select serial no."
                    },
                    delivery_date: {
                        required: "Please choose delivery date"
                    }
      				}
              
      			});
    });
    </script>
    <script>
        $(document).ready(function(){
            $("#serial_no").change(function(){
                var manufacturing_id=$(this).val();
				var link  = "{{ route('deliveries-get-knitting-company') }}";
				$.ajax({
					url : link,
					type : "POST",
					data : {
                        "_token": "{{ csrf_token() }}",
					    "manufacturing_id" : manufacturing_id
					},
					success: function(data) {
                        console.log(data);
                        $('#knitting_company').val(data);
                        
					}
				});
            });
       });    
    </script>
    <style type="text/css">
    .error{
     color: red;   
    }
    </style>
@endsection