<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Invoice</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"><style type="text/css">
<!--
body,td,th {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 13px;
}
.style3 {
	font-size: 20px;
	font-weight: bold;
}
.style5 {font-size: 14px}
.style6 {
	font-size: 16px;
	font-weight: bold;
}
-->
</style>
 <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/style.css')}}">
    <!-- END: Custom CSS-->
</head>
<body onload="window.print()">
  <div align="center">
    <table width="800" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="50%"><span class="style3">FALAK TRAVEL AND TOURISM LLC</span></td>
      <td width="50%" rowspan="3"><div align="right"><img src="{{ asset('documents/'.$company->Logo) }}" ></div></td>
    </tr>
      <tr>
        <td>Shop #3, Maram Building Hor Ul Anz, Deira Dubai</td>
    </tr>
      <tr>
        <td><span class="style5">PHONE : +97 1555751344, +97553613356, +974880551</span></td>
    </tr>
      <tr>
        <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
      <tr>
        <td colspan="2"><div align="center" class="style6"><u>SALES INVOICE</u> </div></td>
    </tr>
    </table>  
  </div>
  <div align="center">
    <table width="800" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="50%">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
      <tr>
        <td><div align="left"> <strong>Invoice To:</strong></div></td>
      <td>&nbsp;</td>
    </tr>
      <tr>
        <td><table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr>
            <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
        </tr>
          <tr>
            <td width="10%" valign="top">Name:</td>
          <td width="90%" valign="top">
            {{$invoice_mst[0]->PartyName}}        </td>
        </tr>
          <tr>
            <td valign="top">Address:</td>
          <td valign="top"> 
            {{$invoice_mst[0]->Address}}</td>
        </tr>
          <tr>
            <td valign="top">Contact:</td>
          <td valign="top"><div align="left">  {{$invoice_mst[0]->Phone}}</div></td>
        </tr>
          <tr>
            <td valign="top">Email:</td>
          <td valign="top"><div align="left"> {{$invoice_mst[0]->Email}}</div></td>
        </tr>
          <tr>
            <td>&nbsp;</td>
          <td><div align="left"></div></td>
        </tr>
        </table></td>
      <td valign="top"><table width="47%" border="0" align="right" cellpadding="0" cellspacing="3">
        <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td width="47%" valign="top"><div align="left">Invoice#&nbsp;:</div></td>
          <td width="53%" valign="top">{{$invoice_mst[0]->InvoiceMasterID}}</td>
        </tr>
        <tr>
          <td valign="top"><div align="left">Date :</div></td>
          <td valign="top">{{dateformatman($invoice_mst[0]->Date)}}</td>
        </tr>
        <tr>
          <td valign="top"><div align="left">User&nbsp;:</div></td>
          <td valign="top"><div align="left">YASEEN</div></td>
        </tr>
        
        <tr>
          <td>&nbsp;</td>
          <td><div align="left"></div></td>
        </tr>
        </table></td>
    </tr>
    </table>
  </div>
  <div align="center">
  <table width="800" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="50%">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2"><table width="100%" border="1" cellpadding="0" cellspacing="1" bordercolor="#999999" style="border-collapse:collapse;">
        <tr bgcolor="#CCCCCC">
          <td width="5%" height="25"><div align="center"><strong>S/NO</strong></div></td>
          <td width="14%" height="25" ><div align="center"><strong>DESCRIPTION</strong></div></td>
          <td width="14%" height="25" ><div align="center"><strong>PAX NAME </strong></div></td>
          <td width="9%" height="25" ><div align="center"><strong>PNR</strong></div></td>
          <td width="15%" height="25" ><div align="center"><strong>SECTOR</strong></div></td>
          <td width="10%" height="25" ><div align="center"><strong>FARE</strong></div></td>
          <td width="10%" height="25" ><div align="center"><strong>O/P VAT </strong></div></td>
          <td width="10%" height="25" ><div align="center"><strong>TOTAL</strong></div></td>
        </tr>
        
        <tr >
          <td height="570" valign="top" style="padding: 5px;"> @foreach($invoice_det as $key => $value) <div style="padding: 1px;">{{$key+1}}</div>  <BR><BR> @endforeach</td>
          <td height="570" valign="top" style="padding: 5px;"> @foreach($invoice_det as $key => $value) <div style="padding: 1px;">{{$value->ItemName}}  </div><BR> @endforeach</td>
          <td height="570" valign="top" style="padding: 5px;"> @foreach($invoice_det as $key => $value) <div style="padding: 1px;">{{$value->PaxName}}  </div><BR><BR> @endforeach</td>
          <td height="570" valign="top" style="padding: 5px;"> @foreach($invoice_det as $key => $value) <div style="padding: 1px;">{{$value->PNR}}  </div><BR> <BR>@endforeach</td>
          <td height="570" valign="top" style="padding: 5px;"> @foreach($invoice_det as $key => $value) <div style="padding: 1px;">{{$value->Sector}}  </div><BR> <BR>@endforeach</td>
          <td height="570" valign="top" style="padding: 5px;"> @foreach($invoice_det as $key => $value)<div style="padding: 1px;"> 
            <div align="right">{{number_format($value->Total,2)}} </div>
          </div><BR><BR> @endforeach</td>
          <td height="570" valign="top" style="padding: 5px;"> @foreach($invoice_det as $key => $value)<div style="padding: 1px;"> 
            <div align="right">{{number_format($value->Taxable,2)}} </div>
          </div><BR><BR> @endforeach</td>
          <td height="570" valign="top" style="padding: 5px;"> @foreach($invoice_det as $key => $value) <div style="padding: 1px;">
            <div align="right">{{number_format($value->Total,2)}} </div>
          </div><BR><BR> @endforeach</td>
          </tr>
        
        <tr>
          <td height="25" colspan="5" bgcolor="#CCCCCC" style="padding-left: 10px;" ><strong>TOTAL </strong>
            <div align="center">
            <div align="center">
            <div align="center"></td>
          <td height="25" bgcolor="#CCCCCC" >&nbsp;</td>
          <td width="10%" height="25" bgcolor="#CCCCCC" >&nbsp;</td>
          <td width="10%" height="25" bgcolor="#CCCCCC" ><div align="right"><strong>{{number_format($invoice_mst[0]->Total,2)}}</strong></div></td>
        </tr>
        <tr>
          <td height="25"  colspan="7" style="padding-left: 10px;" ><strong>PREVIOUS BALANCE </strong></td>
     <td height="25" ><div align="right"><strong>{{number_format($invoice_mst[0]->Paid,2)}}</strong></div></td>
   </tr>
        <tr>
          <td height="25"  colspan="7" style="padding-left: 10px;" ><strong>NET PAYABLE {{env('APP_CURRENCY')}} </strong>{{ucwords(convert_number_to_words($invoice_mst[0]->Paid))}} Only/-</td>
     <td height="25" ><div align="right"><strong>{{number_format($invoice_mst[0]->Balance,2)}}</strong></div></td>
   </tr>
      </table></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td valign="top">&nbsp;</td>
    </tr>
  </table>
</div>
</body>
</html>