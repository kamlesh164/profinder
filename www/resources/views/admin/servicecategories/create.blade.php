@extends('admin.layouts.app')
@section('title')
<title>@if(@$data) Update @else Create @endif Service Category</title>
@endsection
@section('admin-content')
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>@if(@$data) Update @else Create @endif Service Category</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.servicecategory.index') }}">Service Category List</a></div>
                <div class="breadcrumb-item">@if(@$data) Update @else Create @endif Service Category</div>
            </div>
        </div>

        <div class="section-body">
            <a href="{{ route('admin.servicecategory.index') }}" class="btn btn-primary"><i class="fas fa-list"></i>Service Category List</a>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="@if(@$data) {{ route('admin.servicecategory.update',$data->id) }} @else {{ route('admin.servicecategory.store') }} @endif" method="POST" enctype="multipart/form-data">
                                @csrf
                                @if(@$data)
                                @method('PUT')
                                @endif
                                <div class="row">
                                    <div class="form-group col-6">
                                        <label>Name <span class="text-danger">*</span></label>
                                        <input type="text" id="name" value="{{old('name',@$data->name)}}" class="form-control" name="name">
                                    </div>
                                    <div class="form-group col-6">
                                        <label>Description </label>
                                        <input type="text" id="description" value="{{old('slug',@$data->description)}}" class="form-control" name="description">
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="form-group col-3">
                                        <label>Image <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control-file" name="image">
                                    </div>
                                    @if(@$data)
                                    <div class="form-group col-3">
                                        <label>Existing Image <span class="text-danger">*</span></label>
                                        <img width="100" height="100" src="{{asset(@$data->image)}}" alt="Category Image">
                                    </div>
                                    @endif
                                    <div class="form-group col-3">
                                        <label>Status <span class="text-danger">*</span></label>
                                        <select name="is_active" class="form-control">
                                            <option value="1">Active</option>
                                            <option value="0">InActive</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-3">
                                        <label>Commission(%) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="commission" value="{{old('commission',@$data->commission)}}" required>
                                    </div>

                                </div>

                                <div class="row float-right">
                                    <div class="col-12">
                                        <button class="btn btn-primary">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>
@endsection
