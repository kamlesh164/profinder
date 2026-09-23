@extends('admin.layouts.app')
@section('title')
<title>Apartments</title>
@endsection
@section('admin-content')
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Apartments</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Apartments</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row mt-4">
                <div class="col-6">
                    <div class="card">
                        <div class="card-body">
                            <a href="#" data-toggle="modal" data-target="#addbhk" class="btn btn-primary float-right mb-2"><i class="fas fa-plus"></i></a>
                            <div class="table-responsive table-invoice">
                                <table class="table table-striped" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Price Multiply</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($bhks as $index => $bhk)
                                        <tr>
                                            <td>{{ $bhk->title }}</td>
                                            <td>{{ $bhk->price_multiply }}</td>
                                            <td>
                                                <a href="#" data-toggle="modal" data-target="#addbhk" onclick="UpdateBhkData(`{{$bhk->id}}`,`{{$bhk->bhk}}`,`{{$bhk->price_multiply}}`)" class="text-success"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                                <a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="text-danger" onclick="deleteData(`{{ $bhk->id }}`,1)"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card">
                        <div class="card-body">
                            <a href="#" data-toggle="modal" data-target="#addsquarefeet" class="btn btn-primary float-right mb-2"><i class="fas fa-plus"></i></a>
                            <div class="table-responsive table-invoice">
                                <table class="table table-striped" id="dataTable2">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Price Multiply</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($squarefeets as $index => $square)
                                        <tr>
                                            <td>{{ $square->title }}</td>
                                            <td>{{ $square->price_multiply }}</td>
                                            <td>
                                                <a href="#" data-toggle="modal" data-target="#addsquarefeet" onclick="UpdateSquareData(`{{$square->id}}`,`{{$square->from_area}}`,`{{$square->to_area}}`,`{{$square->price_multiply}}`)" class="text-success"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                                <a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="text-danger" onclick="deleteData(`{{ $square->id }}`,2)"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>

<div class="modal fade" id="addbhk" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Create/Update BHK</h5>
            </div>
            <form id="bhkform" action="{{route('admin.apartment.store')}}" method="post">
                @csrf
                <input type="hidden" name="form_type" value="1">
                <input type="hidden" name="id" value="" id="bhkupdateid">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-6">
                            <label for="">Number of BHK <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="bhk" name="bhk" required>
                        </div>
                        <div class="form-group col-6">
                            <label for="">Price Multiply <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="bhk_price_multiply" name="price_multiply" required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="addsquarefeet" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Create/Update Squarefeet</h5>
            </div>
            <form id="squareForm" action="{{route('admin.apartment.store')}}" method="post">
                @csrf
                <input type="hidden" name="form_type" value="2">
                <input type="hidden" name="id" value="" id="squareupdateid">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-6">
                            <label for="">From Area <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="from_area" name="from_area" required>
                        </div>
                        <div class="form-group col-6">
                            <label for="">To Area <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="to_area" name="to_area" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-6">
                            <label for="">Price Multiply <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="square_price_multiply" name="price_multiply" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    "use strict";

    function deleteData(id,formtype) {
        $("#deleteForm").attr("action", '{{ url("admin/apartments/delete") }}' + "?form_type=" + formtype + "&id=" + id)
    }

    function UpdateBhkData(id,bhk,pricemul) {
        $("#bhkform").attr("action", '{{ url("admin/apartments/update") }}');
        $('#bhkupdateid').val(id);
        $('#bhk').val(bhk);
        $('#bhk_price_multiply').val(pricemul);
    }
    function UpdateSquareData(id, fromarea, toarea, pricemul) {
        $("#squareForm").attr("action", '{{ url("admin/apartments/update") }}');
        $('#squareupdateid').val(id);
        $('#from_area').val(fromarea);
        $('#to_area').val(toarea);
        $('#square_price_multiply').val(pricemul);
    }
</script>
@endsection
