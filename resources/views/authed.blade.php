@extends('main')
@section('main-content')
	<!-- Site wrapper -->
	<div class="wrapper">
		@include('partials.navs')
		@include('partials.sidebar')
		
		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			@yield('wrapped-content')
		</div>
		<!-- /.content-wrapper -->

		<footer class="main-footer">
			<div class="float-right d-none d-sm-block">
				<b>Version</b> 3.2.0
			</div>
			<strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
		</footer>
	</div>
	<!-- ./wrapper -->
@endsection