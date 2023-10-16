<style>
.skin-blue .main-header .logo {
    background: green;
}
.skin-blue .main-header .navbar{
    background: green;
}

</style>
<!---#dfb407-->
	<header class="main-header">
		<!--<a href="#" class="logo">				
		<span class="logo-mini"><b>SFI</span>				
		<span class="logo-lg">SFI</span>
		</a>-->			
		<nav class="navbar navbar-static-top">			
			<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
				<span class="sr-only">Toggle navigation</span>
			</a>
			<div class="navbar-custom-menu" style="padding-right: 30px;">
				<h4 class="widget-user-desc" style="color:white;">
					WIRE CODE required this friday(cut-off): 
					@if ($active_wire != null)
						@if ($active_wire->wirecode != null)
							<span style="color:red;">{{ $active_wire->wirecode->code }}</span>
						@endif
					@else
						<span style="color:red;">No Active Wire</span>
					@endif
				</h4>
			</div>
		</nav>
	</header>

	<aside class="main-sidebar">
		<section class="sidebar">
			@include('inc.member.membersidenav')
		</section>
	</aside>
