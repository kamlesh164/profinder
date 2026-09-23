@extends('admin.layouts.app')
@section('title')
<title>@if(@$data) Update @else Create @endif Designation</title>
@endsection
@section('admin-content')
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>@if(@$data) Update @else Create @endif Designation</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.designations.index') }}">Designations List</a></div>
                <div class="breadcrumb-item">@if(@$data) Update @else Create @endif Designation</div>
            </div>
        </div>

        <div class="section-body">
            <a href="{{ route('admin.designations.index') }}" class="btn btn-primary"><i class="fas fa-list"></i> Designations List</a>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="@if(@$data) {{ route('admin.designations.update',$data->id) }} @else {{ route('admin.designations.store') }} @endif" method="POST" enctype="multipart/form-data">
                                @csrf
                                @if(@$data)
                                @method('PUT')
                                @endif
                                <div class="row">
                                    <div class="form-group col-6">
                                        <label>Name <span class="text-danger">*</span></label>
                                        <input type="text" id="name" class="form-control" value="{{old('name',@$data->name)}}" name="name" required>
                                    </div>

                                    <div class="form-group col-6">
                                        <label>Status <span class="text-danger">*</span></label>
                                        <select name="status" class="form-control" required>
                                            <option value="1" {{ old('status', @$data->status) == 1 ? 'selected' : '' }}>
                                                Active
                                            </option>
                                            <option value="0" {{ old('status', @$data->status) == 0 ? 'selected' : '' }}>
                                                InActive
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
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
