@extends('admin.layouts.app')
@section('title')
<title>@if(@$data) Update @else Create @endif Service</title>
@endsection
@section('admin-content')
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>@if(@$data) Update @else Create @endif Service </h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.servicecategory.index') }}">Service List</a></div>
                <div class="breadcrumb-item">@if(@$data) Update @else Create @endif Service </div>
            </div>
        </div>

        <div class="section-body">
            <a href="{{ route('admin.service.index') }}" class="btn btn-primary"><i class="fas fa-list"></i>Service List</a>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="@if(@$data) {{ route('admin.service.update',$data->id) }} @else {{ route('admin.service.store') }} @endif" method="POST" enctype="multipart/form-data">
                                @csrf
                                @if(@$data)
                                @method('PUT')
                                @endif
                                <div class="row">
                                    <div class="form-group col-6">
                                        <label>Service Professional <span class="text-danger">*</span></label>
                                        <select name="user_id" class="form-control select2" id="" required>
                                            <option value="">Select Service Professional</option>
                                            @foreach($servicePro as $sp)
                                            <option value="{{$sp->id}}" @selected($sp->id == @$data->user_id)>{{$sp->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-6">
                                        <label>Service Category <span class="text-danger">*</span></label>
                                        <select name="service_category_id" class="form-control select2" id="" required>
                                            <option value="">Select Category</option>
                                            @foreach($categories as $category)
                                            <option value="{{$category->id}}" @selected($category->id == @$data->service_category_id)>{{$category->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    <div class="form-group col-4">
                                        <label>Name <span class="text-danger">*</span></label>
                                        <input type="text" id="name" value="{{old('name',@$data->name)}}" class="form-control" name="name">
                                    </div>
                                    <div class="form-group col-4">
                                        <label>State <span class="text-danger">*</span></label>
                                        <select id="stateSelect" class="form-control" name="state_id" required>
                                            <option value="">Select State</option>
                                            @foreach($states as $state)
                                                <option value="{{ $state->id }}"
                {{ old('state_id', @$data->state_id) == $state->id ? 'selected' : '' }}>
                {{ $state->name }}
            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-4">
                                        <label>City <span class="text-danger">*</span></label>
                                        <select id="citySelect" class="form-control" name="city_id" required>
                                            <option value="">Select City</option>
                                        </select>
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
                                        <label>Base Price <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="base_price" value="{{old('base_price',@$data->base_price)}}" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-12">
                                        <label>Description</label>
                                        <input type="text" class="form-control" name="description" value="{{old('description',@$data->description)}}">
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

<script>
    window.onload = function() {

        $('#stateSelect').on('change', function(){
            loadCities($(this).val(), null);
        });

        @if(old('state_id', @$data->state_id))
            loadCities('{{ old('state_id', @$data->state_id) }}', '{{ old('city_id', @$data->city_id) }}');
        @endif
    }

</script>