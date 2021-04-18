@extends('layouts.app')
@section('title')
    Operator
@endsection
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Operator</h6>
    </div>
    <div class="card-body">
      
      
        <div class="col-md-6">
            <form action="{{ route('post:add_operator') }}" method="post" enctype="multipart/form-data">
              @csrf
              
                
              
              <div class="form-group">
                <label for="name">Operator Name:</label>
                <input type="text" class="form-control" id="name" placeholder="Enter Name" 
                name="name" autocomplete="off" required="">
              </div>
              
              <div class="form-group">
                <label for="code">Operator Code:</label>
                <input type="text" class="form-control" id="code" placeholder="Enter OPcode" 
                name="code" autocomplete="off" required="">
              </div>
              
              <div class="form-group">
                  <label for="phone">Operator Type:</label>
                  <select class="form-control" name="type" required="">
                      <option>Select Operator Type</option>
                      
                      <option value="TopUP-1">TopUP</option>
                      <option value="DTH-2">DTH</option>
                      <option value="POSTPAID-4">POSTPAID</option>
                      <option value="Electricity-7">Electricity</option>
                      <option value="Landline-11">Landline</option>
                      <option value="Water-8">Water</option>
                      <option value="GAS-6">GAS</option>
                      
                  </select>
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