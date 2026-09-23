@extends('admin.layouts.app')
@section('title')
<title>Service Professional List</title>
@endsection
@section('admin-content')
<style>
    #categorySidePanel {
        position: fixed;
        top: 0;
        right: -35%;
        width: 35%;
        height: 100%;
        background: #fff;
        box-shadow: -2px 0 8px rgba(0,0,0,0.1);
        z-index: 1050;
        transition: right 0.3s ease;
        overflow-y: auto;
    }
    #categorySidePanel.open {
        right: 0;
    }
    #categorySidePanelBackdrop {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.4);
        z-index: 1049;
        display: none;
    }
    #categorySidePanelBackdrop.show {
        display: block;
    }
</style>
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header d-flex justify-content-between align-items-center mb-3">
            <h1>Service Professional List</h1>
            <button type="button" class="btn btn-sm- btn-primary" id="toggleCategoryPanel">
                Manage Info Categories
            </button>
        </div>
        <div class="section-body">
             {{-- <form action="{{route('admin.vendor.upload.excel')}}" enctype="multipart/form-data" method="post">
                @csrf
                <label for="">Excel File</label>
                <input type="file" name="excel_file" class="form-control" required>
                <button class="btn btn-sm btn-primary">Submit</button>
            </form> --}}
            <div class="row mt-4">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive table-invoice">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>SN</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Vendor Type</th>
                                            {{-- <th>Kyc</th> --}}
                                            {{-- <th>Is Active</th> --}}
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $i=1; @endphp
                                       @foreach ($sp as $vendor)
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>{{ $vendor->name }}</td>
                                            <td>{{ $vendor->email}}</td>
                                            <td>{{ $vendor->phone}}</td>
                                            <td>{{ ucfirst(\App\Helpers\StaticDataHelper::getAccountTypeLabel($vendor->type)) }}</td>
                                            {{-- <td>
                                                @if(@$vendor->kycDetail() && @$vendor->kycDetail->status == 0)
                                                <span class="badge badge-warning">Pending</span>
                                                @elseif(@$vendor->kycDetail->status == 1)
                                                <span class="badge badge-success">Approved</span>
                                                @elseif(@$vendor->kycDetail->status == 2)
                                                <span class="badge badge-danger">Rejected</span>
                                                @else
                                                <span class="badge badge-danger">Not Submit</span>
                                                @endif
                                            </td> --}}
                                            {{--<td>
                                                <a href="javascript:;" onclick="manageVendorStatus(`{{ $vendor->id }}`)">
                                                    <input id="status_toggle" type="checkbox" {{@$vendor->is_active == 1 ? 'checked':''}} data-toggle="toggle" data-on="Active" data-off="Inctive" data-onstyle="success" data-offstyle="danger">
                                                </a>
                                            </td>--}}
                                            <td>
                                                <a href="{{ route('admin.service-professional.show',@$vendor->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                                <a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="btn btn-danger btn-sm" onclick="deleteData(`{{ @$vendor->id }}`)"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                            </td>
                                        </tr> 
                                        @php $i++ @endphp
                                        @endforeach 
                                    </tbody>
                                </table>
                                <div class="mt-4">
                                    {{ $sp->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>
<div id="categorySidePanelBackdrop"></div>

<div id="categorySidePanel">
    <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Info Categories</h5>
        <button type="button" class="close" id="closeCategoryPanel">&times;</button>
    </div>

    <div class="p-3">
        {{-- Add form --}}
        <form id="infoCategoryForm" method="POST" action="{{ route('admin.info-categories.store') }}">
            @csrf
            <div class="form-group">
                <label>Category Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" id="infoCategoryName" required>
                <small class="text-danger d-none" id="infoCategoryNameError"></small>
            </div>
            <button class="btn btn-primary btn-sm mb-3" type="submit" id="btnAddCategory" disabled>
                Add Category
            </button>
        </form>

        <div id="infoCategoryList">
            @include('admin.partials.info-category-list', ['categories' => $categories])
        </div>
    </div>
</div>
<script>
    "use strict";

    let checkUrl = '{{ route('admin.info-categories.check-name') }}';

    function setButtonState(valid) {
        $('#btnAddCategory').prop('disabled', !valid);
    }

    $('#infoCategoryName').on('keyup blur', function () {
        let name = $(this).val().trim();
        if (!name.length) {
            $('#infoCategoryNameError').addClass('d-none').text('');
            setButtonState(false);
            return;
        }

        $.get(checkUrl, { name: name }, function (res) {
            if (res.exists) {
                $('#infoCategoryNameError')
                    .removeClass('d-none')
                    .text('This category name already exists.');
                    setButtonState(false);
            } else {
                $('#infoCategoryNameError').addClass('d-none').text('');
                setButtonState(true);
            }
        });
    });

    function deleteData(id) {
        $("#deleteForm").attr("action", '{{ url("admin/service-professional") }}' + "/" + id)
    }

    function manageVendorStatus(id) {
        $.ajax({
            type: "put",
            data: {
                _token: '{{ csrf_token() }}'
            },
            url: "{{url('/admin/service-professional/status')}}" + "/" + id,
            success: function(response) {
                toastr.success(response)
            },
            error: function(err) {


            }
        })
    }

    (function($){
        "use strict";

        $(document).ready(function () {
            $('#toggleCategoryPanel').on('click', function () {
                $('#categorySidePanel').addClass('open');
                $('#categorySidePanelBackdrop').addClass('show');
            });

            $('#closeCategoryPanel, #categorySidePanelBackdrop').on('click', function () {
                $('#categorySidePanel').removeClass('open');
                $('#categorySidePanelBackdrop').removeClass('show');
            });
        });
    })(jQuery);
</script>
@endsection
