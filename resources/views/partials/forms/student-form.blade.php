<div class="modal fade" id="student-modal-form" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouvel étudiant</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form autocomplete="off" action="{{route('app.list.students.form')}}">
                    <input autocomplete="off" name="hidden" type="text" style="display:none;">
	                @csrf
	                <input type="hidden" name="id">
                    <div class="form-group">
                        <label>Nom et prénom(s) <span class="text-danger">*</span></label>
                        <input name="name" type="text" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input name="email" type="email" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label>Téléphone <span class="text-danger">*</span></label>
                        <input name="phone" type="text" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label>Date du test</label>
                        <div class="input-group date" id="test_date" data-target-input="nearest">
                            <input name="test_date" type="text" class="form-control datetimepicker-input"
                                   data-target="#test_date" value=""/>
                            <div class="input-group-append" data-target="#test_date" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Niveau actuel</label>
                        <select name="level" class="custom-select">
                            <option value="" selected>Aucun niveau</option>
	                        @foreach ($levels as $level)
		                        <option value="{{$level['id']}}">{{$level['label']}}</option>
	                        @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Membre du partenaire</label>
                        <select name="partners">
                            @foreach($partners as $partner)
		                        <option value="{{$partner['id']}}">{{$partner['name']}}</option>
	                        @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" id="submit-student-form">Enregistrer</button>
            </div>
        </div>
    </div>
</div>
<script>
    var resetForm = function(){
	    $('#student-modal-form').find('[name]').filter(function(){
		    return $(this).attr('name') !== '_token';
	    }).each(function(){
		    var self = $(this);
		    self.val('')
		    self.trigger('change');
	    });
    }
    $(function(){
	    var itsModalDom = $('#student-modal-form');
	    var formDom = itsModalDom.find('form');
	    itsModalDom.on('show.bs.modal', function(){
		    resetForm();
		    $(this).find('[name="partners"]').select2({
			    multiple : true,
			    theme : 'bootstrap4'
		    });
	    }).on('hidden.bs.modal', function(){
		    $(this).find('[name="partners"]').select2('destroy');
	    });
	    $('#submit-student-form').on('click', function(){
		    formDom.trigger('submit');
	    });
	    formDom.on('submit', function(event){
		    event.preventDefault();
		    var self = $(this);
		    var data = {
			    _token : $('[name=_token]').val(),
			    name : $('[name=name]').val(),
			    email : $('[name=email]').val(),
			    phone : $('[name=phone]').val(),
			    test_date : $('[name=test_date]').val() ? moment($('[name=test_date]').val(), 'DD-MM-YYYY').format('YYYY-MM-DD') : '',
			    level : $('[name=level]').val(),
		    };
		    if ($('[name=id]').val()) {
			    data.id = $('[name=id]').val();
		    }
		    if ($('[name=partners]').val()) {
			    data.partners = $('[name=partners]').val();
		    }
		    $.ajax({
			    url : self.attr('action'),
			    method : 'post',
			    type : 'json',
			    data : data,
			    success : function(response){
				    if (typeof studentDatatable !== 'undefined') {
					    studentDatatable.ajax.reload();
				    }
				    itsModalDom.modal('hide');
				    resetForm();
			    },
			    error : function(err1, err2, err3){
				   
			    }
		    })
	    });
	    $('[data-dismiss="modal"]').on('click', resetForm);
	    $('#test_date').datetimepicker({
		    locale : 'fr',
		    format : 'DD-MM-YYYY'
	    });
	    $('[name=phone]').inputmask({
		    mask : '+261 39 99 999 99',
		    greedy : false,
	    });
    })
</script>
