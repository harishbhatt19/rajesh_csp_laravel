@extends('layouts.app')
@section('title','Add Salinity Data')
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Add Salinity Data</h6>
    </div>
    <div class="card-body">
      
      <div class="col-md-6 offset-md-3">
            
            <!-- <a href="{{ route('get:download_salinity_excel_sheet','xls') }}">
                <button class="btn btn-success btn-md">Download Excel</button></a> -->
            <br><br>

        <form  action="{{ route('post:add_salinity_data') }}" method="post" class="form-horizontal" enctype="multipart/form-data">
            @csrf

            <input type="file" name="import_file"  accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />

            <button class="btn btn-primary">Import File</button>

        </form>













      </div>
      
      
        
    </div>
  </div>
</div>
@endsection
@section('customjs')
@endsection