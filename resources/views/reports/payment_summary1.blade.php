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

      <div>
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


      <?php 
            $DrTotal=0;
            $CrTotal=0;
             ?>
      <div class="card shadow-sm">
        <div class="card-body">
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
           

            <tr>
              <td width="30%">From {{dateformatman2(request()->StartDate)}}  - {{  dateformatman2(request()->EndDate) }}</td>
              <td width="30%">
                <div  align="center"><strong> Payment Summary</strong></div>
              </td>
              <td width="30%">
                <div align="right">DATED: {{ dateformatman2(date('Y-m-d'))}}</div>
              </td>

            </tr>
          </table>
          <br>
          <tr>
            <td colspan="2">
              <div align="left"><strong> Cash Payment </strong></div>
            </td>
            
          </tr>
          <br>
         
          <table class="table table-bordered table-sm">
            <thead class="bg-light">
              <tr>
                <th width="5%" bgcolor="#CCCCCC">
                  <div align="left"><strong>DATE</strong></div>
                </th>
                <th width="5%" bgcolor="#CCCCCC">
                  <div align="left"><strong>Inovice No</strong></div>
                </th>
                <th width="5%" bgcolor="#CCCCCC">
                  <div align="left"><strong>V.NO</strong></div>
                </th>
                <th width="5%" bgcolor="#CCCCCC">
                  <div align="center"><strong>Party Name</strong></div>
                </th>
               
                <th width="10%" bgcolor="#CCCCCC">
                  <div align="center"><strong>Note</strong></div>
                </th>
                <th width="10%" bgcolor="#CCCCCC">
                  <div align="center"><strong>Amount</strong></div>
                </th>

              </tr>
            </thead>
            <tbody>
              @php
              $cash = 0;
              $partyName = null;
              @endphp
              @foreach ($cash_payments as $key => $value)
              @php
              $cash += $value->Paid;

              $party = DB::table('party')
              ->select('PartyID','PartyName')
              ->where('partyID',$value->PartyID)->first();
              @endphp

            <tbody>
              <tr>
                <td>
                  <div align="left">{{dateformatman($value->Date)}}</div>
                </td>
                <td>
                  <div align="left">{{$value->InvoiceMasterID}}</div>
                </td>
                <td>
                  <div align="left">{{$value->Voucher}}</div>
                </td>
                <td>
                  <div align="left">{{$party->PartyName}}</div>
                </td>
              
                <td>
                  <div align="left">{{$value->Note}}</div>
                </td>
                <td>
                  <div align="right">{{$value->Paid}}</div>
                </td>


                {{-- <td>
                  <div align="right">{{number_format(($value->Service),0)}}</div>
                </td> --}}
              </tr>
            </tbody>
            @endforeach
            <tr class="bg-light">
              <td width="5%" bgcolor="#CCCCCC">
                <div align="center"><strong></strong></div>
              </td>
              <td width="5%" bgcolor="#CCCCCC">
                <div align="center"><strong></strong></div>
              </td>
              <td width="5%" bgcolor="#CCCCCC">
                <div align="center"><strong></strong></div>
              </td>
              <td width="10%" bgcolor="#CCCCCC">
                <div align="center"><strong></strong></div>
              </td>
             
              <td width="9%" bgcolor="#CCCCCC">
                <div align="right"><strong>TOTAL</strong></div>
              </td>

              <td width="9%" bgcolor="#CCCCCC">
                <div align="right"><strong>{{number_format($cash,0)}} </strong></div>
              </td>
            </tr>




            </tbody>
          </table>
          <tr>
            <td colspan="2">
              <div align="left"><strong> Bank Payment </strong></div>
            </td>
            <br>
          </tr>
          <table class="table table-bordered table-sm">
            <thead class="bg-light"> 
              <tr>
                <th width="5%" bgcolor="#CCCCCC">
                  <div align="center"><strong>DATE</strong></div>
                </th>
                <th width="5%" bgcolor="#CCCCCC">
                  <div align="center"><strong>Inovice No</strong></div>
                </th>
                <th width="5%" bgcolor="#CCCCCC">
                  <div align="center"><strong>V.NO</strong></div>
                </th>
                <th width="10%" bgcolor="#CCCCCC">
                  <div align="center"><strong>PARTY</strong></div>
                </th>
                <th width="10%" bgcolor="#CCCCCC">
                  <div align="center"><strong>Note</strong></div>
                </th>
                <th width="10%" bgcolor="#CCCCCC">
                  <div align="center"><strong>Amount</strong></div>
                </th>

              </tr>
            </thead>
            <tbody>
              @php
              $bank = 0;
              $partyName = null;
              @endphp
              @foreach ($bank_payments as $key => $value)
              @php
              $bank += $value->Paid;
              $party = DB::table('party')
              ->select('PartyID','PartyName')
              ->where('partyID',$value->PartyID)->first();
              @endphp

            <tbody>
              <tr>
                <td>
                  <div align="left">{{dateformatman($value->Date)}}</div>
                </td>
                <td>
                  <div align="left">{{$value->InvoiceMasterID}}</div>
                </td>
                <td>
                  <div align="left">{{$value->Voucher}}</div>
                </td>
                <td>
                  <div align="left">{{$party->PartyName}}</div>
                </td>
                <td>
                  <div align="left">{{$value->Note}}</div>
                </td>
                <td>
                  <div align="right">{{$value->Paid}}</div>
                </td>


                {{-- <td>
                  <div align="right">{{number_format(($value->Service),0)}}</div>
                </td> --}}
              </tr>
            </tbody>
            @endforeach
            <tr class="bg-light">
              <td width="5%" bgcolor="#CCCCCC">
                <div align="center"><strong></strong></div>
              </td>
              <td width="5%" bgcolor="#CCCCCC">
                <div align="center"><strong></strong></div>
              </td>
              <td width="5%" bgcolor="#CCCCCC">
                <div align="center"><strong></strong></div>
              </td>
              <td width="10%" bgcolor="#CCCCCC">
                <div align="center"><strong></strong></div>
              </td>
              <td width="9%" bgcolor="#CCCCCC">
                <div align="right"><strong>TOTAL</strong></div>
              </td>

              <td width="9%" bgcolor="#CCCCCC">
                <div align="right"><strong>{{number_format($bank,0)}} </strong></div>
              </td>
            </tr>




            </tbody>
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