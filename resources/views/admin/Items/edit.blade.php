@extends('admin.layouts.AdminPanel')
@section('title')
	Item Edit
@endsection
@section('content')
	{{ Html::style('resources/views/admin/assets/bower_components/select2/dist/css/select2.min.css') }}
  
    <section class="content">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Update Item <small class="text-red">* Fields are mendatory </small></h3>
          <div class="box-tools pull-right">
          	<a href="{{ route('items.index') }}" class="btn-sm"><i class="fa fa-list-ul fa-add-edit-delete-btn"></i></a>
          </div>
        </div>
        <div class="box-body">
			<div class="row">
            {{ Form::model($data, ['url' => ['admin/items', $data->id], 'method' => 'PUT', 'files' => true, 'class' => '', 'id' => 'form-addedit','enctype' => 'multipart/form-data']) }}
                <div class="col-md-12">
                     <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('item_name', 'Item Name *', ['class' => '']) }}
                        {{ Form::text('item_name', null, array('class' => 'form-control', 'placeholder' => 'Enter item name')) }}
                        @if ($errors->has('item_name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('item_name') }}</strong>
                            </span>
                        @endif
                    </div>                 
                    </div>
                    <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('item_type_id', 'Item Type *', ['class' => '']) }}
                        <select name="item_type_id" id="item_type_id" class="form-control">
                            <option value="">Select Item Type</option>
                            @if($item_types!='' && count($item_types))
                             @foreach($item_types as $item_type)
                              <option value="{{ $item_type->item_type_id }}"  {{ ($item_type->item_type_id==$data->item_type_id)?'selected':'' }} >{{ $item_type->item_type_name }}</option>
                             @endforeach
                            @endif
                        </select>
                    </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('unit', 'Unit *', ['class' => '']) }}
                            {{ Form::text('unit', null, array('class' => 'form-control', 'placeholder' => 'Enter unit')) }}
                        </div>                 
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('alt_unit', 'Alternative Unit', ['class' => '']) }}
                            {{ Form::text('alt_unit', null, array('class' => 'form-control', 'placeholder' => 'Enter alternative unit')) }}
                        </div>                 
                    </div>
                  
                    <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('gst_applicable', 'GST Applicable', ['class' => '']) }}
                        {{ Form::select('gst_applicable', ['1' => 'Yes', '2' => 'No'], $data->gst_applicable, array( 'class' => 'form-control select2')) }}
                    </div>
                    </div>

                    <div class="col-md-6" id="gst_no_dv">
                        <div class="form-group">
                            {{ Form::label('gst_no', 'GST No', ['class' => '']) }}
                            {{ Form::text('gst_no', null, array('class' => 'form-control', 'placeholder' => 'Enter GST No')) }}
                        </div>                 
                    </div>

                    <div class="col-md-6" id="gst_percentage_dv">
                        <div class="form-group">
                            {{ Form::label('gst_percentage', 'GST Percentage', ['class' => '']) }}
                            {{ Form::text('gst_percentage', null, array('class' => 'form-control', 'placeholder' => 'Enter GST percentage')) }}
                        </div>                 
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('supply_type', 'Supply Type', ['class' => '']) }}
                            {{ Form::text('supply_type', null, array('class' => 'form-control', 'placeholder' => 'Enter Supply Type')) }}
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
            var gst_applicable='{{ $data->gst_applicable }}';
            if(gst_applicable==2){
                $('#gst_no').hide();
                $('#gst_no_dv').hide();
                $('#gst_percentage').hide();
                $('#gst_percentage_dv').hide();
            }else{
                $('#gst_no').show();
                $('#gst_no_dv').show();
                $('#gst_percentage').show();
                $('#gst_percentage_dv').show();
            }
            $('body').on('change','#gst_applicable', function() {
                var gst_applicable=$(this).val();
                if(gst_applicable==2){
                    $('#gst_no').hide();
                    $('#gst_no_dv').hide();
                    $('#gst_percentage').hide();
                    $('#gst_percentage_dv').hide();
                }else{
                    $('#gst_no').show();
                    $('#gst_no_dv').show();
                    $('#gst_percentage').show();
                    $('#gst_percentage_dv').show();
                }
            });

            $('#datepicker').datepicker({
              format: 'yyyy-mm-dd',
              autoclose: true
            });

             $('body').on('change','#unit_type_id', function() {
                calculate_price();
             });

             $('body').on('keyup','#item_quantity', function() {
                calculate_price();
             });

             function calculate_price(){
                var unit_price = $('#unit_type_id option:selected').attr('pricetag');
                var item_quantity = $('#item_quantity').val();
                if(unit_price && item_quantity){
                var item_price=parseInt(unit_price)*parseInt(item_quantity);
                $('#item_price').val(item_price);
                }else{
                $('#item_price').val('');    
                }
             }
            jQuery("#form-addedit").validate({
                rules: {
                    item_name: {
                        required: true
                    },
                    item_type_id: {
                        required: true
                    },
                    unit: {
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
                    item_name: {
                        required: "Please enter item name."
                    },
                    item_type_id: {
                        required: "Please select item type."
                    },
                    unit: {
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