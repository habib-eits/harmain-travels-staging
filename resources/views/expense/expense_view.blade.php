<!doctype html>
<html>

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Hello, world!</title>
    <style>
        .font {
            font-family: "Poppins", sans-serif !important;

            border-collapse: separate;
            text-indent: initial;
            white-space: normal;
            line-height: normal;
            font-weight: normal;
            font-size: medium;
            font-style: normal;
            color: -internal-quirk-inherit;

        }
    </style>
</head>

<body>
    <div class="container mt-5">
  

        <div class="row"  >
            <div class="col-md-4">
                <div >
                    <img width="250px" src="{{asset('/documents/'.$company[0]->Logo)}}" alt="">

                </div>

            </div>
            <div class="col-md-8">
                <span class="font"><strong>
                        <h3>{{$company[0]->Name}}</h3>
                    </strong>
                    TRN # {{$company[0]->TRN}},<br>
                    {{$company[0]->Address}}<br>
                    {{$company[0]->Contact}}<br>
                    {{$company[0]->Email}}

                </span>

            </div>
            <div class="col-lg-12">
                <hr>
            </div>
            <div class="mx-auto mt-4" style="width: 50%;">

                <h3 class="font text-uppercase bold font-weight-bold" style="font-size: 22px;">EXPENSE Receipt</h3>
            </div>

            <div class="container">

                <div class="row">
                    <div class="col-8">
                        <table class="table table-borderless">

                            <tbody class="font">
                                <tr>
                                    <td>Payment Date</td>
                                    <th>{{$expense_master[0]->Date	}}</th>
                                </tr>
                                <tr>
                                    <td>Reference Number</td>
                                    <th>{{$expense_master[0]->ReferenceNo	}}</th>
                                </tr>
                                <tr>
                                    <td>Expense No</td>
                                    <th>{{$expense_master[0]->ExpenseNo	}}</th>
                                </tr>
                                
                            </tbody>
                        </table>
                        Bill To:
                        <br>
                        <strong style="font-weight: bold;" class="font">{{$expense_master[0]->SupplierName}}</strong>
                       
                    </div>
                    <div class="col-4">
                        <div class="bg-info text-center pt-4" style="height: 45%; width: 70%; margin-left: -20%;">
                            <span class="font" style="color: white;">
                                Amount Paid <br>
                                {{session::get('Currency')}} {{$expense_master[0]->GrantTotal	}}
                            </span>
                        </div>

                    </div>
                </div>
                <hr>

                 @if(count($expense_detail)>0)        
                <table class="table table-sm align-middle table-nowrap mb-0">
                <tbody><tr class="bg-light">
                <th scope="col" class="col-md-1">S.No</th>
                <th scope="col" class="col-md-7">Expense No</th>
                <th scope="col" class="col-md-3">Expense Account</th>
                <th scope="col" class="col-md-2">Amount</th>
                </tr>
                </tbody>
                <tbody>
                @foreach ($expense_detail as $key =>$value)
                 <tr>
                 <td >{{$key+1}}</td>
                 <td >{{$value->ExpenseNo}}</td>
                 <td >{{$value->ChartOfAccountName}}</td>
                 <td >{{$value->Amount}}</td>
                 </tr>
                 @endforeach   
                 <tr class="font-weight-bolder">
                     <td></td>
                     <td></td>
                     <td>Total</td>
                     <td>{{$expense_master[0]->GrantTotal   }} {{session::get('Currency')}} </td>
                 </tr>
                 </tbody>

                 </table>
                 @else
                   <p class=" text-danger">No data found</p>
                 @endif   

            </div>

 <hr>

 <div style="height: 250px;">.</div>

        </div>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>