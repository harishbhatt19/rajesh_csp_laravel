@extends('layouts.app')
@section('title')
    DMT Slab
@endsection
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">DMT Slab</h6>
      <a href="https://csp.suvidhakendra.com/main/public/manage-slab/{{$id}}">Manage Slab</a>
    </div>
    <div class="card-body">
      
      
        <div class="col-md-6 offset-md-3">
            <form action="{{ route('post:add_dmt_slab') }}" method="post" enctype="multipart/form-data">
              @csrf
              
                
              <input type="hidden" value="{{ $id }}" name="grpid" >
              <div class="form-group">
                <label for="email">Slab Name:</label>
                <input type="text" class="form-control" id="pname" placeholder="Enter Slab Name" 
                name="slab_name" autocomplete="off" required="">
              </div>
              
              <div class="form-group">
                <label for="samt">Start Amount:</label>
                <input type="text" class="form-control" id="samt" placeholder="Enter Start Amount" 
                name="samt" autocomplete="off" required="">
              </div>
              
              <div class="form-group">
                <label for="eamt">End Amount:</label>
                <input type="text" class="form-control" id="eamt" placeholder="Enter End Amount" 
                name="eamt" autocomplete="off" required="">
              </div>
              
              <div class="form-group">
                <label for="fee">Fee:</label>
                <input type="text" class="form-control" id="fee" placeholder="Enter Fee" 
                name="fee" autocomplete="off" required="">
              </div>
              
              <div class="form-group">
                  <label for="phone">Fee Type:</label>
                  <select class="form-control" name="type" required="">
                      <option>Select Fee Type</option>
                      
                      <option value="FLAT">Flat</option>
                      <option value="percentage">percentage</option>
                      
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