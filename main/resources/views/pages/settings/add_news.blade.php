@extends('layouts.app')
@section('title')
    News
@endsection
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">News</h6>
    </div>
    <div class="card-body">
      
      
        <div class="col-md-12">
            <form action="{{ route('post:add_news') }}" method="post" enctype="multipart/form-data">
              @csrf
              
                
              
              <div class="form-group">
                <label for="news">News:</label>
                <input type="text" class="form-control" id="news" placeholder="Enter News" 
                name="news" value="@if(isset($employeedata)){{ $employeedata->news }}@else{{ old('news') }}@endif" autocomplete="off" required="">
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