@extends('template.tmp')

@section('title', $pagetitle)
 

@section('content')



<div class="main-content">

 <div class="page-content">
 <div class="container-fluid">
  <!-- start page title -->
                         
 @if (session('error'))

 <div class="alert alert-{{ Session::get('class') }} p-1" id="success-alert">
                    
                   {{ Session::get('error') }}  
                </div>

@endif

 @if (count($errors) > 0)
                                 
                            <div >
                <div class="alert alert-danger p-1   border-3">
                   <p class="font-weight-bold"> There were some problems with your input.</p>
                    <ul>
                        
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>

                        @endforeach
                    </ul>
                </div>
                </div>
 
            @endif

            
            
  <div class="card">
      <div class="card-body">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="2"><div align="center" class="style1"> </div></td>
    </tr>
    <tr>
      <td colspan="2"><div align="center"><strong>Itemwise Sales Report </strong></div></td>
    </tr>
    <tr>
      <td width="50%"></td>
    <td width="50%"><div align="right">DATED: {{date('d-m-Y')}}</div></td>
    
    </tr>
  </table>
  <table class="table table-bordered table-sm">
    <tr class="bg-light">
      <td width="5%" bgcolor="#CCCCCC"><div align="center"><strong>S.NO</strong></div></td>
      <td width="10%" bgcolor="#CCCCCC"><div align="center"><strong>SALEMAN</strong></div></td>
      <td width="3%" bgcolor="#CCCCCC"><div align="center"><strong>TOTAL</strong></div></td>
      <td width="5%" bgcolor="#CCCCCC"><div align="center"><strong>APPROVAL</strong></div></td>
      <td width="5%" bgcolor="#CCCCCC"><div align="right"><strong> Covid</strong></div></td>
      <td width="5%" bgcolor="#CCCCCC"><div align="right"><strong> V1</strong></div></td>
      <td width="5%" bgcolor="#CCCCCC"><div align="right"><strong>V2 </strong></div></td>
      <td width="5%" bgcolor="#CCCCCC"><div align="right"><strong>V3 </strong></div></td>
      <td width="5%" bgcolor="#CCCCCC"><div align="right"><strong>V4 </strong></div></td>
      <td width="5%" bgcolor="#CCCCCC"><div align="right"><strong>V5 </strong></div></td>
      <td width="5%" bgcolor="#CCCCCC"><div align="right"><strong>V6 </strong></div></td>
      <td width="5%" bgcolor="#CCCCCC"><div align="right"><strong>FREELANCER </strong></div></td>
      <td width="5%" bgcolor="#CCCCCC"><div align="right"><strong>HOTEL </strong></div></td>
      <td width="5%" bgcolor="#CCCCCC"><div align="right"><strong>KSA </strong></div></td>
      <td width="5%" bgcolor="#CCCCCC"><div align="right"><strong>SAFARI </strong></div></td>
      <td width="5%" bgcolor="#CCCCCC"><div align="right"><strong>TICKET </strong></div></td>
      <td width="5%" bgcolor="#CCCCCC"><div align="right"><strong>VISA </strong></div></td>
      <td width="5%" bgcolor="#CCCCCC"><div align="right"><strong>UMRAH </strong></div></td>
      <td width="5%" bgcolor="#CCCCCC"><div align="right"><strong>S1 </strong></div></td>
      <td width="5%" bgcolor="#CCCCCC"><div align="right"><strong>GT </strong></div></td>
    </tr>
@php
    $totalApproval = 0;
    $totalCovid = 0;
    $totalV1 = 0;
    $totalV2 = 0;
    $totalV3 = 0;
    $totalV4 = 0;
    $totalV5 = 0;
    $totalV6 = 0;
    $totalFreelancer = 0;
    $totalHotel = 0;
    $totalKSA = 0;
    $totalSafari = 0;
    $totalTicket = 0;
    $totalVisa = 0;
    $totalUmrah = 0;
    $totalS1 = 0;
    $totalGT = 0;
@endphp

