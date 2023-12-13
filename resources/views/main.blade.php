<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CF Ravaka | @yield('title')</title>
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<!-- Font Awesome -->
    <link rel="stylesheet" href="/plugins/fontawesome-free/css/all.min.css">
	<!-- overlayScrollbars -->
    <link rel="stylesheet" href="/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
	<!-- Theme style -->
    <link rel="stylesheet" href="/dist/css/adminlte.min.css">
	@yield('dynamic-style')
	<!-- jQuery -->
	<script src="/plugins/jquery/jquery.min.js"></script>
	<!-- Bootstrap 4 -->
<script src="/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- overlayScrollbars -->
<script src="/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
</head>
<body>
@yield('main-content')

<!-- AdminLTE App -->
<script src="/dist/js/adminlte.min.js"></script>
@yield('dynamic-script')
</body>
</html>
