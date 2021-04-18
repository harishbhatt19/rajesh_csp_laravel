@extends('layouts.app')
@section('title')
    Banner
@endsection
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Banner</h6>
    </div>
    <div class="card-body">
      
      
        <div class="col-md-12">
            <form action="{{ route('post:add_banner') }}" method="post" enctype="multipart/form-data">
              @csrf
              
                
              
              <div class="form-group">
                <label for="news">Banner:</label>
                <input type="file" class="form-control" id="banner" name="banner" required="">
              </div>
              
              
              
              
              
              
              
              <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>

    </div>
  </div>
  
  <div class="row">
            @foreach($allbanner as $r)
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <img src="{{ $r->img }}" class="img-rounded img-responsive" style="width:100%">
                      
                    </div>
                    <div class="col-auto">
                      <a href='{{url("delete-banner")}}/{{ $r->id }}'  class="btn btn-success btn-sm" tooltip="Delete" title="Delete">Delete</a>
                    </div>
                    
                  </div>
                </div>
              </div>
            </div>
            @endforeach
  </div>
</div>
@endsection
@section('customjs')
@endsection