@extends('layouts.app')
@section('title','Slab')

@section('content')
<div class="container-fluid">
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Slab</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
          <form class="form" name="date_filter_form">
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
                          <th>Slab Name</th>
                          <th>Starting Amount</th>
                          <th>Ending Amount</th>
                          <th>CHarge</th>
                          <th>CHarge type</th>
                           <th>Action</th> 
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
            "url": "{{ route('get:manage_slab_ajax_data')}}",
              "type": 'GET',
              data: function (d) {
                d.group_id = '{{ $id }}';
                // d.enddate = $('#end_date').val();  
              }
        },
        "columns": [
            { "data": "id",   
                render: function (data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;    
                }
            },
            
            {"data": "slab_name"},
            {"data": "to_amount"},
            {"data": "from_amount"},
            {"data": "commission"},
            {"data": "commission_type"},
            { "data": 'id', "name": 'id', "sClass": "salaryright" , "render": function (data, type, full, meta) {

                var action = "";
                // action += '<a href={{url("manage-commission")}}/' + data + '  class="btn btn-success btn-sm" tooltip="Edit" title="Edit"><i class="fas fa-edit"></i></a>&nbsp;';
                // action+='<a href={{url("add-dmt-slab")}}/' + data + '  class="btn btn-success btn-sm" tooltip="Edit" title="DMT">DMT SLAB</a>&nbsp;';
                action+='<button class="btn btn-danger btn-sm"  tooltip="Delete" title="Delete" onclick="DataDelete(\'' + data + '\');"><i class="fas fa-trash-alt"></i></button>&nbsp;';
                return action;    }
            }
            
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
    url: "{{ route('post:delete_slab') }}",
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