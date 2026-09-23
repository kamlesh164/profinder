@extends('admin.layouts.app')
@section('title')
<title>Products</title>
@endsection
@section('admin-content')
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Products</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Products</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row mt-4">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive table-invoice">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>SN</th>
                                            <th>Category</th>
                                            <th>Service Professional</th>
                                            <th>Product Name</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Image</th>
                                            <th>Is Active</th>
                                            <th>Is Approved</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $index => $product)
                                        <tr>
                                            <td>{{ ++$index }}</td>
                                            <td>{{ $product->category->name }}</td>
                                            <td><a href="{{route('admin.service-professional.show',$product->serviceProfessional->id)}}">{{ $product->serviceProfessional->name }}</a></td>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $product->price }}</td>
                                            <td>{{ $product->qty }}</td>
                                            <td>
                                                <img src="{{ \App\Helpers\StaticDataHelper::imageOrDefault($product->image) }}" class="w_80" alt="Product Img">
                                            </td>
                                            <td>
                                                <a href="javascript:;" onclick="changeProductStatus(`{{ $product->id }}`)">
                                                    <input id="status_toggle" type="checkbox" {{$product->is_active == 1 ? 'checked' : ''}} data-toggle="toggle" data-on="Active" data-off="Inactive" data-onstyle="success" data-offstyle="danger">
                                                </a>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{$product->approve_admin == 1 ? 'success' : ($product->approve_admin == 2 ? 'danger' :'warning')}}">{{$product->approve_admin == 1 ? 'Approved' : ($product->approve_admin == 2 ? 'Reject' :'Pending')}}</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.product.details',$product) }}" class="btn btn-primary btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                            </td>

                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="mt-4">
                                    {{ $products->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>

<script>
    "use strict";

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
