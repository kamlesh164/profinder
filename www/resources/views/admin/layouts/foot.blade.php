@php
$setting = App\Models\Setting::first();
@endphp

<div class="modal fade" tabindex="-1" role="dialog" id="deleteModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Item Delete Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure delete this item ?</p>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <form id="deleteForm" action="" method="POST">
                    @csrf
                    @method("DELETE")
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Yes, Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>



@livewireScripts

<script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('backend/js/popper.min.js') }}"></script>
<script src="{{ asset('backend/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('backend/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('backend/js/jquery.nicescroll.min.js') }}"></script>
<script src="{{ asset('backend/js/moment.min.js') }}"></script>
<script src="{{ asset('backend/js/stisla.js') }}"></script>
<script src="{{ asset('backend/js/scripts.js') }}"></script>
<script src="{{ asset('backend/js/select2.min.js') }}"></script>
<script src="{{ asset('backend/js/tagify.js') }}"></script>
<script src="{{ asset('backend/js/bootstrap-tagsinput.min.js') }}"></script>
<script src="{{ asset('backend/js/toastr.min.js') }}"></script>
<script src="{{ asset('backend/js/bootstrap4-toggle.min.js') }}"></script>
<script src="{{ asset('backend/js/fontawesome-iconpicker.min.js') }}"></script>
<script src="{{ asset('backend/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('backend/clockpicker/dist/bootstrap-clockpicker.js') }}"></script>
<script src="{{ asset('backend/datetimepicker/jquery.datetimepicker.js') }}"></script>
<script src="{{ asset('backend/js/iziToast.min.js') }}"></script>
<script src="{{ asset('backend/tinymce/js/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('backend/common.js') }}"></script>

@if(Session::has('messege'))
<script>
    var type = "{{Session::get('alert-type','info')}}"
    switch (type) {
        case 'info':
            toastr.info("{{ Session::get('messege') }}");
            break;
        case 'success':
            toastr.success("{{ Session::get('messege') }}");
            break;
        case 'warning':
            toastr.warning("{{ Session::get('messege') }}");
            break;
        case 'error':
            toastr.error("{{ Session::get('messege') }}");
            break;
    }
</script>
@endif
@if (Session::has('success'))
    <script>
        toastr.success(@json(Session::get('success')));
    </script>
@endif

@if (Session::has('error'))
    <script>
        toastr.error(@json(Session::get('error')));
    </script>
@endif

@if ($errors->any())
@foreach ($errors->all() as $error)
<script>
    toastr.error('{{ $error }}');
</script>

@endforeach

@endif



<script>
    (function($) {
        "use strict";
        $(document).ready(function() {
            tinymce.init({
                selector: '.summernote',
                plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage tinycomments tableofcontents footnotes mergetags autocorrect typography inlinecss',
                toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
                tinycomments_mode: 'embedded',
                tinycomments_author: 'Author name',
                mergetags_list: [{
                        value: 'First.Name',
                        title: 'First Name'
                    },
                    {
                        value: 'Email',
                        title: 'Email'
                    },
                ]
            });
            $('#dataTable').DataTable();
            $('#dataTable2').DataTable();
            $('.select2').select2();
            $('.sub_cat_one').select2();
            $('.tags').tagify();
            $('.datetimepicker_mask').datetimepicker({
                format: 'Y-m-d H:i',

            });

            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                startDate: '-Infinity'
            });
            $('.datepicker2').datepicker();
            $('.clockpicker').clockpicker();

        });

    })(jQuery);
</script>

</body>

</html>
