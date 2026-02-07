<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{$pagetitle}}</title>

    <style type="text/css">

         @page {
                margin-top: 0.5cm;
                margin-bottom: 0.5cm;
                margin-left: 0.5cm;
                margin-right: 0.5cm;
            }


table {
    border-left: 0.01em solid #ccc;
    border-right: 0;
    border-top: 0.01em solid #ccc;
    border-bottom: 0;
    border-collapse: collapse;
}
table td,
table th {
    border-left: 0;
    border-right: 0.01em solid #ccc;
    border-top: 0;
    border-bottom: 0.01em solid #ccc;
    
}

.noborder_table {
    border-left: 0;
    border-right: 0;
    border-top: 0.;
    border-bottom: 0;
    border-collapse: collapse;
}

.noborder_table  td,
.noborder_table   th {
    border-left: 0;
    border-right: 0;
    border-top: 0;
    border-bottom: 0;
}



.style1 {
  font-size: 22px;
  font-weight: bold;
}
body,td,th {
  font-size: 13px;
}
-->
    </style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>
<body>

 <table width="100%" border="0" cellspacing="0" cellpadding="0" class="noborder_table">
  <tr>
    <td colspan="2"><div align="center" class="style1">{{$company[0]->Name}}</div>

<div align="center" >{{$company[0]->Address}}</div>
<div align="center" >TRN : {{$company[0]->TRN}}</div>
    </td>
  </tr>
  <tr style="color: red;">
    <td colspan="2"><div align="center" class="style1" style="color: red;">{{$party[0]->PartyID}}-{{$party[0]->PartyName}} </div><div align="center">Ledger Account</div></td>
  </tr>
 <tr style="color: red;">
    <td colspan="2"><div align="center">Contact : {{$party[0]->Phone}}</div></td>
  </tr>
 <tr style="color: red;">
    <td colspan="2"><div align="center">From {{dateformatman2(request()->StartDate)}} To {{dateformatman2(request()->EndDate)}}
    </div></td>
  </tr>
  <tr>
    <td width="50%">Dated: {{date('d-m-Y')}}</td>
    <td width="50%">&nbsp;</td>
  </tr>
</table>
       
          <?php 
            $DrTotal=0;
            $CrTotal=0;
          
           ?>

<script type="text/php">
    if ( isset($pdf) ) {
        $font = Font_Metrics::get_font("helvetica", "bold");
        $pdf->page_text(72, 18, "Header: {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0));
    }
</script> 

          <table width="60%" border="1" style="font-size: 10pt;" align="center">
          
          <tr>
          <th bgcolor="#CCCCCC"><strong>VHNO</strong></th>
          <th bgcolor="#CCCCCC"><strong>DATE</strong></th>
          <th bgcolor="#CCCCCC"><strong>C.O</strong></th>
          <th bgcolor="#CCCCCC"><strong>Description</strong></th>
          <th bgcolor="#CCCCCC"><strong>DR</strong></th>
          <th bgcolor="#CCCCCC"><strong>CR</strong></th>
          <th bgcolor="#CCCCCC"><strong>Balance</strong></th>
          </tr>
          
   

      @if(!$journal->isEmpty())
          @foreach ($journal as $key =>$value)
        <?php 
        $invoice_master = DB::table('invoice_master')->where('InvoiceMasterID',$value->InvoiceMasterID)->get();
         ?>
           <tr valign="top">
           <td style="border: 1px solid black;" valign="top">

@php
    $url = '';
    if (str_starts_with($value->VHNO, 'UI')) {
        $url = URL('/UmrahEdit/'.$value->InvoiceMasterID);
    } elseif (str_starts_with($value->VHNO, 'SI')) {
        $url = URL('/InvoiceEdit/'.$value->InvoiceMasterID);
    }
@endphp

<a href="{{ $url }}" title="" target="_blank">{{ $value->VHNO }}</a>
            
            </td>
           <td style="border: 1px solid black;" valign="top">{{dateformatman($value->Date)}}</td>
           <td style="border: 1px solid black;" valign="top"><a href="{{URL('/VoucherEdit'.'/'.$value->VoucherMstID)}}" title="" target="_blank">Edit VH#</a></td>
           <td align="left" style="border: 1px solid black;  " valign="top" >{{$value->Narration}}<div style="color: red;">  @if((substr($value->VHNO,0,3)=='TAX') || (substr($value->VHNO,0,3)=='INV'))
<?php 

$invoice_detail = DB::table('v_invoice_detail')->where('InvoiceMasterID',$value->InvoiceMasterID)->get();

?>

      @if(!$invoice_detail->isEmpty())
 
             @foreach ($invoice_detail as $key =>$value1)
                  
 
   {{$value1->ItemName}} {{$value1->Qty}} Qty x {{$value1->Rate}} Rate ={{$value1->Total}} <br>
              
                 
                  
                 @endforeach

                       
    @endif


    @endif</div></td>
           <td  style="border: 1px solid black;" valign="top" align="right"> {{($value->Dr==0) ? '' : number_format($value->Dr,2)}}</td>
           <td style="border: 1px solid black;" valign="top" align="right" > {{($value->Cr==0) ? '' : number_format($value->Cr,2)}}</td>
           <td style="border: 1px solid black;" valign="top" align="right" >
               
                
                 <?php 

              if(!isset($balance)) { 

              $balance  = ($value->Dr-$value->Cr);
              /* $balance  =  $sql[0]->Balance + ($value->Dr-$value->Cr); */
              $DrTotal = $DrTotal+$value->Dr;
              $CrTotal = $CrTotal+$value->Cr;
              echo number_format($balance,2);

              }
              else
              {
                $balance = $balance + ($value->Dr-$value->Cr);
                $DrTotal = $DrTotal+$value->Dr;
                          $CrTotal = $CrTotal+$value->Cr;
                echo number_format($balance,2);
              }
              ?>
             {{($balance>0) ? "DR" : "CR"}}  </td>
           </tr>








           @endforeach 

          <tr  >
              
           
           <td style="border: 1px double black; background-color: #CCCCCC;" colspan="4" align="center"><strong>TOTAL</strong></td>
           <td style="border: 1px double black; background-color: #CCCCCC;" align="right" ><strong>{{number_format($DrTotal,2)}}</strong></td>
           <td style="border: 1px double black; background-color: #CCCCCC;" align="right"><strong>{{number_format($CrTotal,2)}}</strong></td>
           <td style="border: 1px double black; background-color: #CCCCCC;" align="right"><strong>{{number_format(($DrTotal-$CrTotal),2)}}</strong></td>
          </tr>
            

           @else
             <tr>
              
           <td colspan="7" align="center" style="border: 1px double black; background-color: #CCCCCC;"><strong>No Date Found</strong></td>
        
          </tr>
           @endif
       
 </table>
       
       
</body>
</html>