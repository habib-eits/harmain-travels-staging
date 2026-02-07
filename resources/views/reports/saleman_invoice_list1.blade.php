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
      <td colspan="2"><div align="center"><h3><strong>INVOICES SELL BY SALEMAN - <span class="text-danger">{{request()->user}}</span> </strong></h3></div></td>
    </tr>
    <tr>
      <td width="50%">From {{dateformatman2(request()->start)}} to {{dateformatman2(request()->end)}}</td>
    <td width="50%"><div align="right">DATED: {{date('d-m-Y')}}</div></td>
    
    </tr>
  </table>
  <table class="table table-bordered table-sm">
    <tr class="bg-light">
        <td width="10%" bgcolor="#CCCCCC"><div align="center"><strong>INVOICE</strong></div></td>
        <td width="10%" bgcolor="#CCCCCC"><div align="center"><strong>DATE</strong></div></td>
        <td width="15%" bgcolor="#CCCCCC"><div align="center"><strong>PARTY NAME</strong></div></td>
        <td width="15%" bgcolor="#CCCCCC"><div align="center"><strong>SALESMAN</strong></div></td>
        <td width="8%" bgcolor="#CCCCCC"><div align="right"><strong>TOTAL</strong></div></td>
        <td width="15%" bgcolor="#CCCCCC"><div align="right"><strong>BALANCE</strong></div></td>
    </tr>

    @php
        $totalSum = 0;
        $balanceSum = 0;
    @endphp

    @foreach ($invoice_master as $key => $value)
        @php
            $totalSum += $value->Total;
            $balanceSum += $value->Balance;
        @endphp


 

        <tr>
            <td><div align="center"> <a target="_blank" href="{{URL('/InvoicePDF/').'/'.$value->InvoiceMasterID}}" >{{ $value->InvoiceCode }}</a> </div></td>
            <td><div align="center">{{ dateformatman($value->Date) }}</div></td>
            <td>{{ $value->PartyName }}</td>
            <td>{{ $value->FullName }}</td>
            <td><div align="right">{{ number_format($value->Total, 2) }}</div></td>
            <td><div align="right">{{ number_format($value->Balance, 2) }}</div></td>
        </tr>
    @endforeach

    <!-- Display Aggregate Totals -->
    <tr class="bg-light">
        <td colspan="4"><strong><div align="right">TOTALS:</div></strong></td>
        <td><div align="right"><strong>{{ number_format($totalSum, 2) }}</strong></div></td>
        <td><div align="right"><strong>{{ number_format($balanceSum, 2) }}</strong></div></td>
    </tr>
</table>


  



  </table>    
      </div>
  </div>
  
  </div>
</div>

        </div>
      </div>
    </div>
    <!-- END: Content-->


<script>
  
   
        function loadPDF(id) {
          alert(id);
       

        $.ajax({
            url: {{URL('/InvoicePDFView/')}}+'/'+id,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
              alert('dd');
                $('#pdfViewModal').modal('show'); // Show the modal after loading content
                $('#pdfContainer').html(response.html); // Update the PDF container with received HTML
            },
            error: function(xhr, status, error) {
                console.error('Error loading PDF:', error);
                alert('Failed to load PDF.');
            }
        });


    }

    
    </script>



<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

    <!-- Modal for PDF View -->
    <div class="modal fade" id="pdfViewModal" tabindex="-1" aria-labelledby="pdfViewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pdfViewModalLabel">Invoice PDF</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="pdfContainer">
                        <!-- PDF content will be loaded her                                                          e -->
                    </div>
                </div>
                <div class="modal-footer">
                    <a title="" class="btn btn-danger" id="print">Print</a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>



 
  @endsection