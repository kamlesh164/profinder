@if($categories->count())
<table class="table table-sm table-bordered">
    <thead>
        <tr>
            <th>SN</th>
            <th>Name</th>
            <th width="60">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($categories as $index => $cat)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $cat->name }}</td>
            <td>
                <a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="btn btn-danger btn-sm btn-delete-category" onclick="Delete(`{{ @$cat->id }}`)"><i class="fa fa-trash" aria-hidden="true"></i></a>
                {{-- <button class="btn btn-danger btn-sm btn-delete-category"
                        data-id="{{ $cat->id }}"
                        data-url="{{ route('admin.info-categories.destroy', $cat->id) }}">
                    <i class="fa fa-trash"></i>
                </button> --}}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<p class="text-muted mb-0">No categories added yet.</p>
@endif
<script>
    "use strict";

    function Delete(id) {
        $("#deleteForm").attr("action", '{{ url("admin/info-categories") }}' + "/" + id)
    }
</script>