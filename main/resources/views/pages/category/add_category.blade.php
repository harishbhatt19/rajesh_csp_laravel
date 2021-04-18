@extends('layouts.app')
@section('title')
    @if(isset($categorydata)) Edit Category @else Add Category @endif
@endsection
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Add Category</h6>
    </div>
    <div class="card-body">
      
        <div class="col-md-6 offset-md-3">
            <form action="{{ route('post:add_category') }}" method="post" enctype="multipart/form-data">
              @csrf
              
                @if(isset($categorydata))
                    <input type="hidden" name="category_id" value="{{ $categorydata->id }}">
                @endif


              <div class="form-group">
                <label for="category_name">Category Name:</label>
                <input type="text" class="form-control" id="name" placeholder="Enter category name" 
                name="category_name" value="@if(isset($categorydata)) {{ $categorydata->category_name }} @else {{ old('category_name') }} @endif" autocomplete="off" required="">
              </div>
              
              <div class="form-group">
                <label for="category_name">Status:</label><br>
                <label class="radio-inline">
                    <input type="radio" name="status" value="1" @if(isset($categorydata)) {{ ($categorydata->status == 1) ? 'checked' : '' }} @endif checked>&nbsp;Enabled</label>&nbsp;&nbsp;&nbsp;
                <label class="radio-inline">
                    <input type="radio" name="status" value="0" @if(isset($categorydata)) {{ ($categorydata->status == 0) ? 'checked' : '' }} @endif>&nbsp;Disabled</label>
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