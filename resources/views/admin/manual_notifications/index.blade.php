@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Send Manual Notications
    </div>

    <div class="card-body">
        <div class="content">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <input type="text" name="title" class="form-control" id="title" required placeholder="Please Enter Title">
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" id="message" name="message" required cols="30" rows="5" placeholder="Please Enter Message"></textarea>
                    </div>

                    <div>
                        <input class="btn btn-primary" id="frm-example" type="submit" value="Send Notification">
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card-header">
                        <h3>
                            Select users from the list to send the notification
                        </h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover datatable" id="example">
                            <thead>
                                <tr>
                                    <th>
                                        
                                    </th>
                                    <th>
                                        Name
                                    </th>
                                    <th>
                                        Email Id
                                    </th>
                                    <th>
                                        User Type
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $key => $user)
                                <tr data-entry-id="{{ $user->id }}">
                                    <td>

                                    </td>
                                    <td>
                                        {{ $user->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $user->email ?? '' }}
                                    </td>
                                    <td>
                                    {{ $user->user_type ?? '' }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
            </div>
        </div> 
    </div>
</div>

@section('scripts')
@parent
<script>
    
$(document).ready(function() {
    let languages = {
    'en': 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/English.json'
  };
   var table = $('#example').DataTable({
    columnDefs: [{
        orderable: false,
        className: 'select-checkbox',
        targets: 0
    }],
    select: {
      style:    'multi+shift',
      selector: 'td:first-child'
    },
    order: [[1, 'asc']],
    scrollX: true,
    pageLength: 100,
    buttons: [
      {
        extend: 'selectAll',
        className: 'btn-primary',
        text: 'Select All',
        exportOptions: {
          columns: ':visible'
        }
      },
      {
        extend: 'selectNone',
        className: 'btn-primary',
        text: 'Select None',
        exportOptions: {
          columns: ':visible'
        }
      }
    ]
   });
   
   // Handle form submission event 
   $('#frm-example').click( function(e){
    var ids = $.map(table.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });
 
      if (ids.length === 0) {
        alert('Please select record from the list')
        return
      }
      var message = $('#message').val();
      var title = $('#title').val();
        if (title === '') {
            alert('Please enter Title')
            return
        }
            
        if (message === '') {
            alert('Please enter Message')
            return
        }
      if (confirm('Are you sure to send notification?')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: "{{ route('admin.manualnotifications.sendnotification') }}",
          data: { ids: ids, title: title,message: message, _method: 'Post' }
        }).done(function () { location.reload() })
      }
   });   
});

</script>
@endsection
@endsection