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
        margin: 0;
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
            'levels' => $levels,
            'trainingTypes' => $trainingTypes
            ])
        </div>
    </div>
    <div class="card-body table-responsive">
        <table id="session-datatable" class="table table-bordered table-striped dt-responsive nowrap">
            <thead>
                <tr>
                    <th>Libellé</th>
                    <th>Débute le</th>
                    <th>Se termine le</th>
                    <th>Nombre de place alouée</th>
                    <th>Nombre de place disponible</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
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
        $('#session-datatable').on('click', '.open-update-modal', function() {
            var self = $(this);
            if (typeof resetForm === 'function') {
                $('#session-modal-form [name]').prop('disabled', true);
                resetForm.call();
            }
            $('#session-modal-form').modal('show');
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
                        var inputDom = $('#session-modal-form [name="' + key + '"]');
                        if (['start_date', 'end_date'].includes(key)) {
                            inputDom.val(moment(value).format('DD-MM-YYYY'))
                        } else {
                            inputDom.val(value);
                        }
                        inputDom.trigger('change');
                    });
                    $('#session-modal-form [name]').prop('disabled', false);
                    response.students.forEach(function(student) {
                        student.checked = false;
                        registerManager.inList.push(student);
                        var studentNotIndex = registerManager.not.findIndex(function(findStudent) {
                            return findStudent.id === student.id;
                        });
                        registerManager.not.splice(studentNotIndex, 1);
                    });
                    console.log('Rinelfi response', response);
                    buildRegisterManager.call();
                }
            });
        });
        return $('#session-datatable').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: options.dataSource,
                method: 'GET'
            },
            columns: [{
                data: 'label'
            }, {
                data: 'start_date'
            }, {
                data: 'end_date'
            }, {
                data: 'available_place'
            }, {
                data: 'occupied_place'
            }, {
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
