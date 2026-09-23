@extends('admin.layouts.app')
@section('title')
<title>Order Details</title>
@endsection
@section('admin-content')
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Invoice</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Invoice</div>
            </div>
        </div>
        <div class="section-body">
            <div class="invoice">
                <div class="invoice-print">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="invoice-title">
                                <h2><img src="{{ asset($setting->logo) }}" alt="" width="120px"></h2>
                                <div class="invoice-number">Order #{{ $order->id }}</div>
                            </div>
                            <hr>
                            @php
                            $orderAddress = $order->user;
                            @endphp
                            <div class="row">
                                <div class="col-md-6">
                                    <address>
                                        <strong>User Information:</strong><br>
                                        {{ $orderAddress->name }}<br>
                                        @if ($orderAddress->email)
                                        {{ $orderAddress->email }}<br>
                                        @endif
                                        @if ($orderAddress->phone)
                                        {{ $orderAddress->phone }}<br>
                                        @endif
                                        {{ $orderAddress->address }},
                                    </address>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <address>
                                        <strong>Payment Information:</strong><br>
                                        Method: {{ $order->payment_method }}<br>
                                        Status : @if ($order->payment_status == 'success')
                                        <span class="badge badge-success">Complete</span>
                                        @else
                                        <span class="badge badge-danger">Pending</span>
                                        @endif <br>
                                        Transaction: {!! clean(nl2br($order->transection_id)) !!}
                                    </address>
                                </div>
                                <div class="col-md-6 text-md-right">
                                    <address>
                                        <strong>Order Information:</strong><br>
                                        Date: {{ Carbon\Carbon::parse($order->created_at)->format('d F, Y') }}<br>

                                        Status :
                                        @if ($order->order_status == 1)
                                        <span class="badge badge-success">Complete </span>
                                        @else
                                        <span class="badge badge-danger">Pending</span>
                                        @endif
                                    </address>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="section-title">Order Summary</div>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-md">
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="25%">Product</th>
                                        <th width="20%">Vendor</th>
                                        <th width="10%" class="text-center">Unit Price</th>
                                        <th width="10%" class="text-right">Total')}}</th>
                                    </tr>
                                    @foreach ($order->orderItems as $index => $item)

                                    <tr>
                                        <td>{{ ++$index }}</td>
                                        <td>{{ $item->product->name }}</td>
                                        <td>{{ $item->product->vendor->name }} </td>
                                        <td class="text-center">{{ $item->price }}</td>
                                        @php
                                        $total = ($item->price * $item->qty)
                                        @endphp
                                        <td class="text-right">{{ $total }}</td>
                                    </tr>
                                    @endforeach
                                </table>
                            </div>

                            <div class="row mt-3">
                                <div class="col-lg-6 order-status">
                                    <div class="section-title">Order Status</div>

                                    <form action="{{ route('admin.update-order-status',$order->id) }}" method="POST">
                                        @csrf
                                        @method("PUT")
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
                                        <button class="btn btn-primary" type="submit">Update Status</button>
                                    </form>
                                </div>

                                <div class="col-lg-6 text-right">
                                    @php
                                    $sub_total = $order->total_amount;

                                    @endphp

                                    <hr class="mt-2 mb-2">
                                    <div class="invoice-detail-item">
                                        <div class="invoice-detail-value invoice-detail-value-lg">Total : {{ round($order->total_amount, 2) }}</div>
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



<div class="modal fade" tabindex="-1" role="dialog" id="declinedOrder-{{ $order->id }}">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Booking Declined Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are You sure declined this booking</p>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <form action="{{ route('admin.booking-declined', $order->id) }}" method="POST">
                    @csrf
                    @method("PUT")
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Yes, Declined</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="approvedOrder-{{ $order->id }}">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Booking Approved Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are You sure approved this booking</p>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <form action="{{ route('admin.booking-approved', $order->id) }}" method="POST">
                    @csrf
                    @method("PUT")
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Yes, Approved</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="approvedPayment-{{ $order->id }}">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Payment Approved Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are You sure approved this payment</p>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <form action="{{ route('admin.payment-approved', $order->id) }}" method="POST">
                    @csrf
                    @method("PUT")
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Yes, Approved</button>
                </form>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" tabindex="-1" role="dialog" id="markAsCompelete-{{ $order->id }}">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Booking Complete Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are You sure complete this booking</p>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <form action="{{ route('admin.booking-mark-as-complete', $order->id) }}" method="POST">
                    @csrf
                    @method("PUT")
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Yes, complete</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    "use strict";

    function deleteData(id) {
        $("#deleteForm").attr("action", '{{ url("admin/delete-order/") }}' + "/" + id)
    }
</script>
@endsection
