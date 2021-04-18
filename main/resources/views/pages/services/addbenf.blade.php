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
            <form action="{{ route('post:add_beneficiaries') }}" method="post" enctype="multipart/form-data">
              @csrf
              
            
              <div class="form-group">
                <label for="ben_account">A/c:</label>
                <input type="text" class="form-control" id="ben_account" placeholder="Enter A/c" 
                name="ben_account" value="{{ old('ben_account') }}" onkeypress="return isNumber(event)"  autocomplete="off" required="">
              </div>
              
              <div class="form-group">
                <label for="ben_name">A/c Holder:</label>
                <input type="text" class="form-control" id="ben_name" placeholder="Enter A/c Holder" 
                name="ben_name" value="{{ old('ben_name') }}" autocomplete="off" required="">
              </div>
              
              <div class="form-group">
                <label for="benifsc">IFSC:</label>
                <input type="text" class="form-control" id="benifsc" placeholder="Enter IFSC" 
                name="benifsc" value="{{ old('benifsc') }}" autocomplete="off" required="">
              </div>
              
              <div class="form-group">
                <label for="benMobile">Mobile:</label>
                <input type="text" class="form-control" id="benMobile" placeholder="Enter mobile" 
                name="benMobile" value="{{ old('benMobile') }}" maxlength="10" onkeypress="return isNumber(event)"  autocomplete="off" required="">
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