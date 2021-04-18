@extends('layouts.app')
@section('title')
    @if(isset($employeedata)) Edit User @else Add User @endif
@endsection
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Add Money</h6>
    </div>
    <div class="card-body">
      
      
        <div class="col-md-4 offset-md-3">
            <form action="{{ route('post:add_money') }}" method="post" enctype="multipart/form-data">
              @csrf          
            
              <div class="form-group">
                <label for="bank">Bank Name:</label>
                <input type="text" class="form-control" id="bank" placeholder="Enter Bank" 
                name="bank" value="{{ old('bank') }}"  autocomplete="off" required="">
              </div>
              
              <div class="form-group">
                  <label for="type">Transfer Type:</label>
                  <select class="form-control" id="type" name="type" required="">
                      <option>Select Transfer Type</option>
                      <option value="UPI">UPI</option>
                      <option value="IMPS">IMPS</option>
                      <option value="NEFT">NEFT</option>
                      <option value="RTGS">RTGS</option>
                      <option value="BANK">BANK</option>
                      <option value="CASH">CASH</option>
                  </select>
              </div>
              
              <div class="form-group">
                <label for="bank_txn">Bank Txn No.:</label>
                <input type="text" class="form-control" id="bank_txn" placeholder="Enter Bank Txn No." 
                name="bank_txn" value="{{ old('bank_txn') }}"  autocomplete="off" required="">
              </div>
              
              
              <div class="form-group">
                <label for="date">Date:</label>
                <input type="date" class="form-control" id="date" placeholder="Enter mobile" 
                name="date" value="{{ old('date') }}" autocomplete="off" required="">
              </div>
              
              <div class="form-group">
                <label for="amount">Amount:</label>
                <input type="text" class="form-control" id="amount" placeholder="Enter amount" 
                name="amount" value="{{ old('amount') }}" maxlength="3" onkeypress="return isNumber(event)"  autocomplete="off" required="">
              </div>
              
              <div class="form-group">
                <label for="note">Remark:</label>
                <input type="text" class="form-control" id="note" placeholder="Enter Remark" 
                name="note" value="{{ old('note') }}"  autocomplete="off">
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