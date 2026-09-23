@extends('admin.layouts.app')
@section('title')
<title>{{$title}}</title>
@endsection
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.subcategories.index') }}">{{$title}}</a></li>
<li class="breadcrumb-item active">Index</li>
@endsection
@section('content')
<div class="card shadow rounded">
        <div class="card-header bg-primary text-white d-flex justify-content-between">
          <h3>Add Category</h3>
          <a href="{{ route('admin.subcategories.index') }}" class="btn btn-secondary">Back</a>
        </div>
        <div class="card-body">
          @if(session('error'))
          <div class="alert alert-danger">{{ session('error') }}</div>
          @endif
          @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
          @endif
          <form action="@if(!empty($subcategory)) {{ route('admin.subcategories.update', $subcategory) }} @else {{ route('admin.subcategories.store') }} @endif" method="POST" enctype="multipart/form-data">
            @csrf
              @if(!empty($subcategory))
         @method('PUT')
         @endif
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                       <label>Select Category <span class="text-danger">*</span></label>
                       <select class="form-control" name="category_id">
                           @foreach($categories ?? '' as $category)
                           <option value="{{ $category->id }}" @selected(old('category_id', @$subcategory->category_id)==$category->id)>{{ $category->name??'NA' }}</option>
                           @endforeach
                       </select>
                     </div>
                </div>
                
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="form-group">
                       <label>Name <span class="text-danger">*</span></label>
                       <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name.." value="{{ old('name', @$subcategory->name) }}" required>
                     </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                       <label>Image <span class="text-danger">*</span></label>
                       <input type="file" name="image" class="form-control" @if(empty($subcategory->image)) required @endif>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="form-group">
                       <label>Status <span class="text-danger">*</span></label>
                       <select class="form-control" name="status">
                           <option value="1" @selected(old('status',@$subcategory->status)=="1")>Active</option>
                           <option value="0" @selected(old('status',@$subcategory->status)=="0")>InActive</option>
                       </select>
                    </div>
                </div>
                 <div class="col-md-6">
                    <div class="form-group">
                       <label>Description (Optional)</label>
                       <input type="text" name="description" placeholder="Description..." class="form-control" value="{{ old('description', @$subcategory->description) }}" id="description" />
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