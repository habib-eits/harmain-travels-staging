@extends('tmp')

@section('title', $pagetitle)


@section('content')




 



 
<div class="main-content">

  <div class="page-content">
    <div class="container-fluid">




      <!-- start page title -->
      <div class="row">
        <div class="col-12">
          <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Log Activity</h4>



          </div>
        </div>
      </div>


      <div class="card">

        <div class="card-body">
          
 @if(count($log)>0)    
<table class="table table-sm align-middle table-nowrap mb-0">
<tbody><tr>
<th scope="col">S.No</th>
<th scope="col">Employee ID</th>
<th scope="col">Amount</th>
<th scope="col">Date</th>
<th scope="col">Section</th>
<th scope="col">VHNO</th>
<th scope="col">Narration</th>
</tr>
</tbody>
<tbody>
@foreach ($log as $key =>$value)
 <tr>
 <td class="col-md-1">{{$key+1}}</td>
 <td class="col-md-1">{{$value->UserName}}</td>
 <td class="col-md-1">{{$value->Amount}}</td>
 <td class="col-md-1">{{$value->Date}}</td>
 <td class="col-md-1">{{$value->Section}}</td>
 <td class="col-md-1">{{$value->VHNO}}</td>
 <td class="col-md-1">{{$value->Narration}}</td>
 </tr>
 @endforeach   
 </tbody>
 </table>
 @else
   <p class=" text-danger">No data found</p>
 @endif   

    </div>
  </div>

</div>
</div>
</div>
<!-- END: Content-->

@endsection