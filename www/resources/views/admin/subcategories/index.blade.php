@extends('admin.layouts.app')
@section('title')
<title>{{$title}}</title>
@endsection
@section('breadcrumbs')
<li class="breadcrumb-item active">Subcategory</li>
@endsection
@section('content')
<div class="card shadow rounded">
        <div class="card-header bg-primary text-white d-flex justify-content-between">
          <a href="{{ route('admin.subcategories.create') }}" class="btn btn-success mb-2">Add New SubCategory</a>
        </div>
        <div class="card-body">

          @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
          @endif

          <table class="table table-bordered" id="datatablesSimple">
            <thead>
            <tr>
              <th>#</th>
              <th>Category</th>
              <th>Name</th>
              <th>Slug</th>
              <th>Image</th>
              <th>Actions</th>
            </tr>
          </thead>
            @foreach($subcategories as $service)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $service->category->name }}</td>
              <td>{{ $service->name }}</td>
              <td>{{ $service->slug }}</td>
              <td>
                @if($service->image)
                <img src="{{ asset($service->image) }}" width="80" height="50">
                @else
                N/A
                @endif
              </td>
              <td>
                <div style="display: inline-flex !important; gap: 10px !important;">
                <a href="{{ route('admin.subcategories.edit', $service->id) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('admin.subcategories.destroy', $service->id) }}" method="POST" style="display:inline;">
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