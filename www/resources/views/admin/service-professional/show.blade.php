@extends('admin.layouts.app')
@section('title')
<title>Service Professional Details</title>
@endsection
@section('admin-content')
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Service Professional Details</h1>
        </div>
        <div class="section-body">
            <a href="{{ route('admin.service-professional.index') }}" class="btn btn-primary"><i class="fas fa-list"></i> Service Professional List</a>
            <div class="row mt-sm-4">
                <div class="col-12 col-md-12 col-lg-5">
                    <div class="card profile-widget">
                        <div class="profile-widget-header">
                            <img alt="image" src="{{ \App\Helpers\StaticDataHelper::imageOrDefault($vendor->image,'user') }}" class="rounded-circle profile-widget-picture">
                            <div class="profile-widget-items">
                                <div class="profile-widget-item">
                                    <div class="profile-widget-item-label">Joined at</div>
                                    <div class="profile-widget-item-value">{{ $vendor->created_at->format('d M Y') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="profile-widget-description">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <tr>
                                        <td>Name</td>
                                        <td>{{ $vendor->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Email</td>
                                        <td>{{ $vendor->email}}</td>
                                    </tr>
                                    <tr>
                                        <td>Phone</td>
                                        <td>{{ $vendor->phone}}</td>
                                    </tr>
                                    <!-- <tr>
                                        <td>Status</td>
                                        <td>
                                            <a href="javascript:;" onclick="manageVendorStatus(`{{ $vendor->id }}`)">
                                                <input id="status_toggle" type="checkbox" {{$vendor->is_active == 1 ? 'checked':''}} data-toggle="toggle" data-on="Active" data-off="InActive" data-onstyle="success" data-offstyle="danger">
                                            </a>
                                        </td>
                                    </tr> -->
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h1>Service Professional Action</h1>
                                </div>
                                <div class="card-body text-center">
                                    <div class="row">
                                        {{-- <div class="col-12">
                                            <a href="" class="btn btn-primary btn-block btn-lg my-2">Service Professional Reviews</a>
                                        </div> --}}
                                        {{-- <div class="col-12">
                                            <a href="{{route('admin.vendor.kyc.details',$vendor->kycDetail->id)}}" class="btn btn-primary btn-block btn-lg my-2">KYC Document</a>
                                        </div> --}}
                                        <div class="col-12">
                                            <a href="" class="btn btn-warning btn-block btn-lg my-2">Send Email</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-12 col-lg-7">
                    <div class="card">
                        <form method="post" class="needs-validation" novalidate="" action="{{ route('admin.service-professional.update',$vendor->id) }}">
                            @method('put')
                            @csrf

                            <div class="card-header">
                                <h4>Edit Profile</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-6">
                                        <label>Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" value="{{ $vendor->name }}" name="name">
                                    </div>

                                    <div class="form-group col-6">
                                        <label>Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" value="{{ $vendor->email }}" name="email">
                                    </div>

                                    <div class="form-group col-6">
                                        <label>Phone <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" value="{{ $vendor->phone }}" name="phone" readonly>
                                    </div>
                                    <div class="form-group col-6">
                                        <label>Gender <span class="text-danger">*</span></label>
                                        <select class="form-control" name="gender" id="gender">
                                            <option value="">Select</option>
                                            <option value="Male" @selected($vendor->gender == 'Male')>Male</option>
                                            <option value="Female" @selected($vendor->gender == 'Female')>Female</option>
                                            <option value="Other" @selected($vendor->gender == 'Other')>Other</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-6">
                                        <label>Vendor Type <span class="text-danger">*</span></label>
                                        <select class="form-control select2" name="type" id="vendor_type">
                                            <option value="">Select</option>
                                            @foreach (config('static_data.accountType') as $role => $value )
                                            @if($role !== 'customer')
                                                <option value="{{ $value }}" @selected($vendor->type == 1)>{{ ucfirst($role) }}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-6">
                                        <label>State <span class="text-danger">*</span></label>
                                        <input type="hidden" name="state" value="{{ $address->state ?? '' }}">
                                        <input type="text" class="form-control" readonly value="{{ optional($address?->stateRelation)->name ?? '' }}">
                                    </div>

                                    <div class="form-group col-6">
                                        <label>City <span class="text-danger">*</span></label>
                                        <input type="hidden" name="city" value="{{ $address->city ?? '' }}">
                                        <input type="text" class="form-control" readonly value="{{ optional($address?->cityRelation)->name ?? '' }}">
                                    </div>
                                    <div class="form-group col-12">
                                        <label>Address <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="address" id="address">{{$address->address}}</textarea>
                                    </div>

                                </div>
                                <button class="btn btn-primary" type="submit">Save Changes</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    "use strict";

    function manageVendorStatus(id) {

        $.ajax({
            type: "put",
            data: {
                _token: '{{ csrf_token() }}'
            },
            url: "{{url('/admin/service-professional/status')}}" + "/" + id,
            success: function(response) {
                toastr.success(response)
            },
            error: function(err) {


            }
        })
    }
</script>

<script>
    (function($) {
        "use strict";
        $(document).ready(function() {

            /* $("#pincode").on("change", function() {
                var pincode = $("#pincode").val();

                if (pincode.length === 6) {
                    $.ajax({
                        type: "GET",
                        url: "{{ url('/city-state-by-pincode') }}/" + pincode,
                        success: function(response) {
                            if (response.success == true) {
                                $("#state_id").val(response.state);
                                $("#city_id").val(response.city);
                                $(".error").text('');
                            } else {
                                $("#state_id").val('');
                                $("#city_id").val('');
                                $(".error").text(response.message);

                            }
                        },
                        error: function(err) {
                            $(".error").text('Failed to fetch city/state.');
                        }
                    });
                } else {
                    $(".error").text('Incorrect pincode');
                    $("#state_id").val('');
                    $("#city_id").val('');
                }
            }); */
        });
    })(jQuery);
</script>
@endsection
