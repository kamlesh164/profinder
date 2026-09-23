@extends('admin.layouts.app')
@section('title')
<title>{{ @$data ? 'Edit Plan' : 'Create Plan' }}</title>
@endsection
@section('admin-content')

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>{{ @$data ? 'Edit Plan' : 'Create Plan' }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active">
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                </div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header" style="justify-content: start">
                            <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-primary">
                                <i class="fa fa-arrow-left"></i> Go Back
                            </a>
                        </div>

                        <div class="card-body">
                            <form
                                action="@if(@$data) {{ route('admin.subscriptions.update', $data->id) }} @else {{ route('admin.subscriptions.store') }} @endif"
                                method="POST"
                                enctype="multipart/form-data"
                            >
                                @csrf
                                @if(@$data)
                                    @method('PUT')
                                @endif

                                <div class="row">

                                    {{-- Icon --}}
                                    {{-- <div class="form-group col-6">
                                        <label>Icon <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control-file" name="icon">
                                    </div>

                                    @if(@$data && $data->icon)
                                        <div class="form-group col-6">
                                            <label>Existing Icon</label>
                                            <br>
                                            <img src="{{ asset($data->icon) }}" width="80" height="80" alt="Icon">
                                        </div>
                                    @endif --}}

                                    {{-- Plan name --}}
                                    <div class="form-group col-md-6">
                                        <label>Plan Name <span class="text-danger">*</span></label>
                                        <input type="text"
                                               name="plan_name"
                                               value="{{ old('plan_name', @$data->plan_name) }}"
                                               class="form-control form_control"
                                               placeholder="Basic / Plus / Professional / Premier">
                                    </div>

                                    {{-- Plan price (monthly) --}}
                                    <div class="form-group col-md-6">
                                        <label>
                                            Plan Price (INR)
                                            <span data-toggle="tooltip"
                                                  data-placement="top"
                                                  class="fa fa-info-circle text--primary"
                                                  title="For free plan use 0">
                                            </span>
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="number"
                                               name="plan_price"
                                               value="{{ old('plan_price', @$data->plan_price) }}"
                                               class="form-control form_control"
                                               min="0"
                                               placeholder="e.g. 399">
                                    </div>

                                    {{-- Expiration (billing type) --}}
                                    <div class="form-group col-md-6">
                                        <label>Billing Type <span class="text-danger">*</span></label>
                                        <select name="expiration_date" class="form-control">
                                            <option value="monthly" @selected(@$data->expiration_date == 'monthly')>Monthly</option>
                                            <option value="yearly"  @selected(@$data->expiration_date == 'yearly')>Yearly</option>
                                        </select>
                                    </div>

                                    {{-- Bids --}}
                                    <div class="form-group col-md-2">
                                        <label>
                                            Bids / Month
                                            <span data-toggle="tooltip"
                                                  data-placement="top"
                                                  class="fa fa-info-circle text--primary"
                                                  title="For unlimited bids use -1">
                                            </span>
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="number"
                                               name="bids"
                                               value="{{ old('bids', @$data->bids) }}"
                                               class="form-control form_control">
                                    </div>

                                    {{-- Skills --}}
                                    <div class="form-group col-md-2">
                                        <label>
                                            Skills Limit
                                            <span data-toggle="tooltip"
                                                  data-placement="top"
                                                  class="fa fa-info-circle text--primary"
                                                  title="For unlimited skills use -1">
                                            </span>
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="number"
                                               name="skills"
                                               value="{{ old('skills', @$data->skills) }}"
                                               class="form-control form_control">
                                    </div>

                                    {{-- Serial --}}
                                    <div class="form-group col-md-6">
                                        <label>Serial <span class="text-danger">*</span></label>
                                        <input type="number"
                                               name="serial"
                                               value="{{ old('serial', @$data->serial) }}"
                                               class="form-control form_control"
                                               placeholder="1 for Basic, 2 for Plus, ...">
                                    </div>

                                    {{-- Status --}}
                                    <div class="form-group col-md-6">
                                        <label>Status <span class="text-danger">*</span></label>
                                        <select name="status" class="form-control">
                                            <option value="1" @selected(@$data->status == 1)>Active</option>
                                            <option value="0" @selected(@$data->status == 0)>Inactive</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label>Plan Features</label>

                                        <div id="features-wrapper">
                                            @php
                                                $features = old('features', @$data->features_json ?? []);
                                            @endphp

                                            @foreach($features as $key => $value)
                                                <div class="form-row mb-2 feature-row">
                                                    <div class="col-md-4">
                                                        <input type="text"
                                                            name="features_keys[]"
                                                            class="form-control"
                                                            value="{{ $key }}"
                                                            placeholder="Feature key (e.g. preferred_freelancer)">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text"
                                                            name="features_values[]"
                                                            class="form-control"
                                                            value="{{ is_bool($value) ? ($value ? 'true' : 'false') : $value }}"
                                                            placeholder="Value (e.g. true / false / unlimited / 100)">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button type="button" class="btn btn-danger btn-sm remove-feature">X</button>
                                                    </div>
                                                </div>
                                            @endforeach

                                            @if(empty($features))
                                                <div class="form-row mb-2 feature-row">
                                                    <div class="col-md-4">
                                                        <input type="text" name="features_keys[]" class="form-control" placeholder="Feature key">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" name="features_values[]" class="form-control" placeholder="Value">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button type="button" class="btn btn-danger btn-sm remove-feature">X</button>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <button type="button" class="btn btn-secondary btn-sm mt-2" id="add-feature">
                                            + Add Feature
                                        </button>

                                        <small class="text-muted d-block mt-1">
                                            Example: key = project_bookmarks, value = unlimited | key = preferred_freelancer_eligible, value = true
                                        </small>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>

                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection