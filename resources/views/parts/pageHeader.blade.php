<header class="page-header">
	<h2>{{ $title }}</h2>

	<div class="right-wrapper text-end">
		<ol class="breadcrumbs">
			<li>
				<a href="{{ url('/') }}">
					<i class="bx bx-home-alt"></i>
				</a>
			</li>

			<li><span>{{ $title }}</span></li>

        </ol>

		<a class="sidebar-right-toggle" data-open="sidebar-right">
			<i class="fas fa-chevron-left"></i>
		</a>
	</div>
</header>
