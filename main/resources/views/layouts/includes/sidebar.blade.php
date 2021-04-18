    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
          <i class="fas fa-smile"></i>
        </div>
        <div class="sidebar-brand-text mx-3"></div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item active">
        <a class="nav-link" href="{{ route('dashboard') }}">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">
      
      @if(Auth::User()->user_type == 0)
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities5" aria-expanded="true" aria-controls="collapseUtilities5">
          <i class="fas fa-fw fa-university"></i>
          <span>Banking</span>
        </a>
        <div id="collapseUtilities5" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Banking</h6>
            <a class="collapse-item" href="{{ route('get:aepsframe')}}">AEPS</a>
            <a class="collapse-item" href="{{ route('get:aepsframe')}}">AEPS Reprot</a>
            <a class="collapse-item" href="{{ route('get:aepsframe')}}">AEPS Ledger</a>
          </div>
        </div>
      </li>
      @endif
      @if(Auth::User()->user_type == 3)
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities1" aria-expanded="true" aria-controls="collapseUtilities1">
          <i class="fas fa-fw fa-mobile"></i>
          <span>Utility</span>
        </a>
        <div id="collapseUtilities1" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Utility</h6>
            
            <a class="collapse-item" href="{{ route('get:recharge')}}">Mobile</a>
            <a class="collapse-item" href="{{ route('get:dthrecharge')}}">DTH</a>
            <a class="collapse-item" href="{{ route('get:electricity')}}">Electricity</a>
            <a class="collapse-item" href="{{ route('get:gas')}}">GAS</a>
            <a class="collapse-item" href="{{ route('get:water')}}">Water</a>
            <a class="collapse-item" href="{{ route('get:dthrecharge')}}">Landline</a>
            <a class="collapse-item" href="{{ route('get:view_beneficiaries')}}">DMR</a>
          </div>
        </div>
      </li>
      @endif
      @if(Auth::User()->user_type == 3 || Auth::User()->user_type == 4 || Auth::User()->user_type == 2 || Auth::User()->user_type == 1 || Auth::User()->user_type == 6)
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities2" aria-expanded="true" aria-controls="collapseUtilities2">
          <i class="fas fa-fw fa-file"></i>
          <span>Report</span>
        </a>
        <div id="collapseUtilities2" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Report</h6>
            
            <a class="collapse-item" href="{{ route('get:report_recharge')}}">Mobile</a>
            <a class="collapse-item" href="{{ route('get:report_dth_recharge')}}">DTH</a>
            <a class="collapse-item" href="{{ route('get:report_addmoney')}}">Add Money</a>
            <a class="collapse-item" href="{{ route('get:report_electricity')}}">Utility</a>
            <a class="collapse-item" href="{{ route('get:mycommission')}}">My COmmission</a>
            <a class="collapse-item" href="{{ route('get:report_dmt')}}">DMR</a>
            @if(Auth::User()->user_type == 4)
            <a class="collapse-item" href="{{ route('get:report_pending')}}">Status Change</a>
            <!--<a class="collapse-item" href="{{ route('get:report_point')}}">Cut Point</a>-->
            
            @endif
          </div>
        </div>
      </li>
      @endif
      @if(Auth::User()->user_type == 3 || Auth::User()->user_type == 4 || Auth::User()->user_type == 2 || Auth::User()->user_type == 1 || Auth::User()->user_type == 6)
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities3" aria-expanded="true" aria-controls="collapseUtilities3">
          <i class="fas fa-fw fa-credit-card"></i>
          <span>Add Money</span>
        </a>
        <div id="collapseUtilities3" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Add Money</h6>
            <a class="collapse-item" href="{{ route('get:add_money')}}">Add Money</a>
            <a class="collapse-item" href="{{ route('get:paytm_add_money')}}">Paytm Add Money</a>
            @if(Auth::User()->user_type == 4 || Auth::User()->user_type == 2 || Auth::User()->user_type == 1)
            <a class="collapse-item" href="{{ route('get:pending_money')}}">Pending Request</a>
            @endif
          </div>
        </div>
      </li>
    
      @endif
      
      @if(Auth::User()->user_type == 4 || Auth::User()->user_type == 2 || Auth::User()->user_type == 1)
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities4" aria-expanded="true" aria-controls="collapseUtilities4">
          <i class="fas fa-fw fa-wrench"></i>
          <span>Users</span>
        </a>
        <div id="collapseUtilities4" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Users</h6>
            <a class="collapse-item" href="{{ route('get:add_user')}}">Add User</a>
            <a class="collapse-item" href="{{ route('get:manage_user')}}">Manage User</a>
            <a class="collapse-item" href="{{ route('get:add_package')}}">Add Package</a>
            <a class="collapse-item" href="{{ route('get:manage_package')}}">Manage Commission</a>
          </div>
        </div>
      </li>
      @endif
      
      @if(Auth::User()->user_type == 4)
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities6" aria-expanded="true" aria-controls="collapseUtilities6">
          <i class="fas fa-fw fa-wrench"></i>
          <span>Settings</span>
        </a>
        <div id="collapseUtilities6" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Settings</h6>
            <a class="collapse-item" href="{{ route('get:api_list')}}">Set Op Code</a>
            <a class="collapse-item" href="{{ route('get:add_operator')}}">Add Op Code</a>
            <a class="collapse-item" href="{{ route('get:add_news')}}">News</a>
            <a class="collapse-item" href="{{ route('get:add_banner')}}">Add Mobile Banner</a>
            <a class="collapse-item" href="{{ route('get:add_bank')}}">Add Bank</a>
            <a class="collapse-item" href="{{ route('get:manage_bank')}}">Manage Bank</a>
          </div>
        </div>
      </li>
      @endif
      


      
      
      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>