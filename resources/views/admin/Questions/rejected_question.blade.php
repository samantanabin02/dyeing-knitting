@extends('admin.layouts.AdminPanel')

@section('title')

	Approval Pending  List

@endsection

@section('content')
<style>
    .mrg {
        margin: 3px 0px;
    }
    .btn-block-xs {
        display: inline-block;
        width: 100%;
    }
@media (min-width:768px) {
    .btn-block-xs {
        display: inline-block;
        width: auto;
    }
}
</style>

    <section class="content-header">

        <h1>

        Rejected Question

        <small>list</small>

        </h1>

        <ol class="breadcrumb">

            <li><a href="{{ url('admin/dashboard') }}"><i class="fa fa-dashboard"></i>Dashboard</a></li>

            <li>Question</li>

            <li> <a href="{{ route('questions.index') }}" class="active">Pending List</a></li>

        </ol>

    </section>

    <section class="content">

        <div class="box box-default">

            <div class="box-header with-border">

                <div class="portlet-body form">

                    {{ Form::open(['route' => 'questions.index', 'method' => 'get', 'class' => '', 'id' => 'form-search']) }}

                        <div class="form-group">

                            <div class="col-md-3 mrg">
                            {{ Form::text('search_key', Input::get('search_key'), ['id' => 'search_key', 'class' => 'form-control', 'placeholder' => 'Enter search key']) }}
                            </div>                         

                            <div class="col-md-1 col-xs-6 mrg">
                            {{ Form::button('<i class="fa fa-search"></i>', ['type' => 'submit', 'class' => 'btn btn-primary btn-block-xs', 'id' => 'submit-btn'] )  }}
                            </div>

                            <div class="col-md-1 col-xs-6 mrg">
                            <a href="{{ route('rejected-question') }}" id="cancel-btn" class="btn btn-warning btn-block-xs"><i class="fa fa-refresh"></i></a>
                            </div>

                            <div class="col-md-7 mrg" align="right">
                                <a href="{{ route('questions.index') }}" class="btn"><i class="fa fa-list-ul fa-add-edit-delete-btn"></i></a>
                                <a href="#" data-toggle="modal" data-target="#multi_delete" id="multi_delete_btn" class="btn"><i class="fa fa-trash-o fa-add-edit-delete-btn"></i></a>                    
                            </div>

                        </div>

                    {{ Form::close() }}

                </div>              
                

            </div>

        

            <div class="box-body">

            {{ Form::open(['route' => ['questions-delete'], 'method' => 'post', 'id' => 'multi_delete_form', 'class' => '']) }}

            {{ Form::hidden('deletable_ids','', array('id'=>'deletable_ids')) }}

            {{ Form::close() }}                        

                <table id="example2" class="table table-bordered table-hover" style="width: 100%;display: inline-block;overflow-x: scroll">

                    <thead>

                        <tr>

                        	<th width="3%">{{ Form::checkbox('multi_check',null,null, array('id'=>'multi_check')) }}</th>

                            <th>subject</th>

                            <th>Question</th>  

                            <th>Accept</th>                                                

                            <th width="10%">

                            Action(s)                          

                            </th>

                        </tr>

                    </thead>

                

                    <tbody>

                        @if (count($datums)>0)

                            @foreach ($datums as $data)

                                <tr>

                                	<td>{{ Form::checkbox('single_check',$data->id,null, array('id'=>'single_check','class'=>'single_check')) }}</td>

                                    <td>{{ $data->subject }}</td>

                                    <td>{{ str_limit(strip_tags($data->question), 25)}}</td>



                                    <td>
                                   
                                    <a href="{{ url('admin/questions-activate',  $data->id) }}" class="btn btn-success">Accept</a>
                                   
                                    </td> 
                           

                                    <td>                                        

                                

                                    <a href="{{ route('questions.show',  $data->id) }}" class=""><i class="fa fa-list-alt"></i></a>

                                 

                                    <a href="#" data-toggle="modal" data-target="#myModal" class=""><i class="fa fa-trash-o"></i></a> 

                                    

                                    {{ Form::open(['route' => ['questions.destroy', $data->id], 'class' => 'form-horizontal', 'id' => "delete-form" ]) }}

                                    {{ Form::hidden('_method', 'DELETE') }}

                                    {{ Form::close() }}                                 

                                    </td>

                                </tr>

                            @endforeach

                        @else

                    <tr>

                        <td colspan="4">No records found.</td>

                    </tr>

                    @endif

                    </tbody>               

                    

                </table>

            </div>

        

            <div class="box-footer">

                {{ $datums->appends(Input::except('page')) }}

            </div>

            

        </div>



    </section>

    

    <div class="modal fade" id="myModal" role="dialog">

    <div class="modal-dialog">   

      <div class="modal-content">       

        <div class="modal-body">

          <p>

          Are you sure to delete this question?          

          <button type="button" style="float:right; margin-left:10px;"class="btn btn-default" data-dismiss="modal">No</button>

          <button type="button"  style="float:right;" onclick="event.preventDefault();document.getElementById('delete-form').submit();" class="btn btn-default" data-dismiss="modal">Yes</button>     

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

          Please select any question to delete.<button type="button" style="float:right;"class="btn btn-default" data-dismiss="modal">Ok</button>

          </p>       

          <p id="mltdltp">

          Are you sure to delete these questions?          

          <button type="button" style="float:right; margin-left:10px;"class="btn btn-default" data-dismiss="modal">No</button>

          <button type="button"  style="float:right;" onclick="event.preventDefault();document.getElementById('multi_delete_form').submit();" class="btn btn-default" data-dismiss="modal">Yes</button>     

          </p>         

          

        </div>

       

      </div>

      

    </div>

  </div>     



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

	 //alert(allVals);

	  jQuery('#deletable_ids').val(allVals);

	

	 if(allVals==''){

	    jQuery('#mltdltchkp').show();	 

		jQuery('#mltdltp').hide(); 

	 }else{		 

	    jQuery('#mltdltchkp').hide();	 

		jQuery('#mltdltp').show(); 

	 }

    

    });		



});

</script>



@endsection