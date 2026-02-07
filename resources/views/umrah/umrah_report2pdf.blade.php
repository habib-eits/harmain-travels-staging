<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PDF</title>
        <style type="text/css">
        @page {
            margin-top: 100px;
            margin-bottom: 100px;
            margin-left: 0.4cm;
            margin-right: 0.4cm;

        }

        body,
        td,
        th {
            font-size: 10pt;
            font-family: Arial, Helvetica, sans-serif;
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

header {

    position: fixed;
   top: -95px;
   left: 0px;
   right: 0px;
   height: auto;
   font-size: 20px !important;
   /*background-color: black;*/
   text-align: left;
   /*border-bottom: 1px solid black;*/

   float: left;       }


footer {
   position: fixed;
   bottom: -90px;
   left: 0px;
   right: 0px;
   height: auto;
   font-size: 13px !important;
   border-top: 1px solid black;
   text-align: center;
   padding-top: 0px;
}
</style>

</head>
</head>
<body>

 <header>



 
 

    <img src="{{asset('assets/images/logo/ft.png')}}" >


 







</header>







<footer style="text-align: center;">
  Address: Shop #89 Al Ameed Plaza Alqouz 4, Dubai U.A.E <br>Landline: +971 4 880 7551
Mobile: +971 55 575 1344<br>
Email: info@falaktravel.com
</footer>




<table class="noborder_table" align="right" style="margin-top: -75px;">
  <tr><td align="right"><strong>STATMENT OF ACCOUNTS</strong></td></tr>
  <tr>
    
    <td align="right">Supplier: <strong>{{$supplier->SupplierName}}</strong></td>
  </tr><tr>
    <td>Date <strong>{{dateformatman2(request()->startdate)}}</strong> to <strong>{{dateformatman2(request()->enddate)}}</strong></td>
  </tr>
  <tr>
    <td align="right">Report Type: <strong>{{(request()->type=='Date' ? 'Invoice Date' : 'Departure Date')}}</strong></td>
  </tr>
</table>
  
@php
  // Initialize total variables
  $totalUmrahFare = 0;
  $totalFare = 0;
  $totalService = 0;
  $totalVAT = 0;
  $totalInvoice = 0;
  $totalPaid = 0;
  $totalPaymentInBus = 0;
@endphp

 
 @if(count($invoice_detail)>0)    
<table border="1" class="table-bordered" style="width: 820 !important;">

<thead style="background-color: #e9e9e9;">
<th width="2">S.No</th>
<th width="5">INV#</th>
<th width="70" align="left">Saleman</th>
<th width="70">Pax Name</th>
 <th width="10">Passport</th>
<th width="30">Pick Point</th>
<th width="30">Room Type</th>
<th width="30">Visa Type</th>
<th width="30">Date of <br>Departure</th>

<th width="10">Pay In Bus</th>
<th width="10">Passport</th>
<th width="10">Emirate ID <br>Front</th>
<th width="10">Emirate ID <br>Back</th>
<th width="10">PictureFile</th>
</thead>


<tbody>
<?php $no=0; ?>
@foreach ($invoice_detail as $key =>$invoice_detail)
@php
  $no = $no + 1;
  $totalUmrahFare += $invoice_detail->UmrahFare;
  $totalFare += $invoice_detail->Fare;
  $totalService += $invoice_detail->Service;
  $totalVAT += $invoice_detail->Taxable;
  $totalInvoice += $invoice_detail->Total;
  $totalPaid += $invoice_detail->Paid;
  $totalPaymentInBus += $invoice_detail->PaymentInBus;
@endphp


 <tr>
 <td align="center" >{{$key+1}}</td>
 <td align="center" >{{$invoice_detail->InvoiceMasterID}}</td>
 <td >{{$invoice_detail->SalemanName}}</td>
 <td >{{$invoice_detail->PaxName}}</td>
  <td >{{$invoice_detail->Passport}}</td>
 <td align="center">{{$invoice_detail->PickPoint}}</td>
 <td align="center">{{$invoice_detail->RoomType}}</td>
 <td align="center">{{$invoice_detail->VisaType}}</td>
 <td align="center">{{dateformatman2($invoice_detail->DepartureDate)}}</td>
 <td  align="center">{{number_format($invoice_detail->PaymentInBus)}}</td>
<td align="center">
  @if($invoice_detail->PassportFile)
    <a href="{{ asset('/' . $invoice_detail->PassportFile) }}" download>Download </a>
  @else
    No File
  @endif
</td>
<td align="center">
  @if($invoice_detail->EmirateIDFileFront)
    <a href="{{ asset('/' . $invoice_detail->EmirateIDFileFront) }}" download>Download</a>
  @else
    No File
  @endif
</td>
<td align="center">
  @if($invoice_detail->EmirateIDFileBack)
    <a href="{{ asset('/' . $invoice_detail->EmirateIDFileBack) }}" download>Download</a>
  @else
    No File
  @endif
</td>
<td align="center">
  @if($invoice_detail->PictureFile)
    <a href="{{ asset('/' . $invoice_detail->PictureFile) }}" download>Download</a>
  @else
    No File
  @endif
</td>

 </tr>
 @endforeach   
 <tr style="background-color: #e9e9e9; font-weight: bolder;">
  <td colspan="9" align="center"><strong>Total ({{$no}} Umrah)</strong></td>
  <td align="center">{{number_format($totalPaymentInBus)}}</td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
</tr>
  
 </tbody>
 </table>
 
 

 @else
   <p class=" text-danger">No data found</p>
 @endif   
    <script type="text/php">



        if (isset($pdf)) { 
     //Shows number center-bottom of A4 page with $x,$y values
        $x = 780;  //X-axis i.e. vertical position 
        $y = 540; //Y-axis horizontal position
        $text = "Page {PAGE_NUM} of {PAGE_COUNT}";  //format of display message
        $font =  $fontMetrics->get_font("helvetica", "normal");
        $size = 9;
        $color = array(0,0,0);
        $word_space = 0.0;  //  default
        $char_space = 0.0;  //  default
        $angle = 0.0;   //  default
        $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);

    }

    

    </script>
</body>

</html>