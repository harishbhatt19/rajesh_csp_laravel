@extends('layouts.app')
@section('title','Recharge Report')

@section('content')
<div class="container-fluid">
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Recharge Report</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">

          <form class="form" name="date_filter_form">
              <input type="hidden" name="user_id" value="{{ $user_id }}" id="user_id">
            <div class="form-body">

                <div class="row">

                    <div class="col-md-5">
                        <div class="form-group">
                            <small>Start Date</small>
                            <div class="input-group input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                </div>
                                <input class="form-control datepicker" id="start_date" placeholder="Select date" type="text" data-date-format="dd-mm-yyyy">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="form-group">
                            <small>End Date</small>
                            <div class="input-group input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                </div>
                                <input class="form-control datepicker" id="end_date" placeholder="Select date" type="text" data-date-format="dd-mm-yyyy">
                            </div>
                        </div>
                    </div>

                    
                    <div class="col-md-2">
                        <div class="form-group"><br>
                            <button class="form-control btn btn-primary" type="button" id="btnFiterSubmitSearch">Go</button>
                        </div>
                    </div>

                </div>
            </div>
        </form>
        
            <table class="table display responsive nowrap" style="width:100%" id="datatable_ajax">
                  <thead>
                      <tr role="row" class="heading">
                          <th>#</th>
                          <th>User Name</th>
                          <th>Mobile</th>
                          <th>Detail</th>
                          <th>Txn</th>
                          <th>Amount</th>
                          <th>Closing Balance</th>
                          <th>Status</th>
                          <th>Date</th>
                          <th>Time</th>
                          <!-- <th>Action</th> -->
                      </tr>
                  </thead>
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
        "ajax": {
            "url": "{{ route('get:userpassbook_data_ajax')}}",
              "type": 'GET',
              data: function (d) {
                d.startdate = $('#start_date').val();
                d.enddate = $('#end_date').val();  
                d.user_id = $('#user_id').val(); 
              }
        },
        "columns": [
            { "data": "id",   
                render: function (data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;    
                }
            },
            
            {"data": "user_name"}, 
            {"data": "mobile"}, 
            { "data": 'rtype', "name": 'id', "sClass": "salaryright" , "render": function (data, type, full, meta) {

                var action = "";
                if(data == 'MobileRecharge'){
                    action += 'User Mobile Recharge/Bill For OP : '+full.opname;
                }else if(data == 'DTH'){
                    action += 'User DTH Recharge For OP : '+full.opname;
                }else if(data == 'refund'){
                    action += 'Transection Refund to user wallet';
                }else if(data == ''){
                    if(full.type == 'Money Accepted'){
                        action += 'Fund added in user wallet';
                    }
                }
                return action;    }
            },    
            {"data": "transaction_id"},           
            {"data": "amount"},
            {"data": "final_balance"},
            { "data": 'status', "name": 'id', "sClass": "salaryright" , "render": function (data, type, full, meta) {

                var action = "";
                if(data == 0){
                    
                
                    action += '<a href="#" class="btn btn-warning btn-icon-split"><span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Pending</span></a>';
                }else if(data == 1){
                    action += '<a href="#" class="btn btn-success btn-icon-split"><span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text">Success</span></a>';
                }else if(full.rtype == 'refund'){
                    action += '<a href="#" class="btn btn-success btn-icon-split"><span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text">Success</span></a>';
                }else{
                    action += '<a href="#" class="btn btn-danger btn-icon-split"><span class="icon text-white-50"><i class="fas fa-info-circle"></i></span><span class="text">Fail</span></a>';
                }
                return action;    }
            },
            
            {"data": "date"},
            {"data": "time"},
            // { "data": 'id', "name": 'id', "sClass": "salaryright" , "render": function (data, type, full, meta) {

            //     var action = "";
            //     // action += '<a href={{url("edit-pond")}}/' + data + '  class="btn btn-success btn-sm" tooltip="Edit" title="Edit"><i class="fas fa-edit"></i></a>&nbsp;';
            //     // action+='<button class="btn btn-danger btn-sm"  tooltip="Delete" title="Delete" onclick="DataDelete(\'' + data + '\');"><i class="fas fa-trash-alt"></i></button>&nbsp;';
            //     return action;    }
            // }
            
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