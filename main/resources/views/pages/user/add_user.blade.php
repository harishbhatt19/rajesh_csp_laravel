@extends('layouts.app')
@section('title')
    @if(isset($employeedata)) Edit User @else Add User @endif
@endsection
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">@if(isset($employeedata)) Edit User @else Add User @endif</h6>
    </div>
    <div class="card-body">
      
      
        <div class="col-md-6 offset-md-3">
            <form action="{{ route('post:add_user') }}" method="post" enctype="multipart/form-data">
              @csrf
              
                @if(isset($employeedata))
                    <input type="hidden" name="employee_id" value="{{ $employeedata->id }}">
                @endif
              <div class="form-group">
                  <label for="phone">User Type:</label>
                  <select class="form-control" name="user_type" required="">
                      <option>Select User Type</option>
                      <!--<option value="1">Master Distributor</option>-->
                      <option value="2">Distributor</option>
                      <option value="3">Retailer</option>
                      @if(Auth::User()->id == 57)
                      <option value="4">White Lable</option>
                      <option value="6">Api User</option>
                      @endif
                  </select>
              </div>
              
              <div class="form-group">
                  <label for="phone">Package:</label>
                  <select class="form-control" name="package" required="">
                      <option>Select Package</option>
                      @foreach($packages as $package)
                      <option value="{{ $package->id }}">{{ $package->group_name }}</option>
                      @endforeach
                  </select>
              </div>
              
              <div class="form-group">
                <label for="email">First Name:</label>
                <input type="text" class="form-control" id="fname" placeholder="Enter First Name" 
                name="fname" value="@if(isset($employeedata)){{ $employeedata->fname }}@else{{ old('fname') }}@endif" autocomplete="off" required="">
              </div>
              
              <div class="form-group">
                <label for="email">Last Name:</label>
                <input type="text" class="form-control" id="lname" placeholder="Enter Last Name" 
                name="lname" value="@if(isset($employeedata)){{ $employeedata->lname }}@else{{ old('lname') }}@endif" autocomplete="off" required="">
              </div>
              
              <div class="form-group">
                <label for="email">Company Name:</label>
                <input type="text" class="form-control" id="cname" placeholder="Enter Company Name" 
                name="cname" value="@if(isset($employeedata)){{ $employeedata->cname }}@else{{ old('cname') }}@endif" autocomplete="off" required="">
              </div>
              
              <div class="form-group">
                <label for="phone">Mobile:</label>
                <input type="tel" class="form-control" id="mobile" placeholder="Enter mobile" 
                name="mobile" value="@if(isset($employeedata)){{ $employeedata->mobile }}@else{{ old('mobile') }}@endif" maxlength="10" onkeypress="return isNumber(event)"  autocomplete="off" required="">
              </div>
              
              <div class="form-group">
                <label for="username">User name:</label>
                <input type="tel" class="form-control" id="username" placeholder="Enter User name" 
                name="username" value="@if(isset($employeedata)){{ $employeedata->username }}@else{{ old('username') }}@endif" maxlength="10"  autocomplete="off" required="">
              </div>
              
              <div class="form-group">
                <label for="phone">Email:</label>
                <input type="email" class="form-control" id="email" placeholder="Enter email" 
                name="email" value="@if(isset($employeedata)){{ $employeedata->email }}@else{{ old('email') }}@endif"  autocomplete="off" required="">
              </div>
              
              

              @if(isset($employeedata))    
              
              @else
              <div class="form-group">
                <label for="pwd">Password:</label>
                <input type="password" class="form-control" id="pwd" placeholder="Enter password" 
                name="password" value="TCL@saltpan" autocomplete="off" required>
              </div>
              @endif
              
              
              
              
              
              <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>

    </div>
  </div>
</div>
@endsection
@section('customjs')
@endsection