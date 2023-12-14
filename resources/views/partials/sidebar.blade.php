<aside class="main-sidebar sidebar-dark-primary elevation-4">
	<!-- Brand Logo -->
	<a href="/dashboard" class="brand-link elevation-4">
		<img src="../../dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: 0.8" />
		<span class="brand-text font-weight-light">CF Ravaka SARL</span>
	</a>

	<!-- Sidebar -->
	<div class="sidebar">
		<!-- SidebarSearch Form -->
		<div class="form-inline mt-2">
			<div class="input-group" data-widget="sidebar-search">
				<input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search" />
				<div class="input-group-append">
					<button class="btn btn-sidebar">
						<i class="fas fa-search fa-fw"></i>
					</button>
				</div>
			</div>
		</div>

		<!-- Sidebar Menu -->
		<nav class="mt-2">
			<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
				<!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
				<li class="nav-item">
					<a href="/dashboard" @class(['nav-link', 'active' => request()->route()->getName() === 'app.dashboard']) class="nav-link">
						<i class="nav-icon fas fa-tachometer-alt"></i>
						<p>Tableau de bord</p>
					</a>
				</li>
				<li @class(['nav-item', 'menu-is-opening menu-open' => \Illuminate\Support\Str::startsWith(request()->route()->getName(), 'app.list')])>
					<a href="#" @class(['nav-link', 'active' => \Illuminate\Support\Str::startsWith(request()->route()->getName(), 'app.list')])>
						<i class="nav-icon fas fa-copy"></i>
						<p>
							Listes
							<i class="fas fa-angle-left right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="{{route('app.list.students')}}" @class(['nav-link', 'active' => request()->route()->getName() === 'app.list.students'])>
								<i class="far fa-circle nav-icon"></i>
								<p>Étudiant</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="{{route('app.list.partners')}}" @class(['nav-link', 'active' => request()->route()->getName() === 'app.list.partners'])>
								<i class="far fa-circle nav-icon"></i>
								<p>Partenaire</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="{{route('app.list.formations')}}" @class(['nav-link', 'active' => request()->route()->getName() === 'app.list.formations'])>
								<i class="far fa-circle nav-icon"></i>
								<p>Formation</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="/session-list" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Session</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="/bill-tracking-list" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Suivi de payement</p>
							</a>
						</li>
					</ul>
				</li>
			</ul>
		</nav>
		<!-- /.sidebar-menu -->
	</div>
	<!-- /.sidebar -->
</aside>
