@extends('template.tmp')
@section('content')
@section('content')
   <div class="main-content">

 <div class="page-content">


<div >
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-plane"></i> Airlines Management
                    </h3>
                    <a href="{{ route('airlines.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Airline
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Country</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($airlines as $airline)
                                    <tr>
                                        <td>{{ $airline->id }}</td>
                                        <td>{{ $airline->name }}</td>
                                        <td>{{ $airline->code ?? 'N/A' }}</td>
                                        <td>{{ $airline->country ?? 'N/A' }}</td>
                                        <td>
                                            @if($airline->is_active)
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>{{ $airline->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('airlines.show', $airline) }}" class="btn btn-info btn-sm" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('airlines.edit', $airline) }}" class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('airlines.destroy', $airline) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this airline?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No airlines found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($airlines->hasPages())
                        <div class="d-flex justify-content-center">
                            {{ $airlines->links() }}
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
