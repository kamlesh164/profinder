@extends('admin.layouts.app')
@section('title')
<title>{{$title}}</title>
@endsection
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.packages.index') }}">{{$title}}</a></li>
<li class="breadcrumb-item active">Index</li>
@endsection
@section('content')
<div class="card shadow rounded">
    <div class="card-header bg-primary text-white d-flex justify-content-between">
        <h3>Add packages</h3>
        <a href="{{ route('admin.packages.index') }}" class="btn btn-secondary">Back</a>
    </div>
    <div class="card-body">
        <form action="@if(!empty($package)) {{ route('admin.packages.update', $package) }} @else {{ route('admin.packages.store') }} @endif" method="POST" enctype="multipart/form-data">
            @csrf
            @if(!empty($package))
            @method('PUT')
            @endif
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name.." value="{{ old('name', @$package->name) }}" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Price <span class="text-danger">*</span></label>
                        <input type="text" name="price" class="form-control" id="price" placeholder="Price" value="{{ old('price', @$package->price) }}" required>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Duration <span class="text-danger">*</span></label>
                        <input type="text" name="duration" class="form-control" id="duration" placeholder="Duration" value="{{ old('duration', @$package->duration) }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Image <span class="text-danger">*</span></label>
                        <input type="file" name="icon" class="form-control" @if(empty($package->icon)) required @endif>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Status <span class="text-danger">*</span></label>
                        <select class="form-control" name="status">
                            <option value="1" @selected(old('status',@$package->status)=="1")>Active</option>
                            <option value="0" @selected(old('status',@$package->status)=="0")>InActive</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Description (Optional)</label>
                        <input type="text" name="description" placeholder="Description..." class="form-control" value="{{ old('description', @$packages->description) }}" id="description" />
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between mt-3">
                <button type="submit" class="btn btn-success">Submit</button>
            </div>
        </form>
    </div>
</div>
@endsection