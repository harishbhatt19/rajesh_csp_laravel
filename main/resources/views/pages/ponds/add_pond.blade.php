@extends('layouts.app')
@section('title')
    @if(isset($pondsdata)) Edit Pond @else Add Pond @endif
@endsection
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">@if(isset($pondsdata)) Edit Pond @else Add Pond @endif</h6>
    </div>
    <div class="card-body">
      
        <div class="col-md-6 offset-md-3">
            <form action="{{ route('post:add_pond') }}" method="post" enctype="multipart/form-data">
              @csrf
              
                @if(isset($pondsdata))
                    <input type="hidden" name="pond_id" value="{{ $pondsdata->id }}">
                @endif

              
                <div class="form-group">
                    <label for="exampleSelectGender">Select Category:</label>
                    <select class="form-control" id="category" name="category_id" required="">
                      @foreach($categories as $category)
                        @if(isset($pondsdata))
                            {{ $selected = ($pondsdata->category_id == $category->id) ? 'selected' : '' }}
                        @endif
                        <option value="{{ $category->id }}" @if(isset($pondsdata)) {{ $selected }} @endif>{{ $category->category_name }}</option>
                      @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="category_name">Pond Name:</label>
                    <input type="text" class="form-control" id="pond_name" placeholder="Enter pond name" 
                    name="pond_name" value="@if(isset($pondsdata)) {{ $pondsdata->pond_name }} @else {{ old('pond_name') }} @endif" autocomplete="off" required="">
                </div>
                
                <div class="form-group">
                    <label for="category_name">Status:</label><br>
                    <label class="radio-inline">
                        <input type="radio" name="status" value="1" @if(isset($pondsdata)) {{ ($pondsdata->status == 1) ? 'checked' : '' }} @endif checked>&nbsp;Enabled</label>&nbsp;&nbsp;&nbsp;
                    <label class="radio-inline">
                        <input type="radio" name="status" value="0" @if(isset($pondsdata)) {{ ($pondsdata->status == 0) ? 'checked' : '' }} @endif>&nbsp;Disabled</label>
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