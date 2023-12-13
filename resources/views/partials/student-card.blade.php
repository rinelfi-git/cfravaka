<div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
    <div class="card bg-light d-flex flex-fill">
        <div class="card-body">
            <h2 class="lead"><b>{{$name}}</b></h2>
            <ul class="ml-4 mb-0 fa-ul text-muted">
                <li class="small"><span class="fa-li"><i class="fas fa fa-phone"></i></span>{{$phone}}</li>
	            @if (!empty($email))
		            <li class="small"><span class="fa-li"><i class="fas fa fa-at"></i></span>{{$email}}</li>
	            @endif
	            @if (!empty($test_date))
		            <li class="small"><span class="fa-li"><i class="fas fa fa-calendar"></i></span>{{$test_date}}</li>
	            @endif
            </ul>
        </div>
        <div class="card-footer">
            <div class="text-right">
                <a href="#" class="btn btn-sm bg-teal">
                    <i class="fas fa-comments"></i>
                </a>
                <a href="#" class="btn btn-sm btn-primary">
                    <i class="fas fa-user"></i> View Profile
                </a>
            </div>
        </div>
    </div>
</div>
