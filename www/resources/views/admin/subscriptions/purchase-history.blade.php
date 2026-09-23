@extends('admin.layouts.app')

@section('title')
    <title>Subscription Purchase History</title>
@endsection

@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Subscription Purchase History</h1>
        </div>

        <div class="section-body">

            {{-- Filters --}}
            <div class="card mb-3">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.subscriptions.purchase-history') }}">
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="">All</option>
                                    <option value="{{ config('static_data.status.ACTIVE') }}" {{ request('status') == config('static_data.status.ACTIVE') ? 'selected' : '' }}>Active</option>
                                    <option value="{{ config('static_data.status.PENDING') }}" {{ request('status') == config('static_data.status.PENDING') ? 'selected' : '' }}>Pending</option>
                                    <option value="{{ config('static_data.status.EXPIRED') }}" {{ request('status') == config('static_data.status.EXPIRED') ? 'selected' : '' }}>Expired</option>
                                    <option value="{{ config('static_data.status.CANCELLED') }}" {{ request('status') == config('static_data.status.CANCELLED') ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label>Payment Status</label>
                                <select name="payment_status" class="form-control">
                                    <option value="">All</option>
                                    <option value="{{ config('static_data.status.PENDING') }}" {{ request('payment_status') == config('static_data.status.PENDING') ? 'selected' : '' }}>Pending</option>
                                    <option value="{{ config('static_data.status.COMPLETED') }}" {{ request('payment_status') == config('static_data.status.COMPLETED') ? 'selected' : '' }}>Completed</option>
                                    <option value="{{ config('static_data.status.FAILED') }}" {{ request('payment_status') == config('static_data.status.FAILED') ? 'selected' : '' }}>Failed</option>
                                </select>
                            </div>

                            <div class="form-group col-md-2">
                                <label>From Date</label>
                                <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">
                            </div>

                            <div class="form-group col-md-2">
                                <label>To Date</label>
                                <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control">
                            </div>

                            <div class="form-group col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary mr-2">Filter</button>
                                <a href="{{ route('admin.subscriptions.purchase-history') }}" class="btn btn-light">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Table --}}
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>User</th>
                                    <th>Plan</th>
                                    <th>Amount</th>
                                    <th>Starts At</th>
                                    <th>Ends At</th>
                                    <th>Status</th>
                                    <th>Payment</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($purchases as $index => $purchase)
                                    @php
                                        $endsAt = $purchase->ends_at;
                                        $isExpiringSoon = $purchase->status == config('static_data.status.ACTIVE')
                                            && $endsAt
                                            && $endsAt->between(now(), now()->addDays(7));
                                    @endphp

                                    <tr @if($isExpiringSoon) class="table-warning" @endif>
                                        <td>{{ $purchases->firstItem() + $index }}</td>
                                        <td>{{ $purchase->user->name ?? '-' }}</td>
                                        <td>{{ $purchase->plan->plan_name ?? '-' }}</td>
                                        <td>₹{{ number_format($purchase->amount ?? ($purchase->plan->plan_price ?? 0)) }}</td>
                                        <td>{{ optional($purchase->starts_at)->format('d M Y') }}</td>
                                        <td>
                                            {{ optional($endsAt)->format('d M Y') }}
                                            @if($isExpiringSoon)
                                                <br>
                                                <small class="text-danger">Expiring in 7 days</small>
                                            @endif
                                        </td>

                                        {{-- Subscription status badge --}}
                                        @php
                                            $status = $purchase->status;
                                            $badgeClass = match ($status) {
                                                config('static_data.status.ACTIVE')    => 'success',
                                                config('static_data.status.PENDING')   => 'warning',
                                                config('static_data.status.EXPIRED')   => 'secondary',
                                                config('static_data.status.CANCELLED') => 'danger',
                                                default                               => 'light',
                                            };
                                        @endphp
                                        <td>
                                            <span class="badge badge-{{ $badgeClass }}">
                                                {{ ucfirst($status) }}
                                            </span>
                                        </td>

                                        {{-- Payment status badge --}}
                                        @php
                                            $payStatus = $purchase->paymentTransaction->status ?? 'pending';
                                            $payBadge = match ($payStatus) {
                                                config('static_data.status.COMPLETED') => 'success',
                                                config('static_data.status.FAILED')  => 'danger',
                                                config('static_data.status.PENDING') => 'warning',
                                                default   => 'light',
                                            };
                                        @endphp
                                        <td>
                                            <span class="badge badge-{{ $payBadge }}">
                                                {{ ucfirst($payStatus) }}
                                            </span>
                                        </td>

                                        <td>{{ $purchase->created_at->format('d M Y H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{ $purchases->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
