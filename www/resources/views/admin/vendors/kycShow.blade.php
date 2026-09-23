@extends('admin.layouts.app')
@section('title')
<title>Kyc Details</title>
@endsection
@section('admin-content')
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Kyc Details</h1>
        </div>
        <div class="section-body">
            <a href="{{ route('admin.vendors.index') }}" class="btn btn-primary"><i class="fas fa-list"></i> Vendor List</a>
            <div class="row mt-sm-4">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card profile-widget">
                        <div class="profile-widget-header">
                            <div class="profile-widget-items">
                                <div class="profile-widget-item">
                                    <div class="profile-widget-item-label">ID: {{$kyc->vendor->id}}</div>
                                    <div class="profile-widget-item-value">Name: {{ $kyc->vendor->name }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="profile-widget-description">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <tr>
                                        <td>Adhar Number</td>
                                        <td>{{ $kyc->adhar_no }}</td>
                                    </tr>
                                    <tr>
                                        <td>Adhar Front</td>
                                        <td><i class="btn btn-primary fa fa-eye" data-toggle="modal" onclick="imageData(`{{ asset($kyc->adhar_front)}}`)" data-target="#canNotDeleteModal" aria-hidden="true"></i>
                                    </tr>
                                    <tr>
                                        <td>Adhar Back</td>
                                        <td><i class="btn btn-primary fa fa-eye" data-toggle="modal" onclick="imageData(`{{ asset($kyc->adhar_back)}}`)" data-target="#canNotDeleteModal" aria-hidden="true"></i>                                    </tr>
                                    <tr>
                                        <td>Pancard</td>
                                        <td>{{ $kyc->pancard_no}}</td>
                                    </tr>
                                    <tr>
                                        <td>Pancard Image</td>
                                        <td><i class="btn btn-primary fa fa-eye" data-toggle="modal" onclick="imageData(`{{ asset($kyc->pancard)}}`)" data-target="#canNotDeleteModal" aria-hidden="true"></i>                                    </tr>
                                    <tr>
                                        <td>GST Number</td>
                                        <td>{{ $kyc->gst_number}}</td>
                                    </tr>
                                    <tr>
                                        <td>GST Doc</td>
                                        <td><i class="btn btn-primary fa fa-eye" data-toggle="modal" onclick="imageData(`{{ asset($kyc->gst_doc)}}`)" data-target="#canNotDeleteModal" aria-hidden="true"></i>                                    </tr>
                                    <tr>
                                        <td>Status</td>
                                        <td>
                                            @if($kyc->status == 0)
                                            <span class="badge badge-warning">Pending</span>
                                            @elseif($kyc->status == 1)
                                            <span class="badge badge-success">Approved</span>
                                            @elseif($kyc->status == 2)
                                            <span class="badge badge-danger">Rejected</span>
                                            @else
                                            <span class="badge badge-danger">Not Submit</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @if($kyc->status == 0)
                                    <tr>
                                        <td>Action</td>
                                        <td>
                                            <a href="{{route('admin.vendor.kyc.approval',['status' => 1, 'kyc_id' => $kyc->id])}}"><span class="badge badge-success" style="cursor: pointer;">Approve</span></a>
                                            <span class="badge badge-danger" data-toggle="modal" data-target="#rejectModal" style="cursor: pointer;">Reject</span>
                                        </td>
                                    </tr>
                                   @endif
                                </table>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>
</div>
<div class="modal fade" id="canNotDeleteModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <img id="docimg" src="" alt="" srcset="">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Reject Reason!</h5>
            </div>
            <div class="modal-body">
                <form action="{{route('admin.vendor.kyc.approval',['status' => 2, 'kyc_id' => $kyc->id])}}" method="get">
                    <div class="form-group">
                        <label for="">Reason <span class="text-danger">*</span></label>
                        <textarea name="reject_reason" class="form-control"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div>

<script>
    "use strict";

    function imageData(imgurl) {
        $("#docimg").attr("src", imgurl)
    }

</script>
@endsection
