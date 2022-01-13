@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Running Events List
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('global.event.fields.title') }}
                        </th>
                        <th>
                            {{ trans('global.event.fields.description') }}
                        </th>
                        <th>
                            {{ trans('global.event.fields.price') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allevents as $key => $event)
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
                                {{ $event->price ?? '' }}
                            </td>
                            <td>
                                @can('product_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.events.show', $event->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan
                                <!-- @can('product_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.events.edit', $event->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan -->
                                @can('product_delete')
                                    <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan
                                <a class="btn btn-xs btn-info" href="{{ route('admin.events.subevent/lists', $event->id) }}">
                                        {{ trans('global.event.view_subevent') }}
                                </a>
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
  //let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
   // text: deleteButtonTrans,
    url: "{{ route('admin.events.massDestroy') }}",
   // className: 'btn-danger',
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