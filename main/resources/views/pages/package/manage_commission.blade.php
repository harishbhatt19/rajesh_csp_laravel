@extends('layouts.app')
@section('title','commission')

@section('content')
<div class="container-fluid">
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">commission</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">

          <form class="form" name="date_filter_form">
              <input type="hidden" value="{{ $id }}" id="group_name">
            <div class="form-body">

                <!--<div class="row">-->

                <!--    <div class="col-md-5">-->
                <!--        <div class="form-group">-->
                <!--            <small>Start Date</small>-->
                <!--            <div class="input-group input-group-alternative">-->
                <!--                <div class="input-group-prepend">-->
                <!--                    <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>-->
                <!--                </div>-->
                <!--                <input class="form-control datepicker" id="start_date" placeholder="Select date" type="text" data-date-format="dd-mm-yyyy">-->
                <!--            </div>-->
                <!--        </div>-->
                <!--    </div>-->

                <!--    <div class="col-md-5">-->
                <!--        <div class="form-group">-->
                <!--            <small>End Date</small>-->
                <!--            <div class="input-group input-group-alternative">-->
                <!--                <div class="input-group-prepend">-->
                <!--                    <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>-->
                <!--                </div>-->
                <!--                <input class="form-control datepicker" id="end_date" placeholder="Select date" type="text" data-date-format="dd-mm-yyyy">-->
                <!--            </div>-->
                <!--        </div>-->
                <!--    </div>-->

                    
                <!--    <div class="col-md-2">-->
                <!--        <div class="form-group"><br>-->
                <!--            <button class="form-control btn btn-primary" type="button" id="btnFiterSubmitSearch">Go</button>-->
                <!--        </div>-->
                <!--    </div>-->

                <!--</div>-->
            </div>
        </form>
        
            <table class="table display responsive nowrap" style="width:100%" id="datatable_ajax">
                  <thead>
                      <tr role="row" class="heading">
                          <th>#</th>
                          <th>OP Name</th>
                          <th>OP Type</th>
                          <th>sale commission</th>
                          @if(Auth::User()->id == 57)
                          @foreach($apis as $key => $api)
                          <th>{{ $api->api_name }} Status</th>
                          
                          @endforeach
                          @endif
                          
                      </tr>
                  </thead>
                  <tbody>
                      
                  </tbody>
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
        "ajax": {
            "url": "{{ route('get:manage_commission_data_ajax')}}",
              "type": 'GET',
              data: function (d) {
                // d.startdate = $('#start_date').val();
                d.grpid = $('#group_name').val();  
              }
        },
        "columns": [
            { "data": "id",   
                render: function (data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;    
                }
            },
            
            {"data": "opname"},
            {"data": "rtype"},
            
            { "data": 'sale_commission', "name": 'id', "sClass": "salaryright" , "render": function (data, type, full, meta) {
                
                var flatselect = '';
                var perselect = '';
                if(full.sale_commission_type == 'flat'){
                    flatselect = 'selected';
                }else{
                    perselect = 'selected';
                }
                
                var action = "";
                action += '<form action="{{ route('post:update_commission') }}" method="post" enctype="multipart/form-data" ><input type="hidden" value="'+full.id+'" name="rowid"> @csrf<input type="tel" class="form-control" value="'+data+'" name="sale_commission" id="sale_commission">';
                action += '<select class="form-control" name="sale_commission_type" id="sale_commission_type">';
                action += '<option value="flat" '+flatselect+'>Flat</option>';
                action += '<option value="percentage" '+perselect+'>Percentage</option>';
                action += '</select><button class="form-control btn btn-primary" type="submit" id="">Update</button></form>';
                return action;    
                
                }
            },
            @if(Auth::User()->id == 57)
            @foreach($apis as $key => $api)
            
            { "data": 'id', "name": 'id', "sClass": "salaryright" , "render": function (data, type, full, meta) {
                var clm = 'status{{ $api->id }}';
                var action = "";
                action += '<form action="{{ route('post:api_active') }}" method="post" enctype="multipart/form-data" > @csrf ';
                action += '<input type="hidden" value="'+full.id+'" name="rowid">';
                action += '<input type="hidden" value="{{ $api->id }}" name="apid">';
                if(full.status{{ $api->id }} == 1){
                    action += '<input type="hidden" value="0" name="activee">';
                    action += '<button class="form-control btn btn-primary" type="submit" id="">Active</button>';
                }else{
                    action += '<input type="hidden" value="1" name="activee">';
                    action += '<button class="form-control btn btn-primary" type="submit" id="">DeActive</button>';
                }
                action += '</form>';
                return action;     }
            },
            
            @endforeach
            @endif
        ],
         
    });


});


$('#btnFiterSubmitSearch').click(function(){
    $('#datatable_ajax').DataTable().ajax.reload();
});

function DataDelete(id) {
      
  var confirmD = confirm("Are you sure,you want to delete this item?");
  if (!confirmD) {
    return false;
  }
  $.ajax({
    url: "{{ route('post:delete_pond') }}",
    type: "post",
    data: {'id':id, '_token':'{{ csrf_token() }}'},
    dataType: 'json',
    beforeSend: function() {
      
    },
    success: function(data) {
      if (data.status) {
          showMyToast('success', data.message);
          window.location.reload();
      } else {
          showMyToast('error', data.message);
          return false;
      }
    },
    error: function() {
    
      return true;
    }
  })
}

</script>
@endsection