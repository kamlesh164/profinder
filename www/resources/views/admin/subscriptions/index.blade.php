@extends('admin.layouts.app')
@section('title')
<title>Subscription Plan</title>
@endsection
@section('admin-content')

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Subscription Plan</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header" style="justify-content: start">
                            <h4>

                                <a href="{{ route('admin.subscriptions.create') }}" class="btn btn-primary"><i
                                        class="fa fa-plus"></i>
                                    Add New</a>
                            </h4>
                        </div>
                        <div class="card-body text-center">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tr>
                                        <th>Serial</th>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Expiration</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>

                                    @foreach ($plans as $index => $plan)
                                    <tr>
                                        <td>{{ $plan->serial }}</td>
                                        <td>{{ $plan->plan_name }}</td>
                                        <td>{{ $plan->plan_price }}</td>
                                        <td>{{ $plan->expiration_date }}</td>

                                        <td>
                                            @if ($plan->status == 1)
                                            <div class="badge badge-success">Active</div>
                                            @else
                                            <div class="badge badge-danger">Inactive</div>
                                            @endif
                                        </td>

                                        <td>
                                            <a href="{{ route('admin.subscriptions.edit', $plan->id) }}" class="btn btn-primary btn-sm"><i
                                                    class="fa fa-edit"></i></a>
                                            <a href="" data-url="{{ route('admin.subscriptions.destroy', $plan->id) }}"
                                                class="btn btn-danger btn-sm delete"><i class="fa fa-trash"></i></a>

                                        </td>
                                    </tr>
                                    @endforeach


                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>


    </section>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="delete">
    <div class="modal-dialog" role="document">
        <form action="" method="POST">
            @csrf
            @method("DELETE")
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('Delete Plan')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="text-danger">{{__('Are You Sure to Delete this Plan ?')}}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                    <button type="submit" class="btn btn-danger">{{__('Yes, Delete')}}</button>
                </div>
            </div>
        </form>
    </div>
</div>


<script>
    $(function() {
        'use strict'

        $('.delete').on('click', function(e) {
            e.preventDefault();
            const modal = $('#delete');
            modal.find('form').attr('action', $(this).data('url'));
            modal.modal('show');
        })
    })
</script>

@endsection
