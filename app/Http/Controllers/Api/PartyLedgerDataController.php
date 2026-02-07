<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PartyLedgerDataController extends Controller
{
    public function index(Request $request)
    {
        $party = auth('party-api')->user();

        $startDate = $request->filled('startDate') ? $request->input('startDate') : date('Y-m-01');
        $endDate = $request->filled('endDate') ? $request->input('endDate') : date('Y-m-d');

        $ledgerData = $this->getLedgerData($party, $startDate, $endDate);

        $ledgers = $ledgerData['ledgers'];
        $openingBalance = $ledgerData['opening_balance'];
        $closingBalance = $ledgerData['closing_balance'];

        return response()->json([
            'party'  => $party,
            'startDate' => $request->startDate,
            'endDate' => $request->endDate,
            'openingBalance' => $openingBalance,
            'closingBalance' => $closingBalance,
            'ledgers' => $ledgers

        ]);
    }

    protected function getLedgerData($party,$startDate,$endDate)
    {

        $openingBalance = DB::table('journal')
        ->where('PartyID', $party->PartyID)
        ->where('Date', '<',$startDate)
        ->whereIn('ChartOfAccountID',[210100,110400]) //  A/C PAYABLE ,  A/C RECEIVABLE. 
        ->orderBy('Date', 'asc') // Sort by Date in ascending order
        ->select(DB::raw('sum(Dr) - sum(Cr) as openingBalance'))
        ->first();

        $openingBalance = $openingBalance->openingBalance ?? 0;



        $ledgers = DB::table('journal')
        ->where('PartyID', $party->PartyID)
          ->whereBetween('Date', [$startDate, $endDate])
        ->whereIn('ChartOfAccountID',[210100,110400]) //  A/C PAYABLE ,  A/C RECEIVABLE. 
        ->orderBy('Date', 'asc') // Sort by Date in ascending order
        ->orderBy('JournalID', 'asc')   // Sort by ID in ascending order
        ->get();


        $runningBalance = $openingBalance;
        $ledgers->each(function ($item) use (&$runningBalance) {
            $item->Date = date('d-m-Y', strtotime($item->Date));
            $item->Dr = (float) $item->Dr;
            $item->Cr = (float) $item->Cr;
            $runningBalance += $item->Dr - $item->Cr;
            $item->Balance = number_format($runningBalance, 2, '.', ',');
            $item->Dr = number_format($item->Dr, 2, '.', ',');
            $item->Cr = number_format($item->Cr, 2, '.', ',');
            $item->Narration = $item->Narration;
        });

        return [
            'ledgers' => $ledgers,
            'opening_balance' => number_format($openingBalance, 2, '.', ','),
            'closing_balance' => number_format($runningBalance, 2, '.', ','),
        ];
    }
}
