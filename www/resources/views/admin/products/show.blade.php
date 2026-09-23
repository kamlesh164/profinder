@extends('admin.layouts.app')
@section('title')
<title>Product Details</title>
@endsection
@section('admin-content')
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Product Details</h1>
        </div>
        <div class="section-body">
            <a href="{{ route('admin.product.index') }}" class="btn btn-primary"><i class="fas fa-list"></i> Products List</a>
            <div class="row mt-sm-4">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card profile-widget">
                        <div class="profile-widget-header">
                            <div class="profile-widget-items">
                                <div class="profile-widget-item">
                                    <div class="profile-widget-item-label">ID: {{$product->id}}</div>
                                    <div class="profile-widget-item-value">Name: {{ $product->name }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="profile-widget-description">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <tr>
                                        <td>Category</td>
                                        <td>{{ $product->category->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Service Professional</td>
                                        <td><a href="{{route('admin.service-professional.show',$product->serviceProfessional->id)}}">{{ $product->serviceProfessional->name }}</a></td>
                                    </tr>
                                    <tr>
                                        <td>Price</td>
                                        <td>{{ $product->price }}</td>
                                    </tr>
                                    <tr>
                                        <td>Quantity</td>
                                        <td>{{ $product->qty }}</td>
                                    </tr>
                                    <tr>
                                        <td>Image</td>
                                        <td><i class="btn btn-primary fa fa-eye" data-toggle="modal" onclick="imageData(`{{ \App\Helpers\StaticDataHelper::imageOrDefault($product->image) }}`)" data-target="#canNotDeleteModal" aria-hidden="true"></i>
                                    </tr>
                                    <tr>
                                        <td>Is Active</td>
                                        <td>
                                            <a href="javascript:;" onclick="changeProductStatus(`{{ $product->id }}`)">
                                                <input id="status_toggle" type="checkbox" {{$product->is_active == 1 ? 'checked' : ''}} data-toggle="toggle" data-on="Active" data-off="Inactive" data-onstyle="success" data-offstyle="danger">
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Is Approve</td>
                                        <td>
                                            <span class="badge badge-{{$product->approve_admin == 1 ? 'success' : ($product->approve_admin == 2 ? 'danger' :'warning')}}">{{$product->approve_admin == 1 ? 'Approved' : ($product->approve_admin == 2 ? 'Reject' :'Pending')}}</span>

                                        </td>
                                    </tr>
                                    @if($product->approve_admin == 0)
                                    <tr>
                                        <td>Action</td>
                                        <td>
                                            <a href="{{route('admin.product.approval',['status' => 1, 'product_id' => $product->id])}}"><span class="badge badge-success" style="cursor: pointer;">Approve</span></a>
                                            <a href="{{route('admin.product.approval',['status' => 2, 'product_id' => $product->id])}}"><span class="badge badge-danger" style="cursor: pointer;">Reject</span></a>
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
            <div class="modal-body text-center">
                <img id="docimg" src="" alt="" class="img-fluid modal-img" style="max-width: 100%;max-height: 80vh;object-fit: contain;">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    "use strict";

    function imageData(imgurl) {
        $("#docimg").attr("src", imgurl)
    }
    function changeProductStatus(id) {
        $.ajax({
            type: "put",
            data: {
                _token: '{{ csrf_token() }}'
            },
            url: "{{url('/admin/product/status')}}" + "/" + id,
            success: function(response) {
                toastr.success(response)
            },
            error: function(err) {


            }
        })
    }
</script>
@endsection
