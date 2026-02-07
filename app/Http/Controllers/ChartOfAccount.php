<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
// for API data receiving from http source
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
// use Datatables;
 use Yajra\DataTables\DataTables;

    

use Session;
use DB;
use URL;
 
class ChartOfAccount extends Controller
{   


public  function ChartOfAcc()
{
///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
$allow= check_role(session::get('UserID'),'Chart of Account','List / Create');
if($allow[0]->Allow=='N')
{
return redirect()->back()->with('error', 'You access is limited')->with('class','danger');
}
////////////////////////////END SCRIPT ////////////////////////////////////////////////

session::put('menu','ChartOfAcc');
$pagetitle='ChartOfAcc';
$chartofaccount = DB::table('chartofaccount')->where('Level',2)->get();

$chart = DB::table('chartofaccount')->where('Level',2)->get();

$chartofaccount_l1 = DB::table('chartofaccount')
->where('Level',1)->get();

$chartofaccount_l2 = DB::table('chartofaccount')
->where('Level',2)->get();


return view ('chartofaccount.chart_of_account',compact('pagetitle','chartofaccount','chart','chartofaccount_l1','chartofaccount_l2'));
}


public  function ChartOfAccountSave(request $request)
{

$CODEA = substr($request->ChartOfAccountID, 0, 1) . '00000';
$CODE = substr($request->ChartOfAccountID, 0, 1);
if ($CODE == 1)
{
$CODE = "A";
}
if ($CODE == 2)
{
$CODE = 'L';
}
if ($CODE == 3)
{
$CODE = 'C';
}
if ($CODE == 4)
{
$CODE = 'R';
}
if ($CODE == 5)
{
$CODE = 'E';
}
if ($CODE == 6)
{
$CODE = 'S';
}
$getID = DB::table('chartofaccount')
->select(DB::raw('max(ChartOfAccountID)+10000 as ChartOfAccountID'))
->where(DB::raw('left(ChartOfAccountID,1)') , '=', substr($request->ChartOfAccountID, 0, 1))
->where(DB::raw('right(ChartOfAccountID,4)') , '=', 0000)->get();
$data = array(
'ChartOfAccountID' => $getID[0]->ChartOfAccountID,
'CODE' => $CODE,
'ChartOfAccountName' => $request->ChartOfAccountName,
'L1' => $request->ChartOfAccountID,
'L2' => $getID[0]->ChartOfAccountID,
'L3' => $getID[0]->ChartOfAccountID,
'Level' =>2
);

$success = DB::table('chartofaccount')->insert($data);
return redirect('ChartOfAcc')->with('error', 'Save Successfully.')->with('class','success');
}

public  function ChartOfAccountSaveL3(request $request)
{

$CODEA = substr($request->ChartOfAccountID, 0, 1) . '00000';
$CODEB = substr($request->ChartOfAccountID, 0, 2) . '0000';
$CODE = substr($request->ChartOfAccountID, 0, 1);
if ($CODE == 1)
{
$CODE = "A";
}
if ($CODE == 2)
{
$CODE = 'L';
}
if ($CODE == 3)
{
$CODE = 'C';
}
if ($CODE == 4)
{
$CODE = 'R';
}
if ($CODE == 5)
{
$CODE = 'E';
}
if ($CODE == 6)
{
$CODE = 'S';
}
// dd(substr($request->ChartOfAccountID, 0, 4));
$getID = DB::table('chartofaccount')
->select(DB::raw('max(ChartOfAccountID)+1 as ChartOfAccountID'))
->where('L2' , '=',$request->ChartOfAccountID)->get();

 

$data = array(
'ChartOfAccountID' => $getID[0]->ChartOfAccountID,
'CODE' => $CODE,
'ChartOfAccountName' => $request->ChartOfAccountName,
'Category' => $request->Category,

'L1' => $CODEA,
'L2' => $CODEB,
'L3' => $getID[0]->ChartOfAccountID,
'Level' =>3
);

 

$success = DB::table('chartofaccount')->insert($data);

return redirect('ChartOfAcc')->with('error', 'Save Successfully.')->with('class','success');
}

public  function ChartOfAccountDelete($ChartOfAccountID)
{


$id = DB::table('chartofaccount')->where('ChartOfAccountID',$ChartOfAccountID)->delete();

return redirect ('ChartOfAcc')->with('error', 'Deleted Successfully.')->with('class','success');
}


public function ChartOfAccountEdit($id)
{


    ///////////////////////USER RIGHT & CONTROL ///////////////////////////////////////////
$allow= check_role(session::get('UserID'),'Chart of Account','List / Create');
if($allow[0]->Allow=='N')
{
return redirect()->back()->with('error', 'You access is limited')->with('class','danger');
}
////////////////////////////END SCRIPT ////////////////////////////////////////////////


session::put('menu','ChartOfAcc');
$pagetitle='ChartOfAcc';
$chartofaccount = DB::table('chartofaccount')->where('ChartOfAccountID',$id)->get();



$parent=$chartofaccount[0]->Level-1;
// dd($parent);


if($parent==1)
{
$chartofaccount_l2 = DB::table('chartofaccount')->where('ChartOfAccountID',$chartofaccount[0]->L1)->get(); 

}
else
{
$chartofaccount_l2 = DB::table('chartofaccount')->where('ChartOfAccountID',$chartofaccount[0]->L2)->get(); 
}
 

return view ('chartofaccount.chart_of_account_edit',compact('pagetitle','chartofaccount','chartofaccount_l2'));


}



public function ChartOfAccountUpdate(request $request)
{   

// dd($request->all());

 
 

 

$data = array(
  'ChartOfAccountName' => $request->ChartOfAccountName,
'Category' => $request->Category,

 
);

 


 
$success = DB::table('chartofaccount')->where('ChartOfAccountID',$request->ChartOfAccountID)->update($data);
    
     return redirect('ChartOfAcc')->with('error','Updated Successfully')->with('class','success');
}

    
}

