@extends('admin.layouts.AdminPanel')

@section('title')
	Question Show
@endsection

@section('content')

	<section class="content-header">
        <h1>
            Question
            <small>View</small>
        </h1>
       <ol class="breadcrumb">

            <li><a href="{{ url('admin/dashboard') }}"><i class="fa fa-dashboard"></i>Dashboard</a></li>

            <li>Question</li>

            <li> <a href="{{ url('admin/pending-question') }}}" class="active">Pending List</a></li>

        </ol>
    </section>
    
    
    <section class="content">

      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">User details</h3>

          <div class="box-tools pull-right">
          	<a href="{{ route('questions.index') }}" class="btn"><i class="fa fa-list-ul fa-add-edit-delete-btn"></i></a>

            <a href="#" data-toggle="modal" data-target="#multi_delete" id="multi_delete_btn" class="btn"><i class="fa fa-trash-o fa-add-edit-delete-btn"></i></a> 
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
			<div class="row">

                <div class="col-md-12">
                
                    <div class="box box-solid">
                        <div class="box-body">
                          
                            <div class="form-group">
                                {{ Form::label('Subject', 'Subject:', ['class' => '']) }}                      
                                {{ $datums->subject }}
                            </div>      
                            
                            <div class="form-group">
                                {{ Form::label('Question', 'question:', ['class' => '']) }}                      
                                {{ strip_tags($datums->question) }}
                            </div> 

                            <div class="form-group">
                                <?php
                                $category_details= DB::table('categories')->where('id', '=', $datums->category)->first();
                                ?>
                                {{ Form::label('Category', 'category:', ['class' => '']) }}                      
                                {{ $category_details->name }}
                            </div> 

                            <div class="form-group">
                                <?php
                                $sub_category_details= DB::table('sub_categories')->where('id', '=', $datums->sub_category)->first();
                                ?>
                                {{ Form::label('Subcategory', 'sub_category:', ['class' => '']) }}                      
                                {{ $sub_category_details->name }}
                            </div>                             
                          

                            <div class="form-group">
                                <?php
                                if($datums->display_name==1){
                                $display_name='Anonymous';
                                }elseif($datums->display_name==2){

                                $question_user_id=$datums->user_id;
                                $user_details= DB::table('users')->where('email', '=', $question_user_id)->first();
                                $display_name=$user_details->name;    
                                }elseif($datums->display_name==3){
                                $display_name=$datums->other_display_name;    
                                }
                                ?>

                                {{ Form::label('Display Name', 'Display Name:', ['class' => '']) }}                      
                                {{ $display_name }}
                            </div> 

                            

                            <div class="form-group">
                                {{ Form::label('status', 'Status:', ['class' => '']) }}                      
                                <?php
                                if($datums->status=='1'){
                                echo 'Active';
                                }else{
                                echo 'Inactive';    
                                }
                                ?>
                            </div>    

                            <div class="form-group">
                                {{ Form::label('Approved', 'Approved:', ['class' => '']) }}                      
                                <?php
                                if($datums->is_accept=='1'){
                                echo 'YES';
                                }else{
                                echo 'NO';    
                                }
                                ?>
                            </div> 
                            
                            <?php if($datums->attachment){ ?>      
                            <div class="form-group">
                                {{ Form::label('image', 'attachment:', ['class' => '']) }} 
                                <?php 
                                $image_name=$datums->attachment;
                                ?>                                             
                                <img src="{{ URL::to('/public/uploads/user_question_attachment/'.$image_name) }}" height="100px">
                                <?php                                
                                ?>
                            </div>
                            <?php }?>
                            
                            
                        </div>
                    </div>

                </div>

          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
        	<a href="Javascript:window.history.go(-1);" class="btn btn-info"><i class="fa fa-chevron-circle-left"></i></a>
        </div>
      </div>
      <!-- /.box -->

      
      <!-- /.row -->

    </section>
            
@endsection