@extends('admin.layouts.app')
@section('title')
<title>{{$title}}</title>
@endsection
@section('breadcrumbs')
<li class="breadcrumb-item active">Package</li>
@endsection
@section('content')
<div class="card shadow rounded">
    <div class="card-header bg-primary text-white d-flex justify-content-between">
        <a href="{{ route('admin.packages.create') }}" class="btn btn-success mb-2">Add New Package</a>
    </div>
    <div class="card-body">

        <table class="table table-bordered" id="datatablesSimple">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Duration</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            @foreach($packages as $package)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    @if($package->icon)
                    <img src="{{ asset($package->icon) }}" width="50" height="50">
                    @else
                    N/A
                    @endif
                </td>
                <td>{{ $package->name }}</td>
                <td>{{ $package->price }}</td>
                <td>{{ $package->duration }}</td>
                <td>
                    <span class="btn btn-sm btn-{{$package->status == 1 ? 'success':'danger'}}">{{$package->status == 1 ? 'Active':'InActive'}}</span>
                 
                </td>

                <td>
                    <div style="display: inline-flex !important; gap: 10px !important;">
                        <a href="{{ route('admin.packages.edit', $package->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.packages.destroy', $package->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection