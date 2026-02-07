<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PartyLedgerController extends Controller
{
    public function index(Request $request)
    {
        return view('crm.parties.ledger');
    }
}
