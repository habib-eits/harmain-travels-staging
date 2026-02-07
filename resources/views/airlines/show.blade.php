@extends('template.tmp')

@section('content')
@section('content')
   <div class="main-content">

 <div class="page-content">
 
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plane"></i> Airline Details: {{ $airline->name }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('airlines.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Airlines
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="30%">ID:</th>
                                        <td>{{ $airline->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Name:</th>
                                        <td>{{ $airline->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Code:</th>
                                        <td>{{ $airline->code ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Country:</th>
                                        <td>{{ $airline->country ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status:</th>
                                        <td>
                                            @if($airline->is_active)
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-danger">Inactive</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Created At:</th>
                                        <td>{{ $airline->created_at->format('M d, Y H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Updated At:</th>
                                        <td>{{ $airline->updated_at->format('M d, Y H:i:s') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Actions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('airlines.edit', $airline) }}" class="btn btn-warning">
                                            <i class="fas fa-edit"></i> Edit Airline
                                        </a>
                                        <form action="{{ route('airlines.destroy', $airline) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this airline?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger w-100">
                                                <i class="fas fa-trash"></i> Delete Airline
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($airline->description)
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Description</h5>
                                    </div>
                                    <div class="card-body">
                                        <p>{{ $airline->description }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
@endsection
