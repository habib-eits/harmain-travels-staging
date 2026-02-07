@extends('template.tmp')
@section('title', 'Satff Create')
@section('content')
    <div class="content-wrapper">
        <div class="row" style="height: 81vh; overflow: auto;">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h3 class="text-info">Add Staff Member Details</h3>
                            </div>
                            <div class="col d-flex justify-content-end">
                                <a href="{{ url('staff') }}" class="btn btn-primary">Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <small class="text-primary"><em>Input fields marked with * are the required fields</em></small>
                        <hr>
                        <form action="{{ url('storestaffmember') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-4">
                                    <label for="name"><strong>Full Name: *</strong></label>
                                    <input type="text" name="name" id="name" required class="form-control" value="{{ old('name') }}">
                                </div>
                                <div class="col-4">
                                    <label for="email"><strong>Email: *</strong></label>
                                    <input type="email" name="email" id="email" required class="form-control" value="{{ old('email') }}">
                                </div>
                                <div class="col-4">
                                    <label for="tel"><strong>Contact Number:</strong></label>
                                    <input type="tel" name="tel" id="tel" class="form-control" value="{{ old('tel') }}">
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-4">
                                    <label for="role"><strong>Role: *</strong></label>
                                    <select name="role" id="role" class="form-control" required>
                                        <option value="" selected disabled>--Select Role--</option>
                                        @foreach ($roles as $item)
                                            <option value="{{ $item->name }}" {{ old('role') == $item->name ? 'selected' : '' }}> {{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-4">
                                    <label for="branch_id"><strong>Branch: *</strong></label>
                                    <select name="branch_id" id="branch_id" class="form-control" required>
                                        <option value="" selected disabled>--Select Branch--</option>
                                        @foreach ($branches as $item)
                                            <option value="{{ $item->id }}" {{ old('branch_id') == $item->id ? 'selected' : '' }}> {{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mt-2">
                                <button type="submit" class="btn btn-gradient-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@endsection
