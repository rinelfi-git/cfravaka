@extends('authed')
@section('title', 'Partenaires')
@section('wrapped-content')
<div class="card">
    <div class="card-header">
        <div class="card-tools">
            <button type="button" class="btn bg-gradient-dark" data-toggle="modal" data-target="#partner-modal-form">
                Nouvelle entrée
            </button>
            @include('partials.partner-form', ['students' => $students])
        </div>
    </div>
    <div class="card-body pb-0">
        <div class="card-body table-responsive">
            <table id="partner-datatable" class="table table-bordered table-striped dt-responsive nowrap">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Dirigeant</th>
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
    var partnerDatatable;

    function partnerDataTable(options) {
        $('#partner-datatable').on('click', '.open-update-modal', function() {
            var self = $(this);
            if (typeof resetForm === 'function') {
                $('#partner-modal-form [name]').prop('disabled', true);
                resetForm.call();
            }
            $('#partner-modal-form').modal('show');
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
                        var inputDom = $('#partner-modal-form [name="' + key + '"]');
                        inputDom.val(value);
                        inputDom.trigger('change');
                    });
                    $('#partner-modal-form [name]').prop('disabled', false);
                }
            });
        });
        return $('#partner-datatable').DataTable({
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
                    data: 'owner'
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
        partnerDatatable = partnerDataTable({
            dataSource: "{{route('app.list.partners.datatable')}}",
            token: "{{session('_token')}}"
        });
    })
</script>
@endsection
