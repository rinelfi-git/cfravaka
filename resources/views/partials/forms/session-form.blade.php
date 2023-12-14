<div class="modal fade" id="session-modal-form" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Gestion de session</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form autocomplete="off" action="{{route('app.list.sessions.form')}}">
                    <input autocomplete="false" name="hidden" type="text" style="display:none;">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Libellé</label>
                                <input type="text" name="label" class="form-control" />
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Nombre de place alouée <span class="text-danger">*</span></label>
                                <input required type="number" name="place" class="form-control" />
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Date du début <span class="text-danger">*</span></label>
                                <div class="input-group date" id="start_date" data-target-input="nearest">
                                    <input required name="start_date" type="text" class="form-control datetimepicker-input" data-target="#start_date" value="" />
                                    <div class="input-group-append" data-target="#start_date" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Date de ferméture <span class="text-danger">*</span></label>
                                <div class="input-group date" id="end_date" data-target-input="nearest">
                                    <input required name="end_date" type="text" class="form-control datetimepicker-input" data-target="#end_date" value="" />
                                    <div class="input-group-append" data-target="#end_date" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4 class="text-center">Inscription</h4>
                    <div class="dual-list-session">
                        <div class="original-list-section">
                            <div class="filter-section">
                                <input type="text" class="form-control" />
                            </div>
                            <div class="student-list" id="register-not"></div>
                        </div>
                        <div class="dual-list-actions">
                            <button class="button btn btn-default disabled" type="button" id="move-selected-right">
                                <span class="fas fa-angle-double-right"></span>
                            </button>
                            <button class="button btn btn-default disabled" type="button" id="move-selected-left">
                                <span class="fas fa-angle-double-left"></span>
                            </button>
                        </div>
                        <div class="selected-list">
                            <div class="filter-section">
                                <input type="text" class="form-control" />
                            </div>
                            <div class="student-list" id="register-in"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" id="submit-formation-form">Enregistrer</button>
            </div>
        </div>
    </div>
