<?php

namespace App\Http\Controllers;

use DB;
use PDF;
use URL;
use File;
use Excel;
use Image;
use Session;
use App\Models\Branch;
use Illuminate\Http\Request;

class User extends Controller
{
 
    public function destroy($id)
    {
        
        
        $id = DB::table('user')->where('UserID',$id)->delete();
        echo "del";
        
    }



    public function Show()
     {

///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
 
////////////////////////////END SCRIPT ////////////////////////////////////////////////


if(session::get('UserType')=='User')
{
   return redirect()->back()->with('error', 'You access is limited')->with('class','danger');
 
}


         session::put('menu','User');     
        $pagetitle = 'User';
       
      if (session::get('UserType') == 'Admin' || session::get('UserType') == 'SuperAdmin') {
          $user = DB::table('v_user')->get();
        } else {
            $user = DB::table('v_user')->where('BranchID', session::get('BranchID'))->get();
        }

        $branch = Branch::getBranchList();
        
        return  view ('user',compact('user','pagetitle','branch'));
     }


     public function UserSave (request $request)
     {

if(session::get('UserType')=='User')
{
   return redirect()->back()->with('error', 'You access is limited')->with('class','danger');
 
}

 
///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
$allow= check_role(session::get('UserID'),'User','List / Create');
if($allow[0]->Allow=='N')
{
return redirect()->back()->with('error', 'You access is limited')->with('class','danger');
}
////////////////////////////END SCRIPT ////////////////////////////////////////////////



            $this->validate($request,[
          
         'Email'=>'required|max:40|unique:user',         
         'Password'=>'required'
         
     ],
      [
       
      'Email.unique' => 'Username already registered',
                
    ]);


        $data = array (

                 'FullName' => $request->input('FullName'),
                'Email' => $request->input('Email'),
                'Password' => $request->input('Password'),
                'UserType' => $request->input('UserType'),
                
                'Active' => $request->input('Active'),
                'BranchID' => $request->input('branch_id'),
                'branch_id' => $request->input('branch_id'),                

                 );



        $id =DB::table('user')->insert($data);
        return redirect('User')->with('error','User Created Successfully')->with('class','success');

     }

    public function UserEdit($id)
     {


                  if(session::get('UserType')=='User')
            {
               return redirect()->back()->with('error', 'You access is limited')->with('class','danger');
            
            }



 session::put('menu','User');     
        $pagetitle = 'User';
 ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
$allow= check_role(session::get('UserID'),'User','Update');
if($allow[0]->Allow=='N')
{
return redirect()->back()->with('error', 'You access is limited')->with('class','danger');
}
////////////////////////////END SCRIPT ////////////////////////////////////////////////

         $v_users= DB::table('user')->where('UserID',$id)->get();
                 $branch = DB::table('branches')->get();


        return  view ('user_edit',compact('v_users','pagetitle','branch'));
     }


public function UserUpdate(request $request)
     {

     
  ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
$allow= check_role(session::get('UserID'),'User','Update');
if($allow[0]->Allow=='N')
{
return redirect()->back()->with('error', 'You access is limited')->with('class','danger');
}
////////////////////////////END SCRIPT ////////////////////////////////////////////////

         $this->validate($request,[
          
          'Password'=>'required'
         
      ],
    [
      'Password.required' => 'Customer Name is required',
            
    ]);

        $data = array 
        (
               
                'FullName' => $request->input('FullName'),
                 'Email' => $request->input('Email'),
                 'Password' => $request->input('Password'),
                'UserType' => $request->input('UserType'),
                 'Active' => $request->input('Active'),
                  'BranchID' => $request->input('branch_id'),
                  'branch_id' => $request->input('branch_id')      
                  );

 
        $id= DB::table('user')->where('UserID',$request->input('UserID'))->update($data);
        return redirect('User')->with('error','Users Updated Successfully')->with('class','success');
     }



     public function UserDelete($id)
     {  
       ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////    
$allow= check_role(session::get('UserID'),'User','Delete');
if($allow[0]->Allow=='N')
{
return redirect()->back()->with('error', 'You access is limited')->with('class','danger');
}
////////////////////////////END SCRIPT ////////////////////////////////////////////////

            $id = DB::table('user')->where('UserID',$id)->delete();
            return redirect('User')->with('error','User Deleted Successfully')->with('class','success');

     }


}
