@extends('main')
@section('title', 'Authentification')
@section('main-content')
	<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="{{route('app.dashboard')}}" class="h1"><b>CF</b> Ravaka</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Vous devez être connécté.e</p>

      <form action="{{route('auth.request.login')}}" method="post" autocomplete="off">
        @csrf
        <div class="input-group mb-3">
          <input name="usernameOrEmail" type="text" class="form-control" placeholder="Nom d'utilisateur" autocomplete="username">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input name="password" type="password" class="form-control" placeholder="Mot de passe" autocomplete="current-password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
	        <!-- /.col -->
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Connexion</button>
          </div>
	        <!-- /.col -->
        </div>
      </form>
    </div>
	  <!-- /.card-body -->
  </div>
		<!-- /.card -->
</div>
@endsection
@section('dynamic-script')
	<script>
      $(function(){
        $(document.body).removeClass().addClass('login-page');
      })
    </script>
@endsection