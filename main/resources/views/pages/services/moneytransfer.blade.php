@extends('layouts.app')
@section('title')
   Money Transfer
@endsection
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Money Transfer</h6>
    </div>
    <div class="card-body">
      
      
        <div class="col-md-4 offset-md-3">
            <form action="{{url('moneytransfer')}}/{{ $id }}" method="post" enctype="multipart/form-data">
              @csrf
              
                
              <div class="form-group">
                <label for="ben_account">A/c:</label>
                <input type="text" class="form-control" id="ben_account" placeholder="Enter A/c" 
                name="ben_account" value="@if($check->ben_account) {{ $check->ben_account }} @endif" onkeypress="return isNumber(event)"  autocomplete="off" required="" readonly>
              </div>
              
              <div class="form-group">
                <label for="ben_name">A/c Holder:</label>
                <input type="text" class="form-control" id="ben_name" placeholder="Enter A/c Holder" 
                name="ben_name" value="@if($check->ben_name) {{ $check->ben_name }} @endif" autocomplete="off" required="" readonly>
              </div>
              
              <div class="form-group">
                <label for="benifsc">IFSC:</label>
                <input type="text" class="form-control" id="benifsc" placeholder="Enter IFSC" 
                name="benifsc" value="@if($check->benifsc) {{ $check->benifsc }} @endif" autocomplete="off" required="" readonly>
              </div>
              
              <div class="form-group">
                <label for="benMobile">Mobile:</label>
                <input type="text" class="form-control" id="benMobile" placeholder="Enter mobile" 
                name="benMobile" value="@if($check->benMobile) {{ $check->benMobile }} @endif" maxlength="10" onkeypress="return isNumber(event)"  autocomplete="off" required="" readonly>
              </div>
              
              <div class="form-group">
                  <label for="txntype">Txn Type:</label>
                  <select class="form-control" name="txntype" required="">
                      <option value="IMPS">IMPS</option>
                      <option value="NEFT">NEFT</option>
                  </select>
              </div>
              
              <div class="form-group">
                <label for="amount">Amount:</label>
                <input type="text" class="form-control" id="amount" placeholder="Enter amount" 
                name="amount" value="{{ old('amount') }}" maxlength="4" onkeypress="return isNumber(event)"  autocomplete="off" required="">
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