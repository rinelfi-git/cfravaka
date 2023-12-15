@extends('authed')
@section('title', 'Session')
@section('dynamic-style')

<style>
    .dual-list-session {
        display: flex;
        flex-direction: row;
    }

    .original-list-section,
    .selected-list {
        flex: 1;
    }

    .dual-list-actions {
        width: 50px;
        text-align: center;
        display: flex;
        gap: 5px;
        flex-direction: column;
        justify-content: center;
        justify-items: flex-start;
        padding: 0 5px;
    }

    .dual-list-actions>* {
        flex: 0 0 auto;
        width: auto;
        display: block;
    }

    .student-list {
        border: 1px solid #ced4da;
        padding: 5px;
        margin-top: 10px;
        height: 400px;
        overflow-y: auto;
    }

    .student-list>.card:last-child {
        margin: 0;
    }

    .student-info {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
    }

    .student-info .check {
        border-right: 1px solid #ced4da;
        padding: 5px 10px;
    }

    .student-info .check.color-section {
        align-self: stretch;
        border-radius: 5px 0 0 5px;
        width: 35px;
    }

    .student-info .name {
        flex: 1;
        font-weight: bold;
        padding: 5px 10px;
    }

    .student-info .actions {
        border-left: 1px solid #ced4da;
        padding: 5px 10px;
    }
</style>
@endsection
@section('wrapped-content')
<div class="card">
    <div class="card-header">
        <div class="card-tools">
            <button type="button" class="btn bg-gradient-dark" data-toggle="modal" data-target="#session-modal-form">
                Nouvelle entrée
            </button>
            @include('partials.forms.session-form', [
                'students' => $students,
                'levels' => $levels
                ])
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table">
            <thead>
                <tr>
                    <th>Libellé</th>
                    <th>Date de début</th>
                    <th>Date de fin</th>
                    <th>Nombre de places restantes</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <input type="text" class="form-control" placeholder="Libellé" autoComplete="off" />
                    </td>
                    <td><input type="text" class="form-control" placeholder="Date de début" autoComplete="off" /></td>
                    <td><input type="text" class="form-control" placeholder="Date de fin" autoComplete="off" /></td>
                    <td><input type="text" class="form-control" placeholder="Nombre de places restantes" autoComplete="off" /></td>
                </tr>
                <tr>
                    <td>Session Janvier</td>
                    <td>1 Janvier 2024</td>
                    <td>15 Fevrier 2024</td>
                    <td>5</td>
                    <td>
                        <div class="btn-group">
                            <button type="button" data-toggle={ 'modal' } data-target={ '#group-arrangement' } class="btn btn-sm bg-gradient-dark">Groupes
                            </button>
                            <button type="button" class="btn btn-sm bg-gradient-info">
                                <span class="fa fa-info"></span>
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="card-footer clearfix">
        <ul class="pagination pagination-sm m-0 float-right">
            <li class="page-item"><a class="page-link" href="#">«</a></li>
            <li class="page-item"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item"><a class="page-link" href="#">»</a></li>
        </ul>
    </div>
    <div class="modal fade" id="group-arrangement">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Disposition des groupes</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="student-list mb-2">
                        <div class="card">
                            <div class="card-body p-0 student-info">
                                <div class="check color-section"></div>
                                <div class="name">
                                    Rijaniaina Elie Fidèle
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('dynamic-script')
<script>
    var sessionDatatable;
    function sessionDataTable(options) {
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
                data: 'action',
                orderable: false,
                searchable: false
            }]
        });
    }

    $(function() {
        sessionDatatable = sessionDataTable({
            dataSource: "{{route('app.list.sessions.datatable')}}",
            token: "{{session('_token')}}"
        });
    })
</script>
@endsection
