@extends('layouts.app')
@section('title','OP Code')
@section('content')
<div class="container-fluid">
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">OP Code</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table display responsive nowrap" style="width:100%" id="datatable_ajax">
                  <thead>
                      <tr role="row" class="heading">
                          <th>#</th>
                          <th>OP Name</th>
                          <th>Type</th>
                          @foreach($apis as $key => $api)
                          <th>{{ $api->api_name }} OP Code</th>
                          
                          @endforeach
                      </tr>
                  </thead>
                  <tbody>
                      @foreach($ops as $key => $op)
                      <tr>
                          <td>{{ $key+1 }}</td>
                          <td>{{ $op->OperatorDescritpion }}</td>
                          <td>{{ $op->RechargeType }}</td>
                          @foreach($apis as $key => $api)
                          <th>
                              <form action="{{ route('post:opcode_update') }}" method="post" enctype="multipart/form-data" >
                                  @csrf
                                  <input type="hidden" value="{{ $api->id }}" name="apiid">
                                  <input type="hidden" value="{{ $op->opid }}" name="opid">
                                  <input type="tel" class="form-control" value="{{ $findopcode->findopcode($op->opid,$api->id) }}" name="opcode" id="opcode{{ $api->id }}{{ $op->opid }}">
                                  <button class="form-control btn btn-primary" type="submit" id="" name="opcodeupdate">Update</button>
                              </form>
                          </th>
                          
                          @endforeach
                          
                      </tr>
                      @endforeach
                  </tbody>
                  <tbody></tbody>
              </table>
          </div>
    </div>
  </div>
</div>
@endsection
@section('customjs')
<script>
    $(document).ready(function() {
    oTable = $('#datatable_ajax').DataTable({
        "processing": false,
        "serverside": true,
        "dom": 'Blfrtip',
        "buttons": [
          {
             extend: 'collection',
             text: 'Export',
             buttons: [ 'pdfHtml5', 'csvHtml5', 'copyHtml5', 'excelHtml5' ]
          }
        ],
        
        
         
    });


});
</script>
@endsection