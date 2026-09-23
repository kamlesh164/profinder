@extends('admin.layouts.app')
@section('title')
<title>@if(@$data) Update @else Create @endif Category</title>
@endsection
@section('admin-content')
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>@if(@$data) Update @else Create @endif Category</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.category.index') }}">Category List</a></div>
                <div class="breadcrumb-item">@if(@$data) Update @else Create @endif Category</div>
            </div>
        </div>

        <div class="section-body">
            <a href="{{ route('admin.category.index') }}" class="btn btn-primary"><i class="fas fa-list"></i> Category List</a>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="@if(@$data) {{ route('admin.category.update',$data->id) }} @else {{ route('admin.category.store') }} @endif" method="POST" enctype="multipart/form-data">
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
                                        <label>Slug <span class="text-danger">*</span></label>
                                        <input type="text" id="slug" value="{{old('slug',@$data->slug)}}" class="form-control" name="slug">
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="form-group col-3">
                                        <label>Icon <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control-file" name="icon">
                                    </div>
                                    @if(@$data)
                                    <div class="form-group col-3">
                                        <label>Existing Icon <span class="text-danger">*</span></label>
                                        <img src="{{asset(@$data->icon)}}" alt="Category Image">
                                    </div>
                                    @endif
                                    <div class="form-group col-3">
                                        <label>Status <span class="text-danger">*</span></label>
                                        <select name="status" class="form-control">
                                            <option value="1" @selected(old('status',@$data->status)=="1")>Active</option>
                                            <option value="0" @selected(old('status',@$data->status)=="0")>InActive</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-3">
                                        <label>Commission <span class="text-danger">*</span></label>
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

<script>
    (function($) {
        "use strict";
        $(document).ready(function() {
            $("#name").on("focusout", function(e) {
                $("#slug").val(convertToSlug($(this).val()));
            })
        });
    })(jQuery);

    function convertToSlug(Text) {
        return Text
            .toLowerCase()
            .replace(/[^\w ]+/g, '')
            .replace(/ +/g, '-');
    }
</script>
@endsection
