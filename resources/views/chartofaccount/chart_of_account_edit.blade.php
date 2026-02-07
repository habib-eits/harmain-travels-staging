@extends('template.tmp')

@section('title', $pagetitle)
 

@section('content')

   <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid"><div class="row">

                      <div class="row"><div class="row">
  <div class="col-12">
  
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

  
 <div class="card shadow-sm">
    
  <div class="card-header">
        <h2>Chart of Account</h2>
  </div>
    
   <div class="row">
 
      <div class="col-md-6">  

        <!-- enctype="multipart/form-data" -->
        <form action="{{URL('/ChartOfAccountUpdate')}}" method="post">
         {{csrf_field()}} 
             <input type="hidden" name="ChartOfAccountID" value="{{request()->id}}">

         <div class="card-body">
      <h5 class="mb-3">Level 3</h5>
          <div class="col-md-12 col-sm-12">
               <div class="mb-3 row">
                  <div class="col-sm-3">
                    <label class="col-form-label fw-bold" for="first-name">Parent Head</label>
                  </div>
                  <div class="col-sm-9">
                    <select name="ChartOfAccountIDold" id="ChartOfAccountIDold" class="form-select select2">
                         <?php foreach ($chartofaccount_l2 as $key => $value): ?>
                          <option value="{{$value->ChartOfAccountID}}" {{($value->ChartOfAccountID== $chartofaccount[0]->L2) ? 'selected=selected':'' }}>{{$value->ChartOfAccountID}}-{{$value->ChartOfAccountName}}</option>
                        <?php endforeach ?>
                  
                      </select>
                  </div>
                </div>

                <div class="mb-3 row">
                  <div class="col-sm-3">
                    <label class="col-form-label fw-bold" for="first-name">Chart of Acc</label>
                  </div>
                  <div class="col-sm-9">
                    <input type="text" id="first-name" class="form-control" name="ChartOfAccountName" value="{{$chartofaccount[0]->ChartOfAccountName}}" >
                  </div>
                </div>

               
  <div class="mb-3 row">
                  <div class="col-sm-3">
                    <label class="col-form-label fw-bold" for="first-name">Type ( if Bank/Card)</label>
                  </div>
                  <div class="col-sm-9">
                    <select name="Category" id="Category" class="form-select">
                      <option value="0">Select </option>
                      <option value="CASH">CASH</option>
                      <option value="BANK">BANK</option>
                      <option value="CARD">CARD</option>
                    </select>
                  </div>
                </div>

            
             

              
                


              </div>
               <div class="card-footer bg-transparent">
        
        <div><button type="submit" class="btn btn-success w-sm float-right">Save</button>
             <a href="{{URL('/ChartOfAcc')}}" class="btn btn-secondary w-sm float-right">Cancel</a>
        
        
      </div>
  </div>
      </div>


    </form>


    </div>
   </div>
     
  
  </div>

  

 
  
  </div>
</div>

        </div>
      </div>
    </div>
    <!-- END: Content-->
 
 
  @endsection