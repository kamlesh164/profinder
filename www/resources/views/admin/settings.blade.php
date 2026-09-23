@extends('admin.layouts.app')
@section('title')
<title>Settings</title>
@endsection
@section('admin-content')
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Settings</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
            </div>
        </div>

        <div class="section-body">
            <div class="row mt-4">
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-3">
                                        <ul class="nav nav-pills flex-column" id="myTab4" role="tablist">
                                            <li class="nav-item border rounded mb-1">
                                                <a class="nav-link active" id="logo-tab" data-toggle="tab" href="#logoTab" role="tab" aria-controls="logoTab" aria-selected="true">Logo {{-- and Favicon --}}</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-9">
                                        <div class="border rounded">
                                            <div class="tab-content no-padding" id="settingsContent">
                                                <div class="tab-pane fade show active" id="logoTab" role="tabpanel" aria-labelledby="logo-tab">
                                                    <div class="card m-0">
                                                        <div class="card-body">
                                                            <form action="{{ route('admin.update.logo-favicon') }}" method="POST" enctype="multipart/form-data">
                                                                @csrf
                                                                <div class="row">
                                                                    <div class="form-group col-6">
                                                                        <label for="">New Logo</label>
                                                                        <input type="file" name="logo" class="form-control-file">
                                                                    </div>
                                                                    <div class="form-group col-6">
                                                                        <label for="">Existing Logo</label>
                                                                        <div>
                                                                            <img src="{{ asset($setting->logo) }}" alt="" width="200px">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                {{-- <div class="row">
                                                                    <div class="form-group col-6">
                                                                        <label for="">New Logo</label>
                                                                        <input type="file" name="footer_logo" class="form-control-file">
                                                                    </div>
                                                                    <div class="form-group col-6">
                                                                        <label for="">Existing Footer Logo</label>
                                                                        <div>
                                                                            <img src="{{ asset($setting->footer_logo) }}" alt="" width="200px">
                                                                        </div>
                                                                    </div>
                                                                </div> --}}
                                                                {{-- <div class="row">
                                                                    <div class="form-group col-6">
                                                                        <label for="">New Favicon</label>
                                                                        <input type="file" name="favicon" class="form-control-file">
                                                                    </div>
                                                                    <div class="form-group col-6">
                                                                        <label for="">Existing Favicon</label>
                                                                        <div>
                                                                            <img src="{{ asset($setting->favicon) }}" alt="" width="50px">
                                                                        </div>
                                                                    </div>
                                                                </div> --}}
                                                                <button class="btn btn-primary">Update</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>
@endsection
