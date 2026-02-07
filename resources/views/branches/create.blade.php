@extends('template.tmp')
@section('title', 'Branch Create')
@section('content')
    <div class="content-wrapper">
        <div class="row" style="height: 81vh; overflow: auto;">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h3 class="text-info">Add Branch Details</h3>
                            </div>
                            <div class="col d-flex justify-content-end">
                                <a href="{{ url('branches') }}" class="btn btn-primary">Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <small class="text-primary"><em>Input fields marked with * are the required fields</em></small>
                        <hr>
                        <form action="{{ url('storebranch') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-4">
                                    <label for="name"><strong>Name: *</strong></label>
                                    <input type="text" name="name" id="name" required class="form-control" value="{{ old('name') }}">
                                </div>
                                <div class="col-4">
                                    <label for="location"><strong>Location:</strong></label>
                                    <input type="text" name="location" id="location" class="form-control" value="{{ old('location') }}">
                                </div>
                                <div class="col-4">
                                    <label for="tel"><strong>Contact Number:</strong></label>
                                    <input type="tel" name="tel" id="tel" class="form-control" value="{{ old('tel') }}">
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
