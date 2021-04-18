@extends('layouts.app')
@section('title')
    @if(isset($employeedata)) Edit Employee @else Add Employee @endif
@endsection
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">@if(isset($employeedata)) Edit Employee @else Add Employee @endif</h6>
    </div>
    <div class="card-body">
      
      
        <div class="col-md-6 offset-md-3">
            <form action="{{ route('post:add_employee') }}" method="post" enctype="multipart/form-data">
              @csrf
              
                @if(isset($employeedata))
                    <input type="hidden" name="employee_id" value="{{ $employeedata->id }}">
                @endif

              <div class="form-group">
                <label for="email">Name:</label>
                <input type="text" class="form-control" id="name" placeholder="Enter name" 
                name="name" value="@if(isset($employeedata)){{ $employeedata->name }}@else{{ old('name') }}@endif" autocomplete="off" required="">
              </div>
              
              <div class="form-group">
                <label for="phone">Mobile:</label>
                <input type="text" class="form-control" id="mobile" placeholder="Enter mobile" 
                name="mobile" value="@if(isset($employeedata)){{ $employeedata->mobile }}@else{{ old('mobile') }}@endif" maxlength="10" onkeypress="return isNumber(event)"  autocomplete="off" required="">
              </div>

              @if(isset($employeedata))    
              
              @else
              <div class="form-group">
                <label for="pwd">Password:</label>
                <input type="password" class="form-control" id="pwd" placeholder="Enter password" 
                name="password" value="TCL@saltpan" autocomplete="off" required>
              </div>
              @endif
              
              <div class="form-group">
                <label for="category_name">Status:</label><br>
                <label class="radio-inline">
                    <input type="radio" name="status" value="1" @if(isset($employeedata)) {{ ($employeedata->status == 1) ? 'checked' : '' }} @endif checked>&nbsp;Enabled</label>&nbsp;&nbsp;&nbsp;
                <label class="radio-inline">
                    <input type="radio" name="status" value="0" @if(isset($employeedata)) {{ ($employeedata->status == 0) ? 'checked' : '' }} @endif>&nbsp;Disabled</label>
              </div>
              
              
              
              <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>

    </div>
  </div>
</div>
@endsection
@section('customjs')
@endsection