@extends('layouts.app')
@section('title')
    @if(isset($employeedata)) Edit User @else Add User @endif
@endsection
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Paytm Add Money</h6>
    </div>
    <div class="card-body">
      
      
        <div class="col-md-4 offset-md-3">
            <form action="{{ route('post:paytm_add_money') }}" method="post" enctype="multipart/form-data">
              @csrf          
            
             
              
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