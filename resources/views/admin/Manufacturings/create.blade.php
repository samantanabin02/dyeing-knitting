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
                          {{ Form::label('item_id', 'Item *', ['class' => '']) }}
                          <select name="item_id" id="item_id" class="form-control">
                              <option value="">Select Item</option>
                              @if($items!='' && count($items))
                               @foreach($items as $key=>$value)
                                <option value="{{ $key }}">{{ $value }}</option>
                               @endforeach
                              @endif
                          </select>
                      </div>
                    </div>
                  
                    <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('gst_applicable', 'GST Applicable *', ['class' => '']) }}
                        {{ Form::select('gst_applicable', ['1' => 'Yes', '2' => 'No'], 2, array( 'class' => 'form-control select2')) }}
                    </div>
                    </div>

                    <div class="col-md-6" id="gst_no_dv">
                        <div class="form-group">
                            {{ Form::label('gst_no', 'GST No *', ['class' => '']) }}
                            {{ Form::text('gst_no', null, array('class' => 'form-control', 'placeholder' => 'Enter GST No')) }}
                        </div>                 
                    </div>

                    <div class="col-md-6" id="gst_percentage_dv">
                        <div class="form-group">
                            {{ Form::label('gst_percentage', 'GST Percentage *', ['class' => '']) }}
                            {{ Form::text('gst_percentage', null, array('class' => 'form-control', 'placeholder' => 'Enter GST percentage')) }}
                        </div>                 
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('supply_type', 'Supply Type', ['class' => '']) }}
                            {{ Form::text('supply_type', null, array('class' => 'form-control', 'placeholder' => 'Enter Supply Type')) }}
                        </div>                 
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <div id="append_div">
                             </div>
                        </div>               
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                              <input type="button" class="btn btn-info" id="quantuty_add" value="Add Quantity">
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
                $("#quantuty_add").click(function(){
                  i++;
                  var append_html='<div class="row" id="deletedv_'+i+'"><div class="col-md-2"><label>Quantity:</label><input type="text" name="quantity['+i+']" id="quantity_'+i+'" class="form-control" value="" placeholder="Enter Quantity"></div><div class="col-md-2"><label>Unit:</label><input type="text" name="unit['+i+']" id="unit_'+i+'" class="form-control" value="" placeholder="Enter Unit"></div><div class="col-md-2"><label>Rate:</label><input type="text" name="rate['+i+']" id="rate_'+i+'" class="form-control" value="" placeholder="Enter Rate"></div><div class="col-md-2"><label>Amount:</label><input type="text" name="amount['+i+']" id="amount_'+i+'" class="form-control" value="" placeholder="Enter Amount"></div><div class="col-md-2"><label style="display:block;">&nbsp;</label><input type="button" id="deletebtn_'+i+'" class="form-control btn btn-danger delete_quantity_button" value="Delete"></div></div></br>';
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