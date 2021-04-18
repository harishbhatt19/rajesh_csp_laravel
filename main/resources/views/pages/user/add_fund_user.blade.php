@extends('layouts.app')
@section('title')
    Fund
@endsection
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">{{ $users->firstname }} ( {{ $users->username }} )</h6>
    </div>
    <div class="card-body">
      
      
        <div class="col-md-3">
            <form action="{{ route('post:add_fund_user') }}" method="post" enctype="multipart/form-data">
              @csrf
              
               <input type="hidden" name="user_id" value="{{ $users->id }}" >
              
             
              
              <div class="form-group">
                <label for="phone">Recharge Amount: Rs. {{ $users->balance }}</label>
                <input type="text" class="form-control" id="addfund" placeholder="Enter Amount" 
                name="addfund" value="{{ old('addfund') }}" maxlength="4"   autocomplete="off" required="">
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