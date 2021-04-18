@extends('layouts.app')
@section('title')
    Add Bank
@endsection
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Add Bank</h6>
    </div>
    <div class="card-body">
      
      
        <div class="col-md-6">
            <form action="{{ route('post:add_bank') }}" method="post" enctype="multipart/form-data">
              @csrf
              
                
              
              <div class="form-group">
                <label for="name">Bank Name:</label>
                <input type="text" class="form-control" id="bank_name" placeholder="Enter Bank Name" 
                name="bank_name" value="@if(isset($employeedata)){{ $employeedata->bank_name }}@else{{ old('bank_name') }}@endif" autocomplete="off" required="">
              </div>
              
              <div class="form-group">
                <label for="name">IFSC:</label>
                <input type="text" class="form-control" id="bank_ifsc" placeholder="Enter IFSC" 
                name="bank_ifsc" value="@if(isset($employeedata)){{ $employeedata->bank_ifsc }}@else{{ old('bank_ifsc') }}@endif" autocomplete="off" required="">
              </div>
              
              <div class="form-group">
                <label for="name">BANK BRANCH:</label>
                <input type="text" class="form-control" id="bank_branch" placeholder="Enter BANK BRANCH" 
                name="bank_branch" value="@if(isset($employeedata)){{ $employeedata->bank_branch }}@else{{ old('bank_branch') }}@endif" autocomplete="off" required="">
              </div>
              
              <div class="form-group">
                <label for="name">BANK Acc:</label>
                <input type="text" class="form-control" id="acc" placeholder="Enter BANK Acc" 
                name="acc" value="@if(isset($employeedata)){{ $employeedata->bank_branch }}@else{{ old('acc') }}@endif" autocomplete="off" required="">
              </div>
              
              <div class="form-group">
                <label for="name">BANK Acc Type:</label>
                <input type="text" class="form-control" id="acc_type" placeholder="Enter BANK Acc Type" 
                name="acc_type" value="@if(isset($employeedata)){{ $employeedata->bank_branch }}@else{{ old('acc_type') }}@endif" autocomplete="off" required="">
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