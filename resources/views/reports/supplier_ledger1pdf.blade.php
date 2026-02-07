<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Party List</title>
     
 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"><style type="text/css">
<!--
.style1 {font-weight: bold}

-->
</style>

<style>
    @page {
        margin-top: 30px;
        margin-right: 20px;
        margin-bottom: 30px;
        margin-left: 20px;
    }

    body {
        font-family: DejaVu Sans, sans-serif; /* Ensure Unicode/Urdu compatibility */
        font-size: 12px;
    }
</style>

</head>
<body>
<?php 
            $DrTotal=0;
            $CrTotal=0;
             ?>


             @php
    $company = DB::table('company')->first();
             @endphp
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="2"><div align="center" class="style1">{{$company->Name}} </div></td>
  </tr>
  <tr>
    <td colspan="2"><div align="center"><strong>PARTY LEDGER </strong></div></td>
  </tr>
  <tr>
    <td colspan="2"><div align="center">{{$supplier[0]->PartyID}}-{{$supplier[0]->PartyName}}</div></td>
  </tr>
  <tr>
    <td width="50%">DATED: {{date('d-m-Y')}}</td>
    <td width="50%"><div align="right">From {{request()->StartDate}} TO {{request()->EndDate}}</div></td>
  </tr>
</table>
<p>@if(count($journal)>0) </p>
@php
    $balance = $sql[0]->Balance;
    $DrTotal = 0;
    $CrTotal = 0;
@endphp

<table width="100%" border="1" cellpadding="4" cellspacing="0" style="border-collapse: collapse; font-size: 12px;">
    <thead style="background-color: #a6a6a6;">
        <tr>
            <th style="width: 10%; text-align: center;">DATE</th>
            <th style="width: 10%; text-align: center;">VHNO</th>
            <th style="width: 10%; text-align: center;">Type</th>
            <th style="width: 40%; text-align: center;">Description</th>
            <th style="width: 10%; text-align: right;">DR</th>
            <th style="width: 10%; text-align: right;">CR</th>
            <th style="width: 10%; text-align: right;">Balance</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>Opening Balance</td>
            <td></td>
            <td></td>
            <td style="text-align: right;">{{ number_format($balance, 2) }}</td>
        </tr>

        @foreach ($journal as $value)
            @php
                $balance += ($value->Dr - $value->Cr);
                $DrTotal += $value->Dr;
                $CrTotal += $value->Cr;
                $balanceLabel = $balance > 0 ? 'DR' : ($balance < 0 ? 'CR' : '');
            @endphp
            <tr>
                <td style="text-align: center;">{{ dateformatman($value->Date) }}</td>
                <td style="text-align: center;">{{ $value->VHNO }}</td>
                <td style="text-align: center;">{{ $value->JournalType }}</td>
                <td>{{ $value->Narration }}</td>
                <td style="text-align: right;">{{ $value->Dr == 0 ? '' : number_format($value->Dr, 2) }}</td>
                <td style="text-align: right;">{{ $value->Cr == 0 ? '' : number_format($value->Cr, 2) }}</td>
                <td style="text-align: right;">
                    {{ number_format(abs($balance), 2) }} {{ $balanceLabel }}
                </td>
            </tr>
        @endforeach

        <tr style="font-weight: bold; background-color: #f0f0f0;">
            <td></td>
            <td></td>
            <td style="text-align: center;">TOTAL</td>
            <td></td>
            <td style="text-align: right;">{{ number_format($DrTotal, 2) }}</td>
            <td style="text-align: right;">{{ number_format($CrTotal, 2) }}</td>
            <td style="text-align: right;">{{number_format($DrTotal-$CrTotal,2)}}</td>

        </tr>
    </tbody>
</table>

  @else
           <p class=" text-danger">No data found</p>
         @endif 
<p>&nbsp;</p>
</body>
</html>