@extends('layouts.app')
@section('title','Manage Category')
@section('content')
<div class="container-fluid">
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Manage Category</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table display responsive nowrap" style="width:100%" id="datatable_ajax">
                  <thead>
                      <tr role="row" class="heading">
                          <th>#</th>
                          <th>Name</th>
                          <th>Time</th>
                          <th>Status</th>
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
            "url": "{{ route('get:manage_categories_data')}}",
              "type": 'GET',
              data: function (d) {
              }
        },
        "columns": [
            { "data": "id",   
                render: function (data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;    
                }
            },
            
            {"data": "category_name"},           
            {"data": "created_at"}, 
            {"data": 'status', name: 'status' ,"render": function (data, type, full, meta) {
                    var action = "";
                    
                    switch (data)
                    {
                        case (0):
                                action = '<h6><span class="badge badge-danger">Disabled</span></h6>';
                                break;
                        case ('1'):
                                action = '<h6><span class="badge badge-success">Enabled</span></h6>';
                                break;
                        default:
                                action = data
                                break;
                    }
                    return action;
                } 
            },
            { "data": 'id', "name": 'id', "sClass": "salaryright" , "render": function (data, type, full, meta) {

                var action = "";
                action += '<a href={{url("edit-category")}}/' + data + '  class="btn btn-success btn-sm" tooltip="Edit" title="Edit"><i class="fas fa-edit"></i></a>&nbsp;';
                action+='<button class="btn btn-danger btn-sm"  tooltip="Delete" title="Delete" onclick="DataDelete(\'' + data + '\');"><i class="fas fa-trash-alt"></i></button>&nbsp;';
                return action;    }
            }
            
        ],
         
    });
});

function DataDelete(id) {
      
  var confirmD = confirm("Are you sure,you want to delete this item?");
  if (!confirmD) {
    return false;
  }
  $.ajax({
    url: "{{ route('post:delete_category') }}",
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