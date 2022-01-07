@extends('layouts.admin')
@section('content')
@can('product_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.events.index") }}">
                Back to Event List
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('global.sub_event.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('global.sub_event.fields.title') }}
                        </th>
                        <th>
                            {{ trans('global.sub_event.fields.description') }}
                        </th>
                        <th>
                            Sub Event Type
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($subEvents as $key => $event)
                        <tr data-entry-id="{{ $event->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $event->name ?? '' }}
                            </td>
                            <td>
                                {{ $event->description ?? '' }}
                            </td>
                           
                            <td>
                                @if($event->event_type_id == 1)
                                    Virtual Event
                                    @endif
                                @if($event->event_type_id == 2)
                                    On Site Event
                                @endif
                            </td>
                            <td>
                                @can('product_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.subevents.show', $event->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan
                                @can('product_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.subevents.leaderboard', $event->id) }}">
                                        View Leaderboard
                                    </a>
                                @endcan
                                <!-- @can('product_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.events.edit', $event->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan -->
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@section('scripts')
@parent
<script>
    $(function () {
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.events.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('product_delete')
  dtButtons.push(deleteButton)
@endcan

  $('.datatable:not(.ajaxTable)').DataTable({ buttons: dtButtons })
})

</script>
@endsection
@endsection