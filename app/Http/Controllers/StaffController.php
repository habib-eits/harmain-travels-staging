<?php

namespace App\Http\Controllers;

use App\Events\StaffCreationEvent;
use App\Events\StaffUpdationEvent;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
 use Yajra\DataTables\DataTables;

class StaffController extends Controller
{
    public function index(Request $request)
    {
       
            $data = User::all();
            // $roles = Role::all();
             $branches = Branch::all();
            $roles = DB::table('roles')->get();
            return view('staff.index', compact('data', 'roles', 'branches'));
        
    }
    public function create()
    {
        try {
            $roles = Role::all();
            $branches = Branch::all();
            return view('staff.create', compact('roles', 'branches'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }
    public function store(Request $request)
    {

        

        try {
            $request->validate([
                'name' => 'required|max:255',
                'email' => 'required|email|max:255|unique:users,email',
                'tel' => 'nullable|max:255',
                'role' => 'required'
            ]);
            DB::beginTransaction();
            $random = str_shuffle('abcdefghjklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ234567890!$%^&!$%^&');
            $password = substr($random, 0, 10);
            $encPassword = bcrypt($password);
            $data = $request->except('_token');
            // $data['password'] = $encPassword;
            $data['password'] = 123456;
            $user = User::create($data);
             $mailData = [
                'title' => 'Mail from CRM',
                'body' => 'Your Account Credentials.',
                'email' => $request->email,
                'name' => $request->name,
                'password' => 123456,
                'role' => $request->role
            ];
            // event(new StaffCreationEvent($mailData));
            DB::commit();
            return redirect('/staff')->withSuccess("Staff Member Created Successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }
    public function edit($id)
    {
        try {
            $data = User::findOrFail($id);
            return response()->json(['data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    public function update(Request $request)
    {
        // dd($request->all());
        try {
            $request->validate([
                'name' => 'required|max:255',
                'email' => 'required|email|max:255|unique:users,email,'. $request->id . ',id',
                'tel' => 'nullable|max:255',
                'role' => 'required'
            ]);
            DB::beginTransaction();
            $user = User::findOrFail($request->id);
            $user->removeRole($user->role);
            $random = str_shuffle('abcdefghjklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ234567890!$%^&!$%^&');
            $password = substr($random, 0, 10);
            $encPassword = bcrypt($password);
            $data = $request->except('_token', 'id');
            // $data['password'] = $encPassword;
             $data['password'] = 123456;

            // dd($data);
            $user->update($data);
             $mailData = [
                'title' => 'Mail from CRM',
                'body' => 'Your Updated Account Credentials.',
                'email' => $request->email,
                'name' => $request->name,
                'password' => 123456,
                'role' => $request->role
            ];
            event(new StaffUpdationEvent($mailData));
            DB::commit();
            return redirect('/staff')->withSuccess("Staff Member Updated Successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }
    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $user = User::findOrFail($id);
            // $user->removeRole($user->role);
            $user->delete();
            DB::commit();
            return back()->with('error','Branch Deleted Successfully')->with('class','success');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withError($e->getMessage());
        }
    }
}
