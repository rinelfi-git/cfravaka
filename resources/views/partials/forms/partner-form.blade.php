<div class="modal fade" id="partner-modal-form" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouveau partenaire</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form  autocomplete="off" action="{{route('app.list.partners.form')}}">
                    <input autocomplete="false" name="hidden" type="text" style="display:none;">
                    @csrf
                    <input type="hidden" name="id" value="">
                    <div class="form-group">
                        <label>Nom <span class="text-red">*</span></label>
                        <input type="text" name="name" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label>Dirigeant <span class="text-red">*</span></label>
                        <input type="text" name="owner" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label>Étudiants membre</label>
                        <select name="students">
                            @foreach ($students as $student)
                            <option value="{{$student->id}}">{{$student->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" id="submit-partner-form">Enregistrer</button>
            </div>
        </div>
    </div>
</div>
<script>
    var resetForm = function() {
        $('#partner-modal-form').find('[name]').filter(function() {
            return $(this).attr('name') !== '_token';
        }).each(function() {
            var self = $(this);
            if (self.data('select2')) {
                self.select2('destroy');
                self.select2({
                    theme: 'bootstrap4',
                    multiple: true,
                    width: '100%'
                }).val([]);
            } else if (self.attr('value')) {
                self.val(self.attr('value'))
            } else {
                self.val('')
            }
            self.trigger('change');
        });
    }
    $(function() {
        var itsModalDom = $('#partner-modal-form');
        var formDom = itsModalDom.find('form');
        itsModalDom.on('show.bs.modal', function() {
            $(this).find('[name="students"]').select2({
                theme: 'bootstrap4',
                multiple: true
            }).val([]).trigger('change');
        }).on('hidden.bs.modal', function() {
            $(this).find('[name="students"]').select2('destroy');
        });
        $('#submit-partner-form').on('click', function() {
            formDom.trigger('submit');
        });
        formDom.on('submit', function(event) {
            event.preventDefault();
            var self = $(this);
            var data = {
                _token: $('[name=_token]').val(),
                name: $('[name=name]').val(),
                owner: $('[name=owner]').val(),
                students: $('[name=students]').val()
            };
            if ($('[name=id]').val()) {
				data.id = $('[name=id]').val();
			}
            $.ajax({
                url: self.attr('action'),
                method: 'post',
                type: 'json',
                data: data,
                success: function(response) {
                    if (typeof partnerDatatable !== 'undefined') {
						partnerDatatable.ajax.reload();
					}
                    itsModalDom.modal('hide');
                    resetForm();
                },
                error: function(err1, err2, err3) {

                }
            })
        });
        $('[data-dismiss="modal"]').on('click', resetForm);
        $('#test_date').datetimepicker({
            locale: 'fr',
            format: 'DD-MM-YYYY'
        });
        $('[name=phone]').inputmask({
            mask: '+261 39 99 999 99',
            greedy: false,
        });
    })
</script>
