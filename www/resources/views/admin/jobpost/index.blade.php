@extends('admin.layouts.app')

@section('title')
    <title>Job Posts</title>
@endsection

@section('admin-content')
<style>
.open-live-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.live-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border-radius: 999px;
    background: #fff;
    color: #ff0000;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 1px;
    text-transform: uppercase;
}

.live-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #ff0000;
    box-shadow: 0 0 0 0 rgba(255,255,255,0.7);
    animation: live-pulse 1.4s infinite;
}

@keyframes live-pulse {
    0% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(255,255,255,0.7);
    }
    70% {
        transform: scale(1.4);
        box-shadow: 0 0 0 8px rgba(255,255,255,0);
    }
    100% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(255,255,255,0);
    }
}

</style>
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Job Posts</h1>
        </div>

        <div class="section-body">
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-danger">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header open-live-header">
                                <span class="live-badge">
                                    <span class="live-dot"></span>
                                    LIVE
                                </span>
                            </div>
                            <div class="card-body">
                                {{ $pendingJobs }}
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Jobs</h4>
                            </div>
                            <div class="card-body">
                                {{ $totalJobs }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Live Today</h4>
                            </div>
                            <div class="card-body">
                                {{ $liveToday }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Assigned</h4>
                            </div>
                            <div class="card-body">
                                {{ $assignedJobs }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive table-invoice">
                                <table class="table table-striped" id="dataTable-">
                                    <thead>
                                        <tr>
                                            <th width="5%">SN</th>
                                            <th width="15%">Title</th>
                                            <th width="15%">Customer</th>
                                            <th width="10%">City</th>
                                            <th width="10%">Budget</th>
                                            <th width="10%">Status</th>
                                            <th width="10%">Bids</th>
                                            <th width="15%">Created At</th>
                                            <th width="10%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($jobs as $index => $job)
                                            <tr>
                                                <td>{{ $jobs->firstItem() + $index }}</td>

                                                <td>{{ Str::limit($job->title, 40) }}</td>

                                                <td>
                                                    @if($job->user)
                                                        <a href="{{ route('admin.customers.show', $job->user->id) }}">
                                                            {{ $job->user->name }}
                                                        </a>
                                                    @else
                                                        -
                                                    @endif
                                                </td>

                                                <td>{{ $job->city->name ?? '-' }}</td>

                                                <td>₹{{ number_format($job->budget) }}</td>

                                                <td>
                                                    @php
                                                        $status = $job->status;
                                                        $badgeClass = match($status) {
                                                            'open' => 'success',
                                                            'assigned' => 'warning',
                                                            'completed' => 'primary',
                                                            'cancelled' => 'danger',
                                                            default => 'secondary',
                                                        };
                                                    @endphp
                                                    <span class="badge badge-{{ $badgeClass }}">
                                                        {{ ucfirst($status) }}
                                                    </span>
                                                </td>

                                                <td>
                                                    {{ $job->bids->count() }}
                                                </td>

                                                <td>{{ $job->created_at->format('d M, Y H:i') }}</td>

                                                <td>
                                                    <a href="{{ route('admin.jobpost.show', $job->id) }}"
                                                       class="btn btn-primary btn-sm">
                                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                                    </a>
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
                    {{ $jobs->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
