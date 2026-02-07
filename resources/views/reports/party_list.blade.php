@extends('template.tmp')

@section('title', $pagetitle)
 

@section('content')

@php
$company = DB::table('company')->first();
@endphp

<div class="main-content">

 <div class="page-content">
 <div class="container-fluid">
  <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-print-block d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18"> </h4>
                                         
        <a href="{{URL('/PartyListPDF')}}" class="btn btn-success" target="_blank">View PDF</a>

                                </div>
                            </div>
                        </div>
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

            
            <?php 
            $DrTotal=0;
            $CrTotal=0;
             ?>
  <div class="card">
      <div class="card-body">
         <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="2"><div align="center" class="style1">{{$company->Name}} </div></td>
    </tr>
    <tr>
      <td colspan="2"><div align="center"><strong>LIST OF PARTIES </strong></div></td>
    </tr>
    <tr>
      <td width="50%">DATED: {{date('d-m-Y')}}</td>
      <td width="50%">&nbsp;</td>
    </tr>
  </table>
  <table  class="table table-striped table-sm" >
    <tr>
      <td width="3%" bgcolor="#CCCCCC"><div align="center"><strong>S.NO</strong></div></td>
      <td width="25%" bgcolor="#CCCCCC"><div align="center"><strong>NAME</strong></div></td>
      <td width="36%" bgcolor="#CCCCCC"><div align="center"><strong>ADDRESS</strong></div></td>
      <td width="32%" bgcolor="#CCCCCC"><div align="center"><strong>PHONE/MOBILE NUMBER </strong></div></td>
      <td width="32%" bgcolor="#CCCCCC"><div align="center"><strong>EMAIL </strong></div></td>
    </tr>
   @foreach ($party as $key => $value)
    
    
    <tr>
      <td><div align="center">{{$key+1}}.</div></td>
      <td>{{$value->PartyName}}</td>
      <td>{{$value->Address}}</td>
      <td>{{$value->Phone}}</td>
      <td>{{$value->Email}}</td>
      
    </tr>
@endforeach
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