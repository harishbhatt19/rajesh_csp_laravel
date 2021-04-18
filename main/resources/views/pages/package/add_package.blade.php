@extends('layouts.app')
@section('title')
    @if(isset($employeedata)) Edit Package @else Add Package @endif
@endsection
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">@if(isset($employeedata)) Edit Package @else Add Package @endif</h6>
    </div>
    <div class="card-body">
      
      
        <div class="col-md-6 offset-md-3">
            <form action="{{ route('post:add_package') }}" method="post" enctype="multipart/form-data">
              @csrf
              
                
              
              <div class="form-group">
                <label for="email">Package Name:</label>
                <input type="text" class="form-control" id="pname" placeholder="Enter Package Name" 
                name="pname" value="@if(isset($employeedata)){{ $employeedata->pname }}@else{{ old('pname') }}@endif" autocomplete="off" required="">
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