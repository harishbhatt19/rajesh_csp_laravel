@extends('layouts.app')
@section('title')
   Mobile Recharge/Bill Payment
@endsection
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Mobile Recharge/Bill Payment</h6>
    </div>
    <div class="card-body">
      
      
        <div class="col-md-4 offset-md-3">
            <form action="{{ route('post:recharge') }}" method="post" enctype="multipart/form-data">
              @csrf
              
            <input type="hidden" value="MobileRecharge" name="rtype">
              <div class="form-group">
                  <label for="operator">Operator:</label>
                  <select class="form-control" id="opcode" name="opcode" required="">
                      <option>Select Operator</option>
                      @foreach($op as $r)
                      <option value="{{ $r->opcode }}">{{ $r->OperatorDescritpion }}</option>
                      @endforeach
                  </select>
              </div>
              
              <div class="form-group">
                <label for="phone">Mobile:</label>
                <input type="text" class="form-control" id="mobile" placeholder="Enter mobile" 
                name="mobile" value="{{ old('mobile') }}" maxlength="10" onkeypress="return isNumber(event)"  autocomplete="off" required="">
              </div>
              
              <div class="form-group">
                <label for="amount">Amount:</label>
                <input type="text" class="form-control" id="amount" placeholder="Enter amount" 
                name="amount" value="{{ old('amount') }}" maxlength="3" onkeypress="return isNumber(event)"  autocomplete="off" required="">
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