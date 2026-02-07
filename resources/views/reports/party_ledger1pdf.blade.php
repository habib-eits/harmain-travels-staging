<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>{{$pagetitle}}</title>
    <style type="text/css">
<!--
.style1 {
	font-size: 16px;
	font-weight: bold;
}
body,td,th {
	font-size: 13px;
}
-->


    </style>


    @php
  
$company = DB::table('company')->first();

@endphp


<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>
<body>
<div align="center" class="style1">{{$company->Name}}</div>
<div align="center">{{$party[0]->PartyName}} - {{$party[0]->PartyID}}</div>
<div align="center">Contact : {{$party[0]->Phone}}</div>
<div align="center">From {{session::get('StartDate')}} TO {{session::get('EndDate')}}
    </div>
 
        <p>
          <?php 
            $DrTotal=0;
            $CrTotal=0;
          
		       ?>
          @if(count($journal)>0)    
<table width="100%" border="1" align="center" cellpadding="3" cellspacing="0" style="border-collapse: collapse; font-size: 12px;">
    <thead style="background-color: #a6a6a6; text-align: center;">
        <tr>
            <th style="width: 8%;">DATE</th>
            <th style="width: 8%;">VHNO</th>
            <th style="width: 10%;">Type</th>
            <th style="width: 40%;">Description</th>
            <th style="width: 10%;">DR</th>
            <th style="width: 10%;">CR</th>
            <th style="width: 10%;">Balance</th>
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
            <td style="text-align: right;">
                {{ number_format($sql[0]->Balance, 2) }}
            </td>
        </tr>

        @php
            $balance = $sql[0]->Balance;
            $DrTotal = 0;
            $CrTotal = 0;
        @endphp

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

        <tr style="font-weight: bold;">
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
		   
		   
</body>
</html>