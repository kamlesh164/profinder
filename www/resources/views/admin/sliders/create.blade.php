@extends('admin.layouts.app')
@section('title')
<title>@if(@$data) Update @else Create @endif Slider</title>
@endsection
@section('admin-content')
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>@if(@$data) Update @else Create @endif Slider</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.designations.index') }}">Sliders List</a></div>
                <div class="breadcrumb-item">@if(@$data) Update @else Create @endif Slider</div>
            </div>
        </div>

        <div class="section-body">
            <a href="{{ route('admin.sliders.index') }}" class="btn btn-primary"><i class="fas fa-list"></i> Sliders List</a>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="@if(@$data) {{ route('admin.sliders.update',$data->id) }} @else {{ route('admin.sliders.store') }} @endif" method="POST" enctype="multipart/form-data">
                                @csrf
                                @if(@$data)
                                @method('PUT')
                                @endif
                                <div class="row">
                                    <div class="form-group col-6">
                                        <label>Slider Image</label>
                                        <input type="file" id="image" class="form-control"  name="image">
                                    </div>
                                    @if(@$data)
                                    <div class="form-group col-6">
                                        <label>Existing Image <span class="text-danger">*</span></label>
                                        <img src="{{asset(@$data->image)}}" alt="{{@$data->name}}" width="200" height="100">
                                    </div>
                                    @endif
                                </div>
                                <div class="row">
                                    <div class="form-group col-4">
                                        <label>Name <span class="text-danger">*</span></label>
                                        <input type="text" id="name" class="form-control" value="{{old('name',@$data->name)}}" name="name" required>
                                    </div>

                                    <div class="form-group col-4">
                                        <label>{{__('admin.Status')}} <span class="text-danger">*</span></label>
                                        <select name="is_active" class="form-control" required>
                                            <option value="1" {{ old('is_active', @$data->is_active) == 1 ? 'selected' : '' }}>
                                                {{ __('admin.Active') }}
                                            </option>
                                            <option value="0" {{ old('is_active', @$data->is_active) == 0 ? 'selected' : '' }}>
                                                {{ __('admin.InActive') }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group col-4">
                                        <label>Type <span class="text-danger">*</span></label>
                                        <select name="type" class="form-control" required>
                                            <option value="1" {{ old('type', @$data->type) == 1 ? 'selected' : '' }}>
                                                Primary
                                            </option>
                                            <option value="2" {{ old('type', @$data->type) == 2 ? 'selected' : '' }}>
                                                Secondary
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <button class="btn btn-primary">{{__('admin.Save')}}</button>
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
