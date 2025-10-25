<div class="navbar-inverse set-radius-zero" >
	<div class="container">
    	<div class="navbar-header">
        	<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            	<span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
           	</button>
        </div>
    </div>
</div>

<!-- LOGO HEADER END-->
<!-- Services Section -->
<section class="menu-section">
	<div class="container-fluid">
    	<div class="row ">
			<div class="col-md-12">
				<div class="navbar-collapse collapse ">
					<ul id="menu-top" class="nav navbar-nav" style="margin-bottom: -8px;">
						<a href="{{ url('/d?pageType=0&&parentCode=0') }}" style="float: left; font-size: 25px; 
                        padding: 15px; color: #9170E4; margin-right:10px;" class="triangle-obtuse top">Logo Area</a>&nbsp;&nbsp;
						<li><a href="{{ url('/d?pageType=0&&parentCode=0') }}" class="{{ Request::path() == 'd' ? 'triangle-isosceles' : '' }}">Dashboard</a></li>
						<li><a href="{{ url('users/u?pageType=0&&parentCode=0') }}" class="{{ Request::is('users/u','users/*')? 'triangle-isosceles': '' }}">Users</a></li>
					   	<li><a href="{{ url('hr/h?pageType=0&&parentCode=0') }}" class="{{ Request::is('hr/h','hr/*')? 'triangle-isosceles': '' }}">HR</a></li>
						<li><a href="{{ url('finance/f?pageType=0&&parentCode=0') }}" class="{{ Request::is('finance/f','finance/*')? 'triangle-isosceles': '' }}">Finance</a></li>
						<li><a href="{{ url('purchase/p?pageType=0&&parentCode=0') }}" class="{{ Request::is('purchase/p','purchase/*')? 'triangle-isosceles': '' }}">Purchase</a></li>
						<li><a href="{{ url('sales/s?pageType=0&&parentCode=0') }}" class="{{ Request::is('sales/s','sales/*')? 'triangle-isosceles': '' }}">Sales</a></li>
						<li><a href="{{ url('/inventory/i?pageType=0&&parentCode=0') }}" class="{{ Request::is('inventory/i','inventory/*')? 'triangle-isosceles': '' }}">Inventory</a></li>
						<li><a href="{{ url('/reports/r?pageType=0&&parentCode=0') }}" class="{{ Request::is('reports/r','reports/*')? 'triangle-isosceles': '' }}">Reports</a></li>
						<li>
							<a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown">{{ Auth::user()->name }} <i class="fa fa-angle-down"></i></a>
							<ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
								<li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
							</ul>
						</li>
					</ul>
				</div>
			</div>

		</div>
	</div>
</section>
<!-- MENU SECTION END-->