@foreach ($item_detail as $key => $value)
    @php
        $totalApproval += $value->Approval;
        $totalCovid += $value->Covid;
        $totalV1 += $value->V1;
        $totalV2 += $value->V2;
        $totalV3 += $value->V3;
        $totalV4 += $value->V4;
        $totalV5 += $value->V5;
        $totalV6 += $value->V6;
        $totalFreelancer += $value->Freelancer;
        $totalHotel += $value->Hotel;
        $totalKSA += $value->KSA;
        $totalSafari += $value->Safari;
        $totalTicket += $value->Ticket;
        $totalVisa += $value->Visa;
        $totalUmrah += $value->Umrah;
        $totalS1 += $value->S1;
        $totalGT += $value->GT;
    @endphp

    <tr>
      <td><div align="center">{{$key+1}}.</div></td>
      <td>{{$value->SalemanName}}</td>
      <td>{{$value->Total}}</td>
      <td><div align="center">{{ $value->Approval > 0 ? number_format($value->Approval, 2) : '' }}</div></td>
      <td><div align="right">{{ $value->Covid > 0 ? number_format($value->Covid, 2) : '' }}</div></td>
      <td><div align="right">{{ $value->V1 > 0 ? number_format($value->V1, 2) : '' }}</div></td>
      <td><div align="right">{{ $value->V2 > 0 ? number_format($value->V2, 2) : '' }}</div></td>
      <td><div align="right">{{ $value->V3 > 0 ? number_format($value->V3, 2) : '' }}</div></td>
      <td><div align="right">{{ $value->V4 > 0 ? number_format($value->V4, 2) : '' }}</div></td>
      <td><div align="right">{{ $value->V5 > 0 ? number_format($value->V5, 2) : '' }}</div></td>
      <td><div align="right">{{ $value->V6 > 0 ? number_format($value->V6, 2) : '' }}</div></td>
      <td><div align="right">{{ $value->Freelancer > 0 ? number_format($value->Freelancer, 2) : '' }}</div></td>
      <td><div align="right">{{ $value->Hotel > 0 ? number_format($value->Hotel, 2) : '' }}</div></td>
      <td><div align="right">{{ $value->KSA > 0 ? number_format($value->KSA, 2) : '' }}</div></td>
      <td><div align="right">{{ $value->Safari > 0 ? number_format($value->Safari, 2) : '' }}</div></td>
      <td><div align="right">{{ $value->Ticket > 0 ? number_format($value->Ticket, 2) : '' }}</div></td>
      <td><div align="right">{{ $value->Visa > 0 ? number_format($value->Visa, 2) : '' }}</div></td>
      <td><div align="right">{{ $value->Umrah > 0 ? number_format($value->Umrah, 2) : '' }}</div></td>
      <td><div align="right">{{ $value->S1 > 0 ? number_format($value->S1, 2) : '' }}</div></td>
      <td><div align="right">{{ $value->GT > 0 ? number_format($value->GT, 2) : '' }}</div></td>
    </tr>
@endforeach

<tr style="font-weight: bolder;">
  <td colspan="3" align="center" ><strong>Grand Total</strong></td>
  <td><div align="center">{{ $totalApproval > 0 ? number_format($totalApproval, 2) : '' }}</div></td>
  <td><div align="right">{{ $totalCovid > 0 ? number_format($totalCovid, 2) : '' }}</div></td>
  <td><div align="right">{{ $totalV1 > 0 ? number_format($totalV1, 2) : '' }}</div></td>
  <td><div align="right">{{ $totalV2 > 0 ? number_format($totalV2, 2) : '' }}</div></td>
  <td><div align="right">{{ $totalV3 > 0 ? number_format($totalV3, 2) : '' }}</div></td>
  <td><div align="right">{{ $totalV4 > 0 ? number_format($totalV4, 2) : '' }}</div></td>
  <td><div align="right">{{ $totalV5 > 0 ? number_format($totalV5, 2) : '' }}</div></td>
  <td><div align="right">{{ $totalV6 > 0 ? number_format($totalV6, 2) : '' }}</div></td>
  <td><div align="right">{{ $totalFreelancer > 0 ? number_format($totalFreelancer, 2) : '' }}</div></td>
  <td><div align="right">{{ $totalHotel > 0 ? number_format($totalHotel, 2) : '' }}</div></td>
  <td><div align="right">{{ $totalKSA > 0 ? number_format($totalKSA, 2) : '' }}</div></td>
  <td><div align="right">{{ $totalSafari > 0 ? number_format($totalSafari, 2) : '' }}</div></td>
  <td><div align="right">{{ $totalTicket > 0 ? number_format($totalTicket, 2) : '' }}</div></td>
  <td><div align="right">{{ $totalVisa > 0 ? number_format($totalVisa, 2) : '' }}</div></td>
  <td><div align="right">{{ $totalUmrah > 0 ? number_format($totalUmrah, 2) : '' }}</div></td>
  <td><div align="right">{{ $totalS1 > 0 ? number_format($totalS1, 2) : '' }}</div></td>
  <td><div align="right">{{ $totalGT > 0 ? number_format($totalGT, 2) : '' }}</div></td>
</tr>

  </table>       
      </div>
  </div>
  
  </div>
</div>

        </div>
      </div>
    </div>
    <!-- END: Content-->
 
  @endsection