<?php

namespace App\Exports;
use Illuminate\Support\Facades\DB;

//export
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
class SupplierLedgerExcel implements FromView,WithTitle,WithColumnWidths
{
   
    protected $SupplierID;
    protected $StartDate;
    protected $EndDate;
 
    public function __construct($SupplierID,$StartDate,$EndDate)
    {
         
        $this->SupplierID = $SupplierID;
         $this->StartDate = $StartDate;
        $this->EndDate = $EndDate;
    }



   



     public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 15,            
            'C' => 10,            
            'D' => 60,            
            'E' => 15,            
            'F' => 15,            
            'G' => 15,            
            'H' => 15,            
        ];
    }

     public function title(): string
    {
        return 'SupplierLedger';
    }


    public function view(): View
    {
 
      $pagetitle='Supplier Ledger';

 
       

 $sql = DB::table('journal')
            ->select( DB::raw('sum(if(ISNULL(Dr),0,Dr)-if(ISNULL(Cr),0,Cr)) as Balance'))
            ->where('SupplierID',$this->SupplierID)
            ->where('ChartOfAccountID',210100)
              ->where('Date','<',$this->StartDate)
            // ->whereBetween('date',array($this->StartDate,$this->EndDate))

               ->get();
 

        $sql[0]->Balance = ($sql[0]->Balance ==null) ? '0' :  $sql[0]->Balance;

 


       $supplier = DB::table('supplier')->where('SupplierID',$this->SupplierID)->get();


        $journal = DB::table('v_journal')->where('SupplierID',$this->SupplierID)
        ->whereBetween('Date',array($this->StartDate,$this->EndDate))
        ->where('ChartOfAccountID',210100)
        ->orderBy('Date', 'asc')
        ->get();
 
         return View ('supplier_ledger_excel',compact('journal','pagetitle','sql' ,'supplier')); 
 
        

   



    }

}



 




 
