@extends('admin.layouts.app')
@section('title')
<title>Complete Orders</title>
@endsection
@section('admin-content')
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Complete Orders</h1>
        </div>

        <div class="section-body">
            <div class="row mt-4">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive table-invoice">
                                <table class="table table-striped" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th width="5%">SN</th>
                                            <th width="10%">Customer</th>
                                            <th width="10%">Order Id</th>
                                            <th width="10%">Date</th>
                                            <th width="10%">Quantity</th>
                                            <th width="10%">Amount</th>
                                            <th width="10%">Order Status</th>
                                            <th width="10%">Payment</th>
                                            <th width="15%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orders as $index => $order)
                                        <tr>
                                            <td>{{ ++$index }}</td>
                                            <td><a href="{{ route('admin.customer-show', $order->user->id) }}">{{ $order->user->name}}</a></td>
                                            <td>{{ $order->id }}</td>
                                            <td>{{ Carbon\Carbon::parse($order->created_at)->format('d F, Y') }}</td>
                                            <td>{{ $order->cart_qty }}</td>
                                            <td>{{ $setting->currency_icon }}{{ round($order->total_amount) }}</td>
                                            <td>
                                                @if ($order->order_status == 1)
                                                <span class="badge badge-success">Complete </span>
                                                @elseif ($order->order_status == 0)
                                                <span class="badge badge-danger">Pending </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($order->payment_status == 'success')
                                                <span class="badge badge-success">success </span>
                                                @else
                                                <span class="badge badge-danger">Pending</span>
                                                @endif
                                            </td>

                                            <td>

                                                <a href="{{ route('admin.order.show',$order->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></a>

                                                <a href="javascript:;" data-toggle="modal" data-target="#orderModalId-{{ $order->id }}" class="btn btn-warning btn-sm"><i class="fas fa-truck" aria-hidden="true"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-12">
                    {{ $orders->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </section>
</div>


<!-- Modal -->
@foreach ($orders as $index => $order)
<div class="modal fade" id="orderModalId-{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Order Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.order.update.status',$order->id) }}" method="POST">
                <div class="modal-body">
                    <div class="container-fluid">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label for="">Payment</label>
                            <select name="payment_status" id="" class="form-control">
                                <option {{ $order->payment_status == 'pending' ? 'selected' : '' }} value="pending">Pending</option>
                                <option {{ $order->payment_status == 'success' ? 'selected' : '' }} value="success">Success</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Order</label>
                            <select name="order_status" id="" class="form-control">
                                <option {{ $order->order_status == 0 ? 'selected' : '' }} value="0">Pending</option>
                                <option {{ $order->order_status == 1 ? 'selected' : '' }} value="1">Complete</option>
                            </select>
                        </div>


                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endforeach


<script>
    "use strict";

    function deleteData(id) {
        $("#deleteForm").attr("action", '{{ url("admin/delete-order/") }}' + "/" + id)
    }
</script>
@endsection
