@extends('layouts.app')
@section('title','Dashboard')
@section('content')
    <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
            <!--<a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>-->
          </div>
        
          <div class="row">
              @if(Auth::User()->id == 57)
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Recharge</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">Rs. {{ $totalrecharge }}</div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Today Recharge</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">Rs. {{ $todayrecharge }}</div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Transfer</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">Rs. {{ $totalfundtransfer }}</div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Today Fund Transfer</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">Rs. {{ $todayfundtransfer }}</div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Active User</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalactiveuser }}</div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total User</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totaluser }}</div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            @elseif(Auth::User()->user_type == 6)
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Recharge</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">Rs. {{ $totalrecharge }}</div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Today Recharge</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">Rs. {{ $todayrecharge }}</div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Profit</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">Rs. {{ round($totalprofit) }}</div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            @else
            @endif
          </div>
          
          <div class="row">

            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">R Wallet</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">Rs. {{ Auth::User()->balance }}</div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            
            

            
            <!--<div class="col-xl-3 col-md-6 mb-4">-->
            <!--  <div class="card border-left-info shadow h-100 py-2">-->
            <!--    <div class="card-body">-->
            <!--      <div class="row no-gutters align-items-center">-->
            <!--        <div class="col mr-2">-->
            <!--          <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Tasks</div>-->
            <!--          <div class="row no-gutters align-items-center">-->
            <!--            <div class="col-auto">-->
            <!--              <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">50%</div>-->
            <!--            </div>-->
            <!--            <div class="col">-->
            <!--              <div class="progress progress-sm mr-2">-->
            <!--                <div class="progress-bar bg-info" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>-->
            <!--              </div>-->
            <!--            </div>-->
            <!--          </div>-->
            <!--        </div>-->
            <!--        <div class="col-auto">-->
            <!--          <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>-->
            <!--        </div>-->
            <!--      </div>-->
            <!--    </div>-->
            <!--  </div>-->
            <!--</div>-->

            
            <!--<div class="col-xl-3 col-md-6 mb-4">-->
            <!--  <div class="card border-left-warning shadow h-100 py-2">-->
            <!--    <div class="card-body">-->
            <!--      <div class="row no-gutters align-items-center">-->
            <!--        <div class="col mr-2">-->
            <!--          <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Requests</div>-->
            <!--          <div class="h5 mb-0 font-weight-bold text-gray-800">18</div>-->
            <!--        </div>-->
            <!--        <div class="col-auto">-->
            <!--          <i class="fas fa-comments fa-2x text-gray-300"></i>-->
            <!--        </div>-->
            <!--      </div>-->
            <!--    </div>-->
            <!--  </div>-->
            <!--</div>-->
            
          </div>
          
          

          

            <div class="row">
                <div class="col-md-12 text-center">
                    <!--<img src="{{ asset('theme/img/tata.png') }}" >-->
                </div>
            </div>

          
        </div>
        <!-- /.container-fluid -->

@endsection
@section('customjs')
@endsection