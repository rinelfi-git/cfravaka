<style>
    .form-subcategories {
        position: relative;
    }

    .form-subcategories:hover .sub-category-close {
        visibility: visible;
        opacity: 1;
    }

    .sub-category-close {
        visibility: hidden;
        opacity: 0;
        position: absolute;
        top: 5px;
        right: 5px;
        transition: .15s;
    }
</style>
<div class="modal fade" id="formation-modal-form" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouvel étudiant</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form autocomplete="off" action="{{route('app.list.formations.form')}}">
                    <input autocomplete="false" name="hidden" type="text" style="display:none;">
	                @csrf
	                <input type="hidden" name="id" value="">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label>Nom</label>
                                <input name="name" type="text" class="form-control"/>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label>Exclusive pour</label>
                                <select name="partner_id" class="form-control">
                                    <option value="" selected="">Tout le monde</option>
	                                @foreach ($partners as $partner)
		                                <option value="{{$partner->id}}">{{$partner->name}}</option>
	                                @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div id="formation-subcategories"></div>
                    <div class="text-center">
                        <button type="button" class="btn bg-gradient-primary" id="add-subcategory-line-button">
                            Ajouter une ligne
                        </button>
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
    var defaultSubCategoryForm = {
	    id : null,
	    modality : '',
	    formula : '',
	    convenience : '',
	    hourly_volume : '',
	    price : '',
	    is_monthly : false
    };
    var subCategoriesFormArray = [];
    var buildArrayForm = function(target){
	    target.empty().html('');
	    $.each(subCategoriesFormArray, function(index, subCategoryForm){
		    var closeFormDom = $('<div>')
			    .addClass('btn btn-danger')
			    .data('index', index)
			    .html('<span class="fa fa-times"></span>')
			    .on('click', function(){
				    var self = $(this);
				    subCategoriesFormArray.splice(self.data('index'), 1);
				    buildArrayForm(target);
			    });
		    
		    var modalitySelectDom = $('<select>')
			    .addClass('custom-select')
			    .append(
				    $('<option>')
					    .attr('value', '')
					    .prop('selected', true)
					    .prop('hidden', true)
					    .prop('disabled', true)
					    .text('Modalité'),
				    $('<option>')
					    .attr('value', 'En ligne')
					    .text('En ligne'),
				    $('<option>')
					    .attr('value', 'En présentiel')
					    .text('En présentiel')
			    )
			    .val(subCategoryForm.modality)
			    .data('index', index)
			    .on('change', function(){
				    var self = $(this);
				    subCategoriesFormArray[self.data('index')].modality = self.val();
			    });
		    var formulaSelectDom = $('<select>')
			    .addClass('custom-select')
			    .append(
				    $('<option>')
					    .attr('value', '')
					    .prop('selected', true)
					    .prop('hidden', true)
					    .prop('disabled', true)
					    .text('Formule'),
				    $('<option>')
					    .attr('value', 'Intensif')
					    .text('Intensif'),
				    $('<option>')
					    .attr('value', 'Extensif')
					    .text('Extensif')
			    )
			    .val(subCategoryForm.formula)
			    .data('index', index)
			    .on('change', function(){
				    var self = $(this);
				    subCategoriesFormArray[self.data('index')].formula = self.val();
			    });
		    var convenienceSelectDom = $('<select>')
			    .addClass('custom-select')
			    .append(
				    $('<option>')
					    .attr('value', '')
					    .prop('selected', true)
					    .prop('hidden', true)
					    .prop('disabled', true)
					    .text('Convenance'),
				    $('<option>')
					    .attr('value', 'En particulier')
					    .text('En particulier'),
				    $('<option>')
					    .attr('value', 'En groupe')
					    .text('En groupe')
			    )
			    .val(subCategoryForm.convenience)
			    .data('index', index)
			    .on('change', function(){
				    var self = $(this);
				    subCategoriesFormArray[self.data('index')].convenience = self.val();
			    });
		    var timerangeInputDom = $('<input>')
			    .addClass('form-control')
			    .attr('placeholder', 'Volume horaire')
			    .attr('type', 'number')
			    .val(subCategoryForm.hourly_volume)
			    .data('index', index)
			    .on('input', function(){
				    var self = $(this);
				    subCategoriesFormArray[self.data('index')].hourly_volume = self.val();
			    });
		    var priceInputDom = $('<input>')
			    .addClass('form-control')
			    .attr('type', 'number')
			    .attr('placeholder', 'Tarif')
			    .val(subCategoryForm.price)
			    .data('index', index)
			    .on('input', function(){
				    var self = $(this);
				    subCategoriesFormArray[self.data('index')].price = self.val();
			    });
		    var isMonthlyCheckDom = $('<input>')
			    .addClass('custom-control-input')
			    .attr('type', 'checkbox')
			    .attr('id', 'custom-control-monthly' + index)
			    .prop('checked', subCategoryForm.is_monthly)
			    .data('index', index)
			    .on('change', function(){
				    var self = $(this);
				    subCategoriesFormArray[self.data('index')].is_monthly = self.is(':checked');
			    });
		    target.append(
			    $('<div>')
				    .addClass('card')
				    .append(
					    $('<div>')
						    .addClass('card-body form-subcategories')
						    .append(
							    $('<input>')
								    .prop('hidden', true)
								    .val(subCategoryForm.id),
							    $('<div>')
								    .addClass('form-section')
								    .append(
									    $('<div>')
										    .addClass('row')
										    .append(
											    $('<div>')
												    .addClass('col-sm-4 col-xs-12')
												    .append(
													    $('<div>')
														    .addClass('form-group')
														    .append(
															    modalitySelectDom
														    )
												    ),
											    $('<div>')
												    .addClass('col-sm-4 col-xs-12')
												    .append(
													    $('<div>')
														    .addClass('form-group')
														    .append(
															    formulaSelectDom
														    )
												    ),
											    $('<div>')
												    .addClass('col-sm-4 col-xs-12')
												    .append(
													    $('<div>')
														    .addClass('form-group')
														    .append(
															    convenienceSelectDom
														    )
												    ),
											    $('<div>')
												    .addClass('col-sm-4 col-xs-12')
												    .append(
													    $('<div>')
														    .addClass('form-group')
														    .append(
															    $('<div>')
																    .addClass('input-group mb-3')
																    .append(
																	    timerangeInputDom,
																	    $('<div>')
																		    .addClass('input-group-append')
																		    .html('<span class="input-group-text">Heures</span>')
																    )
														    )
												    ),
											    $('<div>')
												    .addClass('col-sm-4 col-xs-12')
												    .append(
													    $('<div>')
														    .addClass('form-group')
														    .append(
															    $('<div>')
																    .addClass('input-group mb-3')
																    .append(
																	    priceInputDom,
																	    $('<div>')
																		    .addClass('input-group-append')
																		    .html('<span class="input-group-text">Ar</span>')
																    )
														    )
												    ),
											    $('<div>')
												    .addClass('col-sm-4 col-xs-12')
												    .append(
													    $('<div>')
														    .addClass('form-group')
														    .append(
															    $('<div>')
																    .addClass('custom-control custom-switch')
																    .append(
																	    isMonthlyCheckDom,
																	    $('<label>')
																		    .addClass('custom-control-label')
																		    .attr('for', 'custom-control-monthly' + index)
																		    .text('Payement mensuel')
																    )
														    )
												    )
										    )
								    ),
							    $('<div>')
								    .addClass('sub-category-close')
								    .append(
									    subCategoriesFormArray.length <= 1 ? null : closeFormDom
								    )
						    )
				    )
		    );
	    });
	    return target;
    }
    var resetForm = function(){
	    subCategoriesFormArray = [];
	    buildArrayForm($('#formation-subcategories'));
	    $('#formation-modal-form').find('[name]').filter(function(){
		    return $(this).attr('name') !== '_token';
	    }).each(function(){
		    var self = $(this);
		    self.val('');
		    self.trigger('change');
	    });
    }
    $(function(){
	    var itsModalDom = $('#formation-modal-form');
	    var formDom = itsModalDom.find('form');
	    itsModalDom.on('show.bs.modal', function(){
            resetForm();
		    $(this).find('[name="partner_id"]').select2({
			    theme : 'bootstrap4'
		    }).val('').trigger('change');
		    buildArrayForm($('#formation-subcategories'));
	    }).on('hidden.bs.modal', function(){
		    $(this).find('[name="partner_id"]').select2('destroy');
	    });
	    $('#submit-formation-form').on('click', function(){
		    formDom.trigger('submit');
	    });
	    formDom.on('submit', function(event){
		    event.preventDefault();
		    var self = $(this);
		    var data = {
			    _token : $('[name=_token]').val(),
			    name : $('[name=name]').val(),
			    subcategories : subCategoriesFormArray
		    };
		    if ($('[name=id]').val()) {
			    data.id = $('[name=id]').val();
		    }
		    if ($('[name=partner_id]').val()) {
			    data.partner_id = $('[name=partner_id]').val();
		    }
		    $.ajax({
			    url : self.attr('action'),
			    method : 'post',
			    type : 'json',
			    data : data,
			    success : function(response){
				    if (typeof formationDatatable !== 'undefined') {
					    formationDatatable.ajax.reload();
				    }
				    itsModalDom.modal('hide');
				    resetForm();
			    },
			    error : function(err1, err2, err3){
				   
			    }
		    })
	    });
	    $('[data-dismiss="modal"]').on('click', resetForm);
	    $('#add-subcategory-line-button').on('click', function(){
		    subCategoriesFormArray.push($.extend({}, defaultSubCategoryForm));
		    buildArrayForm($('#formation-subcategories'));
	    })
    })
</script>
