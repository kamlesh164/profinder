@extends('admin.layouts.app')

@section('title')
    <title>Job Details</title>
@endsection

@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Job Details</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('admin.jobpost.index') }}">Job Posts</a></div>
                <div class="breadcrumb-item">Details</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">

                {{-- Left: Job + Customer info --}}
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-header">
                            <h4>Job Information</h4>
                        </div>
                        <div class="card-body">
                            <p><strong>Title:</strong> {{ $job->title }}</p>
                            <p><strong>Description:</strong> {{ $job->description ?? '-' }}</p>
                            <p><strong>City:</strong> {{ $job->city->name ?? '-' }}</p>
                            <p><strong>Address:</strong> {{ $job->address ?? '-' }}</p>
                            <p><strong>Budget:</strong> ₹{{ number_format($job->budget) }}</p>
                            <p><strong>Duration:</strong> {{ $job->duration ?? '-' }}</p>
                            <p><strong>Status:</strong>
                                @php
                                    $status = $job->status;
                                    $badgeClass = match($status) {
                                        config('static_data.status.OPEN') => 'success',
                                        config('static_data.status.ASSIGNED') => 'warning',
                                        config('static_data.status.COMPLETED') => 'primary',
                                        config('static_data.status.CANCELLED') => 'danger',
                                        default => 'secondary',
                                    };
                                @endphp
                                <span class="badge badge-{{ $badgeClass }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </p>
                            <p><strong>Created At:</strong> {{ $job->created_at->format('d M, Y H:i') }}</p>

                            @if($job->image)
                                <p><strong>Image:</strong></p>
                                <img src="{{ asset($job->image) }}" alt="Job Image" class="img-fluid rounded">
                            @endif
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4>Customer</h4>
                        </div>
                        <div class="card-body">
                            @if($job->user)
                                <p><strong>Name:</strong>
                                    <a href="{{ route('admin.customers.show', $job->user->id) }}">
                                        {{ $job->user->name }}
                                    </a>
                                </p>
                                <p><strong>Email:</strong> {{ $job->user->email ?? '-' }}</p>
                                <p><strong>Phone:</strong> {{ $job->user->phone ?? '-' }}</p>
                            @else
                                <p>No customer information.</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Right: Bids list --}}
                <div class="col-md-7">
                    <div class="card">
                        <div class="card-header">
                            <h4>Bids on this Job</h4>
                        </div>
                        <div class="card-body">
                            @if($job->bids->isEmpty())
                                <p>No bids placed yet.</p>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Bid ID</th>
                                                <th>Contractor</th>
                                                {{-- <th>Amount</th> --}}
                                                <th>Message</th>
                                                <th>Status</th>
                                                <th>Created At</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($job->bids as $bid)
                                                <tr>
                                                    <td>#{{ $bid->id }}</td>
                                                    <td>
                                                        @if($bid->user)
                                                            <a href="{{ route('admin.customers.show', $bid->user->id) }}">
                                                                {{ $bid->user->name }}
                                                            </a>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    {{-- <td>
                                                        @if(!is_null($bid->amount))
                                                            ₹{{ number_format($bid->amount) }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td> --}}
                                                    <td>{{ $bid->message ?? '-' }}</td>
                                                    <td>
                                                        @php
                                                            $bStatus = $bid->status;
                                                            $bBadge = match($bStatus) {
                                                                config('static_data.status.PENDING') => 'warning',
                                                                config('static_data.status.ACCEPTED') => 'success',
                                                                config('static_data.status.REJECTED') => 'danger',
                                                                default => 'secondary',
                                                            };
                                                        @endphp
                                                        <span class="badge badge-{{ $bBadge }}">
                                                            {{ ucfirst($bStatus) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $bid->created_at->format('d M, Y H:i') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Optional: Assigned info --}}
                    @php
                        $assignedBid = $job->bids->firstWhere('status', config('static_data.status.ACCEPTED'));
                    @endphp
                    @if(in_array($job->status, [config('static_data.status.ASSIGNED'), config('static_data.status.COMPLETED')]) && $assignedBid && $assignedBid->user)
                        <div class="card">
                            <div class="card-header">
                                <h4>Assigned Contractor</h4>
                            </div>
                            <div class="card-body">
                                <p><strong>Contractor:</strong> {{ $assignedBid->user->name }}</p>

                                @if(!is_null($assignedBid->amount ?? null))
                                    <p><strong>Bid Amount:</strong> ₹{{ number_format($assignedBid->amount) }}</p>
                                @endif

                                <p><strong>Assigned At:</strong> {{ $assignedBid->updated_at->format('d M, Y H:i') }}</p>
                            </div>
                        </div>
                    @endif

                </div>

            </div>
        </div>
    </section>
</div>
@endsection
