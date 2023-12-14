@extends('authed')
@section('title', 'Type de formations')
@section('wrapped-content')
	<div class="card">
    <div class="card-header">
        <div class="card-tools">
            <button type="button" class="btn bg-gradient-dark" data-toggle="modal" data-target="#formation-modal-form">
                Nouvelle entrée
            </button>
	        @include('partials.forms.formation-form', ['partners' => $partners])
        </div>
    </div>
    <div class="card-body pb-0">
        <div class="card-body table-responsive">
            <table id="formation-datatable" class="table table-bordered table-striped dt-responsive nowrap">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Sous catégories</th>
                        <th>Disponible pour</th>
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
    var formationDatatable;
    
    function formationDataTable(options){
	    return $('#formation-datatable')
		    .on('click', '.open-update-modal', function(){
			    var self = $(this);
			    if (typeof resetForm === 'function') {
				    $('#formation-modal-form [name]').prop('disabled', true);
				    resetForm.call();
			    }
			    $('#formation-modal-form').modal('show');
			    $.ajax({
				    url : self.data('route'),
				    method : 'post',
				    type : 'json',
				    data : {
					    id : self.data('id'),
					    _token : options.token
				    },
				    success : function(response){
					    $.each(response, function(key, value){
						    var inputDom = $('#formation-modal-form [name="' + key + '"]');
						    inputDom.val(value);
						    inputDom.trigger('change');
					    });
					    $('#formation-modal-form [name]').prop('disabled', false);
					    subCategoriesFormArray = response.subcategories.slice();
					    buildArrayForm($('#formation-subcategories'));
						console.log(subCategoriesFormArray);
				    }
			    });
		    })
		    .DataTable({
			    responsive : true,
			    processing : true,
			    serverSide : true,
			    ajax : {
				    url : options.dataSource,
				    method : 'GET'
			    },
			    columns : [
				    {
					    data : 'name'
				    },
				    {
					    data : 'subcategories',
					    orderable : false,
					    searchable : false
				    },
				    {
					    data : 'availability',
					    searchable : false
				    },
				    {
					    data : 'action',
					    orderable : false,
					    searchable : false
				    }
			    ]
		    });
    }
    
    $(function(){
	    formationDatatable = formationDataTable({
		    dataSource : "{{route('app.list.formations.datatable')}}",
		    token : "{{session('_token')}}"
	    });
    })
</script>
@endsection
