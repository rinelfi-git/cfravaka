@extends('authed')
@section('title', 'Étudiants')
@section('wrapped-content')
<div class="card">
    <div class="card-header">
        <div class="card-tools">
            <button type="button" class="btn bg-gradient-dark" data-toggle="modal" data-target="#student-modal-form">
                Nouvelle entrée
            </button>
            @include('partials.forms.student-form')
        </div>
    </div>
    <div class="card-body pb-0">
        <div class="card-body table-responsive">
            <table id="student-datatable" class="table table-bordered table-striped dt-responsive nowrap">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Date du test</th>
                        <th>Résultat du test</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@section('dynamic-script')
<script>
    var studentDatatable;

    function studentDataTable(options) {
        $('#student-datatable').on('click', '.open-update-modal', function() {
            var self = $(this);
            if (typeof resetForm === 'function') {
                $('#student-modal-form [name]').prop('disabled', true);
                resetForm.call();
            }
            $('#student-modal-form').modal('show');
            $.ajax({
                url: self.data('route'),
                method: 'post',
                type: 'json',
                data: {
                    id: self.data('id'),
                    _token: options.token
                },
                success: function(response) {
                    $.each(response, function(key, value) {
                        var inputDom = $('#student-modal-form [name="' + key + '"]');
                        inputDom.val(value);
                        inputDom.trigger('change');
                    });
                    $('#student-modal-form [name]').prop('disabled', false);
                }
            });
        });
        return $('#student-datatable').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: options.dataSource,
                method: 'GET'
            },
            columns: [{
                    data: 'name'
                },
                {
                    data: 'email'
                },
                {
                    data: 'phone'
                },
                {
                    data: 'test_date'
                },
                {
                    data: 'test_result'
                },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });
    }

    $(function() {
        studentDatatable = studentDataTable({
            dataSource: "{{route('app.list.students.datatable')}}",
            token: "{{session('_token')}}"
        });
    })
</script>
@endsection
