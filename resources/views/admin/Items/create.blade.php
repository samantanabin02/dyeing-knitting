@extends('admin.layouts.AdminPanel')

@section('title')
	Item Add
@endsection

@section('content')
   {{ Html::style('resources/views/admin/assets/bower_components/select2/dist/css/select2.min.css') }}
   {{ Html::style('resources/views/admin/assets/bower_components/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }} 
    <!--<section class="content-header">
        <h1>
            Item
            <small>Create</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('admin/home') }}"><i class="fa fa-dashboard"></i>Home</a></li>
            <li><a href="{{ route('items.index') }}">Item</a></li>
            <li class="active">Add</li>
        </ol>
    </section> -->
    <section class="content">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Create Item <small class="text-red">* Fields are mendatory </small></h3>
          <div class="box-tools pull-right">
          	<a href="{{ route('items.index') }}" class="btn-sm"><i class="fa fa-list-ul fa-add-edit-delete-btn"></i></a>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
			<div class="row">
			{{ Form::open(['route' => ['items.store'], 'method' => 'POST', 'files' => true, 'class' => '', 'id' => 'form-addedit','enctype' => 'multipart/form-data']) }}
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
                              <option value="{{ $item_type->item_type_id }}">{{ $item_type->item_type_name }}</option>
                             @endforeach
                            @endif
                        </select>
                    </div>
                    </div>
                    <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('unit_type_id', 'Unit Type *', ['class' => '']) }}
                        <select name="unit_type_id" id="unit_type_id" class="form-control">
                            <option value="">Select Unit Type</option>
                            @if($unit_types!='' && count($unit_types))
                             @foreach($unit_types as $unit_type)
                              <option value="{{ $unit_type->unit_type_id }}" pricetag="{{ $unit_type->unit_type_price }}">{{ $unit_type->unit_type_name.' (   '.$unit_type->unit_type_price.'/- per '.$unit_type->unit_type_name.' )' }}</option>
                             @endforeach
                            @endif
                        </select>
                    </div>
                    </div>
                    <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('company_id', 'Company *', ['class' => '']) }}
                        <select name="company_id" id="company_id" class="form-control">
                            <option value="">Select Company</option>
                            @if($companies!='' && count($companies))
                             @foreach($companies as $company)
                              <option value="{{ $company->company_id }}">{{ $company->company_name }}</option>
                             @endforeach
                            @endif
                        </select>
                    </div>
                    </div>
                    <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('item_quantity', 'Item Quantity *', ['class' => '']) }}
                        {{ Form::text('item_quantity', null, array('class' => 'form-control', 'placeholder' => 'Enter item quantity')) }}
                    </div>                 
                    </div>
                    <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('item_price', 'Item Price *', ['class' => '']) }}
                        {{ Form::text('item_price', null, array('class' => 'form-control', 'placeholder' => 'Enter item price')) }}
                    </div>                 
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('purchase_date', 'Purchase Date *', ['class' => '']) }}
                            {{ Form::text('purchase_date', null, array('class' => 'form-control', 'placeholder' => 'Enter Purchase Date', 'id'=>'datepicker')) }}
                        </div>                 
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('opening_balance', 'Opening Balance *', ['class' => '']) }}
                            {{ Form::text('opening_balance', null, array('class' => 'form-control', 'placeholder' => 'Enter opening balance')) }}
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
                    unit_type_id: {
                        required: true
                    },
                    company_id: {
                        required: true
                    },
                    item_quantity: {
                        required: true
                    },
                    item_price: {
                        required: true
                    },
                    purchase_date: {
                        required: true
                    },
                    opening_balance: {
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
                    unit_type_id: {
                        required: "Please select unit type."
                    },
                    company_id: {
                        required: "Please select company."
                    },
                    item_quantity: {
                        required: "Please enter item quantity."
                    },
                    item_price: {
                        required: "Please enter item price."
                    },
                    purchase_date: {
                        required: "Please enter purchase date."
                    },
                    opening_balance: {
                        required: "Please enter opening balance."
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