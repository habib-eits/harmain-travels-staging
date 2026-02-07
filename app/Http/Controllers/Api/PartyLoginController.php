<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PartyLoginController extends Controller
{
    public function show()
    {
        $company = DB::table('company')->first();
        return view('crm.parties.login', compact('company'));
    }

   
}
