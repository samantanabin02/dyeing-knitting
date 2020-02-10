<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Hash;
use DB;
use Validator;
use Storage;
class UserController extends Controller
{
    
    public function index(Request $request)
    {		
    	$req=$request->all();
    	//dd($req);
		$query = DB::table("users")->where('deleted_at', NULL);
		if ($request->has('search_key')) {
			$query->where(function ($query) use($req) {
				$query->where('name', 'like', '%'.$req['search_key'].'%');
			    $query->orWhere('email', 'like', '%'.$req['search_key'].'%');
			});
		}
		if ($request->has('status') && $req['status']!=null ) {
			$query->where('status', $req['status']);
		}
		$datums = $query->orderBy('id', 'desc')->paginate(10);		
        return view('admin.Users.index', ['datums' => $datums]);	
    }
 
	
    public function create()
    {	
		$data=array();
        return view('admin.Users.create', ['data' => $data]);
    }

   
    public function store(Request $request)
    {
		$validator = $this->validator($request->all())->validate();		
		
		$file_details=$request->file('user_image');
		if($file_details){ 
		$file_original_name= $file_details->getClientOriginalName();
		$file_extension =pathinfo($file_original_name, PATHINFO_EXTENSION);
		$file_name = rand().'.'.$file_extension ; 
		$uploads_path=public_path('uploads/user_image/');
		//echo '<pre>';print_r($uploads_path);die;
		$file_details->move($uploads_path, $file_name); 
		$user_image=$file_name;
		} 


		$password = Hash::make($request->password); 
		
		$data = new User;
		$data->name = $request->name;	
	    $data->email = $request->email;	
		$data->password = $password;
		$data->image =$user_image;	
		$data->status = $request->status;		
		if($data->save()) {
			return redirect()->back()->with('success', 'User created successfully.');	
		} else {
			return redirect()->back()->with('error', 'Some problem occurred.Please try again!');
		}
		
    }

 
    public function show($id)
    {
		$data = User::find($id);
		return view('admin.Users.show', ['data' => $data]);
    }

   
    public function edit($id)
    {		
        $data = User::find($id); 
		return view('admin.Users.edit', ['data' => $data]);
    }

  
    public function update(Request $request, $id)
    {
        $validator = $this->validator($request->all())->validate();	
		
		$file_details=$request->file('user_image');
		if($file_details){ 
		$file_original_name= $file_details->getClientOriginalName();
		$file_extension =pathinfo($file_original_name, PATHINFO_EXTENSION);
		$file_name = rand().'.'.$file_extension ; 
		$uploads_path=public_path('uploads/user_image/');
		//echo '<pre>';print_r($uploads_path);die;
		$file_details->move($uploads_path, $file_name); 
		$user_image=$file_name;
		}else{
			$user_image=$request->pre_user_image;
		}		
		$data = User::find($id);
		$data->name = $request->name;	
	    $data->email = $request->email;			
		$data->image =$user_image;	
		$data->status = $request->status;			
		if($data->save()) {
			return redirect()->back()->with('success', 'User updated successfully.');	
		} else {
			return redirect()->back()->with('error', 'Some problem occurred.Please try again!.');
		}
		
    }

  
    public function destroy($id)
    {
        $data = User::find($id);
        //echo '<pre>';print_r($data);die;
        if ($data->delete()) {
				return redirect()->route('users.index')->with('success', 'Successfully deleted.');
		}
    }
	
	
	public function multi_destroy(Request $request)
    {
    	$req=$request->all();
		$deletable_ids = $req['deletable_ids'];

		//dd($deletable_ids);
		$deletable_ids=explode(',',$deletable_ids);
		if(count($deletable_ids) > 0) { 
			foreach($deletable_ids as $deletable_id){
				$this->destroy($deletable_id);
			}
			return redirect()->route('users.index')->with('success', 'Successfully deleted.');
		} else {
			return redirect()->route('users.index')->with('error', 'Select record(s) form list for delete.');
		}
    }
	

    protected function validator(array $data)
    {
	
		return Validator::make(
			$data,
			[
				'name' => 'required',
				'status' => 'required'
			],
			[
				'name.required' => 'Enter name.',
				'status.required' => 'Select status.'
			]
		);
    }
}
