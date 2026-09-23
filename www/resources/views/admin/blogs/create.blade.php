@extends('admin.layouts.app')
@section('title')
<title>@if(@$data) Update @else Create @endif Blog</title>
@endsection
@section('admin-content')
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>@if(@$data) Update @else Create @endif Blog</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.category.index') }}">Category List</a></div>
                <div class="breadcrumb-item">@if(@$data) Update @else Create @endif Blog</div>
            </div>
        </div>

        <div class="section-body">
            <a href="{{ route('admin.category.index') }}" class="btn btn-primary"><i class="fas fa-list"></i> Blog List</a>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="@if(@$data) {{ route('admin.blogs.update',$data->id) }} @else {{ route('admin.blogs.store') }} @endif" method="POST" enctype="multipart/form-data">
                                @csrf
                                @if(@$data)
                                @method('PUT')
                                @endif
                                <div class="row">
                                    <div class="form-group col-6">
                                        <label>Title <span class="text-danger">*</span></label>
                                        <input type="text" id="name" value="{{old('title',@$data->title)}}" class="form-control" name="name">
                                    </div>
                                    <div class="form-group col-6">
                                        <label>Published At <span class="text-danger">*</span></label>
                                        <input type="text" id="published_at" value="{{old('published_at',@$data->published_at)}}" class="form-control datepicker" name="published_at" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-4">
                                        <label>Image <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control-file" name="image">
                                    </div>
                                    @if(@$data)
                                    <div class="form-group col-4">
                                        <label>Existing Image <span class="text-danger">*</span></label>
                                        <img src="{{asset(@$data->image)}}" alt="Blog Image">
                                    </div>
                                    @endif
                                    <div class="form-group col-4">
                                        <label>Status <span class="text-danger">*</span></label>
                                        <select name="is_active" class="form-control">
                                            <option value="1">Active</option>
                                            <option value="0">InActive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-6">
                                        <label>SEO Title</label>
                                        <input type="text" id="seo_title" value="{{old('seo_title',@$data->seo_title)}}" class="form-control" name="seo_title">
                                    </div>
                                    <div class="form-group col-6">
                                        <label>SEO Description</label>
                                        <textarea class="form-control" name="seo_description"></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-12">
                                        <label>Description <span class="text-danger">*</span></label>
                                        <textarea class="form-control summernote" name="description" required></textarea>
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
