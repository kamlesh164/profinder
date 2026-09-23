@extends('admin.layouts.app')
@section('title')
<title>Purchase History</title>
@endsection
@section('admin-content')

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Purchase History</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>
                                <a href="{{ route('admin.assign-plan') }}" class="btn btn-primary"><i
                                        class="fa fa-plus"></i>Assign Plan</a>
                            </h4>
                        </div>
                        <div class="card-body text-center">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tr>
                                        <th>Serial</th>
                                        <th>Vendor</th>
                                        <th>Plan</th>
                                        <th>Expiration</th>
                                        <th>Status</th>
                                        <th>Payment</th>
                                        <th>Action</th>
                                    </tr>

                                    @foreach ($histories as $index => $history)
                                    <tr>
                                        <td>{{ ++$index }}</td>
                                        <td><a href="{{ route('admin.vendors.show', $history->vendor_id) }}">{{ $history->vendor->name }}</a></td>
                                        <td>{{ $history->plan_name }}</td>
                                        <td>{{ $history->expiration }}</td>
                                        <td>
                                            @if ($history->status == 'active')
                                            <div class="badge badge-success">Active</div>
                                            @elseif ($history->status == 'pending')
                                            <div class="badge badge-danger">Pending</div>
                                            @elseif ($history->status == 'expired')
                                            <div class="badge badge-danger">Expired</div>
                                            @endif
                                        </td>

                                        <td>
                                            @if ($history->payment_status == 'success')
                                            <div class="badge badge-success">Success</div>
                                            @else
                                            <div class="badge badge-danger">Pending</div>
                                            @endif
                                        </td>

                                        <td>
                                            <a href="{{ route('admin.purchase-history-show', $history->id) }}" class="btn btn-primary btn-sm"><i
                                                    class="fa fa-eye"></i></a>

                                        </td>
                                    </tr>
                                    @endforeach


                                </table>
                            </div>
                        </div>

                        @if($histories->hasPages())

                        <div class="card-footer">

                            {{ $histories->links('admin.partials.paginate') }}

                        </div>

                        @endif

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
