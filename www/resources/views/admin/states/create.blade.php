@extends('admin.layouts.app')
@section('title')
<title>@if(@$data) Update @else Create @endif State</title>
@endsection
@section('admin-content')
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>@if(@$data) Update @else Create @endif State</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.designations.index') }}">State List</a></div>
                <div class="breadcrumb-item">@if(@$data) Update @else Create @endif State</div>
            </div>
        </div>

        <div class="section-body">
            <a href="{{ route('admin.states.index') }}" class="btn btn-primary"><i class="fas fa-list"></i> State List</a>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="@if(@$data) {{ route('admin.states.update',$data->id) }} @else {{ route('admin.states.store') }} @endif" method="POST" enctype="multipart/form-data">
                                @csrf
                                @if(@$data)
                                @method('PUT')
                                @endif
                                <div class="row">
                                    <div class="form-group col-6">
                                        <label>Name <span class="text-danger">*</span></label>
                                        <input type="text" id="name" class="form-control" value="{{old('name',@$data->name)}}" name="name" required>
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
