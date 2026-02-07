@extends('template.tmp')

@section('title', $pagetitle)


@section('content')

    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                @if (session('error'))
                    <div class="alert alert-{{ Session::get('class') }} p-1" id="success-alert">

                        {{ Session::get('error') }}
                    </div>
                @endif

                @if (count($errors) > 0)

                    <div>
                        <div class="alert alert-danger p-2 border-1">
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
                    <div class="card-body">
                        <form action="{{ URL('/SaveParties') }}" method="post">
                            {{ csrf_field() }}
                            <div>
                                <div>

                                    <h4 class="card-title">Add Party / Customer</h4>
                                    <p class="card-title-desc"></p>





                                    <div class="mb-1 row">
                                        <label for="example-url-input"
                                            class="col-md-2 col-form-label fw-bold">Branch</label>
                                        <div class="col-md-4">
                                            <select name="BranchID" class="form-select">
                                                @foreach ($branches as $branch)
                                                    <option value="{{ $branch->id }}"
                                                        {{ old('BranchID') == $branch->id ? 'selected=selected' : '' }}>
                                                        {{ $branch->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-1 row">
                                        <label for="example-url-input" class="col-md-2 col-form-label fw-bold">Party
                                            Type</label>
                                        <div class="col-md-4">
                                            <select name="PartyType" class="form-select">
                                                @foreach ($party_type as $type)
                                                    <option value="{{ $type->PartyCode }}"
                                                        {{ old('PartyType') == $type->PartyCode ? 'selected=selected' : '' }}>
                                                        {{ $type->PartyCode }}-{{ $type->PartyCategory }}
                                                    </option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-1 row">
                                        <label for="example-url-input" class="col-md-2 col-form-label fw-bold">Party
                                            Name</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" value="{{ old('PartyName') }}"
                                                name="PartyName">
                                        </div>

                                    </div>
                                    <div class="mb-1 row">
                                        <label for="example-url-input"
                                            class="col-md-2 col-form-label fw-bold">Address</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="Address"
                                                value="{{ old('Address') }}">
                                        </div>

                                    </div>

                                    <div class="mb-1 row">
                                        <label for="example-url-input" class="col-md-2 col-form-label fw-bold">Phone</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="Phone"
                                                value="{{ old('Phone') }}">
                                        </div>

                                    </div>

                                    <div class="mb-1 row">
                                        <label for="example-url-input" class="col-md-2 col-form-label fw-bold">Email</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="email" name="Email"
                                                value="{{ old('Email') }}">
                                        </div>

                                    </div>
                                    <div class="mb-1 row">
                                        <label for="example-url-input"
                                            class="col-md-2 col-form-label fw-bold">Password</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="text" name="Password"
                                                value="{{ old('Password') }}">
                                        </div>

                                    </div>

                                    <div class="mb-1 row">
                                        <label for="example-url-input" class="col-md-2 col-form-label fw-bold">Invoice Due
                                            Days</label>
                                        <div class="col-md-4">
                                            <input class="form-control" type="number" name="InvoiceDueDays"
                                                value="{{ old('InvoiceDueDays') }}">
                                        </div>

                                    </div>

                                    <div class="mb-1 row">
                                        <label for="example-tel-input"
                                            class="col-md-2 col-form-label fw-bold">Active</label>
                                        <div class="col-md-4">
                                            <select name="Active" class="form-select">


                                                <option value="Yes"
                                                    {{ old('Active') == 'Yes' ? 'selected=selected' : '' }}>Yes</option>
                                                <option value="No"
                                                    {{ old('Active') == 'No' ? 'selected=selected' : '' }}>No</option>




                                            </select>
                                        </div>
                                    </div>














                                </div>

                            </div>







                    </div>
                    <div class="card-footer  ">
                        <button type="submit"
                            class="btn btn-primary me-1 waves-effect waves-float waves-light">Submit</button>




                        <button type="reset" class="btn btn-outline-secondary waves-effect">Reset</button>
                    </div>
                </div>
                <!-- card end here -->
                </form>

                <div class="row">
                    <div class="col-lg-12">

                        <div class="card">

                            <div class="card-body">
                                <h4 class="card-title ">Party / Customer Detail</h4>
                                <!-- <p class="card-title-desc"> Add <code>.table-sm</code> to make tables more compact by cutting cell padding in half.</p>  -->

                                <div class="table-responsive">
                                    <table class="table table-striped  table-sm  m-0" id="student_table">
                                        <thead>
                                            <tr>
                                                <th>Party Code</th>
                                                <th>Type</th>
                                                <th>Name</th>
                                                <th>Branch</th>
                                                <th>Address</th>
                                                <th>Phone</th>
                                                <th>Email</th>

                                                <th>Invoice Due Days</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($party as $value)
                                                <tr>
                                                    <td>{{ $value->PartyID }}</td>
                                                    <td>{{ $value->PartyType }}</td>
                                                    <td>{{ $value->PartyName }}</td>
                                                    <td>{{ $value->branch->name ?? 'N/A' }}</td>
                                                    <!-- Ensure 'name' matches the branch model's attribute -->
                                                    <td>{{ $value->Address }}</td>
                                                    <td>{{ $value->Phone }}</td>
                                                    <td>{{ $value->Email }}</td>
                                                    <td>{{ $value->Active }}</td>
                                                    <td>
                                                        <div class="d-flex gap-1">
                                                            <a href="{{ URL('/PartiesEdit/' . $value->PartyID) }}"
                                                                class="text-secondary">
                                                                <i class="mdi mdi-pencil font-size-15"></i>
                                                            </a>
                                                            <a href="#" class="text-secondary"
                                                                onclick="delete_confirm2('PartiesDelete',{{ $value->PartyID }})">
                                                                <i class="mdi mdi-delete font-size-15"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        </tbody>
                                    </table>



                                </div>
                            </div>
                        </div>



                    </div>

                </div>
            </div>

        </div>
    </div>
    </div>
    <!-- END: Content-->
    <script type="text/javascript">
        $(document).ready(function() {
            $('#student_table').DataTable();
        });
    </script>


@endsection
