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
					<input autocomplete="false" name="hidden" type="text" style="display:none;">
					@csrf
					<input type="hidden" name="id" value="">
					<div class="form-group">
						<label>Nom et prénom(s)</label>
						<input name="name" type="text" class="form-control"/>
					</div>
					<div class="form-group">
						<label>Email</label>
						<input name="email" type="email" class="form-control"/>
					</div>
					<div class="form-group">
						<label>Téléphone</label>
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
						<label>Résultat du test</label>
						<input name="test_result" type="text" class="form-control"/>
					</div>
					<div class="form-group">
						<label>Membre du partenaire</label>
						<select name="partner_id">
							<option value="" selected="">Non affilié</option>
							@foreach($partners as $partner)
								<option value="{{$partner->id}}">{{$partner->name}}</option>
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
			if (self.attr('value')) {
				self.val(self.attr('value'))
			} else {
				self.val('')
			}
			self.trigger('change');
		});
	}
	$(function(){
		var itsModalDom = $('#student-modal-form');
		var formDom = itsModalDom.find('form');
		itsModalDom.on('show.bs.modal', function(){
			$(this).find('[name="partner_id"]').select2({
				theme : 'bootstrap4'
			});
		}).on('hidden.bs.modal', function(){
			$(this).find('[name="partner_id"]').select2('destroy');
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
				test_date : $('[name=test_date]').val() ? moment($('[name=test_date]').val(), 'DD-MM-YYYY').format() : '',
				test_result : $('[name=test_result]').val(),
			};
			if ($('[name=id]').val()) {
				data.id = $('[name=id]').val();
			}
			if ($('[name=partner_id]').val()) {
				data.partner_id = $('[name=partner_id]').val();
			}
			console.log('Save', data);
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
