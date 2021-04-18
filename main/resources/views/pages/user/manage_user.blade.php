@extends('layouts.app')
@section('title','Manage Employee')
@section('content')
<div class="container-fluid">
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Manage Employee</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table display responsive nowrap" style="width:100%" id="datatable_ajax">
                  <thead>
                      <tr role="row" class="heading">
                          <th>#</th>
                          <th>Name</th>
                          <th>Phone</th>
                          <th>Username</th>
                          <th>Password</th>
                          <th>Type</th>
                          <th>Balance</th>
                          
                          <th>Status</th>
                          @if(Auth::User()->user_type == 4)
                          <th>Fund</th>
                          <th>Recharge Report</th>
                          <th>DTH Report</th>
                          <th>Passbook</th>
                          <th>Delete</th>
                          @endif
                          <th>Edit</th>
                      </tr>
                  </thead>
                  <tbody>
                      @foreach($users as $key => $user)
                      <tr>
                          <td>{{ $key+1 }}</td>
                          <td>{{ $user->firstname }} {{ $user->lastname }}</td>
                          <td>{{ $user->mob_no }}</td>
                          <td>{{ $user->username }}</td>
                          <td>{{ $user->show_password }}</td>
                          <td>@if($user->user_type == 2) Distributor @elseif($user->user_type == 3) Retailer @else User @endif</td>
                          <td>{{ $user->balance }}</td>
                          
                          <td>@if($user->status == 1) Active @else Deactive @endif</td>
                          @if(Auth::User()->user_type == 4)
                          <td><a href='{{url("add-fund-user")}}/{{ $user->id }}'  class="btn btn-success btn-sm" tooltip="Fund" title="Fund">Add/Cut</a>&nbsp;</td>
                          <td><a href='{{url("user-report-rechare")}}/{{ $user->id }}'  class="btn btn-success btn-sm" tooltip="Recharge" title="Recharge">Report</a>&nbsp;</td>
                          <td><a href='{{url("user-report-dth-rechare")}}/{{ $user->id }}'  class="btn btn-success btn-sm" tooltip="DTH" title="DTH">Report</a>&nbsp;</td>
                          <td><a href='{{url("userpassbook")}}/{{ $user->id }}'  class="btn btn-success btn-sm" tooltip="Passbook" title="Passbook">Passbook</a>&nbsp;</td>
                          <td><a href='{{url("delete-user")}}/{{ $user->id }}'  class="btn btn-danger btn-sm" tooltip="Delete" title="Delete">Delete</a>&nbsp;</td>
                          @endif
                          <td><a href='{{url("edit-user")}}/{{ $user->mob_no }}'  class="btn btn-success btn-sm" tooltip="Edit" title="Edit">Edit</a>&nbsp;</td>
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