</div>
<script>
    var registrationInfoRoute = "{{route('app.list.sessions.registration.info.get')}}";
    var phpStudents = JSON.parse(JSON.stringify(<?= json_encode($students) ?>));
    var registerManager = {
        not: [],
        inList: [{
            checked: true,
            id: 1,
            name: 'Raoza',
            formations: [],
            level: '',
            showForm: false
        }]
    }
    phpStudents.forEach(function(phpStudent) {
        registerManager.not.push({
            checked: false,
            id: phpStudent.id,
            name: phpStudent.name,
            prevLevel: phpStudent.level
        })
    });
    var buildRegisterManager = function(options) {
        var targetNotDom = $('#register-not');
        var targetInDom = $('#register-in');
        targetInDom.empty().html('');
        targetNotDom.empty().html('');

        function updateLeftButton() {
            var moveSelectLeftDom = $('#move-selected-left');
            var selected = registerManager.inList.filter(function(notFilter) {
                return notFilter.checked;
            });
            if (selected.length) {
                moveSelectLeftDom.removeClass('disabled');
            } else {
                moveSelectLeftDom.addClass('disabled');
            }
        }

        function updateRightButton() {
            var moveSelectRightDom = $('#move-selected-right');
            var selected = registerManager.not.filter(function(notFilter) {
                return notFilter.checked;
            });
            if (selected.length) {
                moveSelectRightDom.removeClass('disabled');
            } else {
                moveSelectRightDom.addClass('disabled');
            }
        }
        updateLeftButton();
        updateRightButton();
        $.each(registerManager.inList, function(index, inList) {
            var checkDom = $('<input>')
                .attr('type', 'checkbox')
                .prop('checked', inList.checked)
                .data('index', index)
                .on('change', function() {
                    var self = $(this);
                    registerManager.inList[self.data('index')].checked = self.is(':checked');
                    updateLeftButton();
                });

            var formationSelectDom = $('<select>')
                .prop('required', true)
                .data('index', index)
                .on('change', function() {
                    var self = $(this);
                    registerManager.inList[self.data('index')].formations = self.val();
                });

            var levelSelectDom = $('<select>')
                .addClass('custom-select')
                .prop('required', true)
                .data('index', index)
                .on('change', function() {
                    var self = $(this);
                    registerManager.inList[self.data('index')].level = self.val();
                });

            var formFooterDom = $('<div>')
                .addClass('card-footer')
                .append(
                    $('<div>')
                    .addClass('form-group')
                    .append(
                        $('<label>')
                        .text('Formation.s'),
                        formationSelectDom
                    ),
                    $('<div>')
                    .addClass('form-group')
                    .append(
                        $('<label>')
                        .text('Niveau'),
                        levelSelectDom
                    )
                )
            formationSelectDom.select2({
                theme: 'bootstrap4',
                multiple: true,
                ajax: {
                    url: registrationInfoRoute, // URL de la source de données
                    method: 'post',
                    delay: 500, // Délai avant l'envoi de la requête après la saisie
                    data: function(params) {
                        return { // Paramètres envoyés avec la requête
                            recherche: params.term, // terme de recherche saisi par l'utilisateur
                            _token: $('[name=_token]').val(),
                            student_id: inList.id,
                            formation: true,
                            page: params.page || 1
                        };
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.items, // Les éléments à afficher dans le menu déroulant
                            pagination: {
                                more: (params.page * 10) < data.total_count // booléen pour plus de pages
                            }
                        };
                    },
                    cache: true
                },
                minimumInputLength: 1,
            });

            levelSelectDom.select2({
                theme: 'bootstrap4',
                tags: true,
                multiple: true,
                tokenSeparators: ['.'],
                minimumInputLength: 1,
                maximumSelectionLength: 1,
                createTag: function(params) {
                    // Cette fonction est appelée lorsque l'utilisateur saisit une valeur
                    var term = $.trim(params.term);
                    if (levelSelectDom.select2('data').length >= 1 || term === '') {
                        return null;
                    }
                    return {
                        id: term, // Utilisez la valeur saisie comme ID
                        text: term, // Utilisez la valeur saisie comme texte
                        newTag: true // Indique qu'il s'agit d'une nouvelle valeur
                    };
                },
                ajax: {
                    url: registrationInfoRoute, // URL de la source de données
                    method: 'post',
                    dataType: 'json', // Type de données attendu en réponse
                    delay: 500, // Délai avant l'envoi de la requête après la saisie
                    data: function(params) {
                        return { // Paramètres envoyés avec la requête
                            recherche: params.term, // terme de recherche saisi par l'utilisateur
                            _token: $('[name=_token]').val(),
                            page: params.page || 1
                        };
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.items, // Les éléments à afficher dans le menu déroulant
                            pagination: {
                                more: (params.page * 10) < data.total_count // booléen pour plus de pages
                            }
                        };
                    },
                    cache: true
                }
            });

            var addFormationDom = $('<button>')
                .addClass('btn btn-sm bg-gradient-info')
                .attr('type', 'button')
                .html('<i class="fas fa-list"></i>')
                .data('index', index)
                .data('footer-form', formFooterDom)
                .on('click', function() {
                    var self = $(this);
                    var showForm = !registerManager.inList[self.data('index')].showForm;
                    registerManager.inList[self.data('index')].showForm = showForm;
                    if (showForm) {
                        self.parents('.card-body.p-0.student-info').after(self.data('footer-form'));
                    } else {
                        self.data('footer-form').detach();
                    }
                })

            targetInDom.append(
                $('<div>')
                .addClass('card')
                .append(
                    $('<div>')
                    .addClass('card-body p-0 student-info')
                    .append(
                        $('<div>')
                        .addClass('check')
                        .append(checkDom),
                        $('<div>')
                        .addClass('name')
                        .text(inList.name),
                        $('<div>')
                        .addClass('actions')
                        .append(
                            $('<div>')
                            .addClass('btn-group')
                            .append(addFormationDom)
                        )
                    ),
                    registerManager.inList[index].showForm ? formFooterDom : null
                )
            );
        });

        $.each(registerManager.not, function(index, not) {
            var checkDom = $('<input>')
                .attr('type', 'checkbox')
                .prop('checked', not.checked)
                .data('index', index)
                .on('change', function() {
                    var self = $(this);
                    registerManager.not[self.data('index')].checked = self.is(':checked');
                    updateRightButton();
                });

            targetNotDom.append(
                $('<div>')
                .addClass('card')
                .append(
                    $('<div>')
                    .addClass('card-body p-0 student-info')
                    .append(
                        $('<div>')
                        .addClass('check')
                        .append(checkDom),
                        $('<div>')
                        .addClass('name')
                        .text(not.name),
                        $('<div>')
                        .addClass('actions')
                        .html('<strong>' + (not.prevLevel ? not.prevLevel : '') + '</strong>')
                    )
                )
            );
        })
    }
    var resetForm = function() {
        $('#session-modal-form').find('[name]').filter(function() {
            return $(this).attr('name') !== '_token';
        }).each(function() {
            var self = $(this);
            if (self.attr('value')) {
                self.val(self.attr('value'))
            } else {
                self.val('')
            }
            self.trigger('change');
        });
    }
    $(function() {
        var itsModalDom = $('#session-modal-form');
        var formDom = itsModalDom.find('form');
        itsModalDom.on('show.bs.modal', function() {
            $(this).find('[name="partner_id"]').select2({
                theme: 'bootstrap4'
            }).val('').trigger('change');
            buildRegisterManager();
        }).on('hidden.bs.modal', function() {
            $(this).find('[name="partner_id"]').select2('destroy');
        });
        $('#submit-formation-form').on('click', function() {
            formDom.trigger('submit');
        });
        formDom.on('submit', function(event) {
            var self = $(this);
            event.preventDefault();
            var data = {
                _token: $('[name=_token]').val(),
                label: $('[name=label]').val(),
                place: $('[name=place]').val(),
                start_date: moment($('[name=start_date]').val(), 'DD-MM-YYYY').format(),
                end_date: moment($('[name=end_date]').val(), 'DD-MM-YYYY').format(),
                students: []
            }
            $(registerManager.inList, function(_, inList) {
                data.students.push({
                    id: inList.id,
                    formations: inList.formations,
                    level: inList.level
                })
            });
            $.ajax({
                url: self.attr('action'),
                dataType: 'json',
                method: 'post',
                data: data,
                success: function() {

                }
            })
        });
        $('[data-dismiss="modal"]').on('click', resetForm);
        $('#start_date, #end_date').datetimepicker({
            locale: 'fr',
            format: 'DD-MM-YYYY'
        });
        $('#move-selected-right').on('click', function() {
            var self = $(this);
            var filtered = registerManager.not.slice().filter(function(filterNot) {
                return filterNot.checked;
            });
            $.each(filtered, function(_, filter) {
                filter.checked = false;
                registerManager.inList.push(filter);
            });
            $.each(filtered, function(_, fors) {
                var index = registerManager.not.findIndex(function(findIndex) {
                    return findIndex.id === fors.id;
                });
                registerManager.not.splice(index, 1);
            });
            buildRegisterManager();
            self.addClass('disabled');
        });
        $('#move-selected-left').on('click', function() {
            var self = $(this);
            var filtered = registerManager.inList.slice().filter(function(filterInList) {
                return filterInList.checked;
            });
            $.each(filtered, function(_, filter) {
                filter.checked = false;
                registerManager.not.push(filter);
            });
            $.each(filtered, function(_, fors) {
                var index = registerManager.inList.findIndex(function(findIndex) {
                    return findIndex.id === fors.id;
                });
                registerManager.inList.splice(index, 1);
            });
            buildRegisterManager();
            self.addClass('disabled');
        });
    })
</script>
