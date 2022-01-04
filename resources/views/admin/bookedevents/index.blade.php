@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        Booked Event List
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th>
                            Event Name
                        </th>
                        <th>
                            Event Start DateTime
                        </th>
                        <th>
                            Event Ebd DateTime
                        </th>
                        <th>
                            Event Total Booked Amount
                        </th>
                        <th>
                            No. of Participant
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($events as $key => $event)
                        <tr data-entry-id="{{ $event->id }}">
                            <td>
                            {{ $event->name ?? '' }}
                            </td>
                            <td>
                                {{ $event->start_date ?? '' }} {{ $event->start_time ?? '' }}
                            </td>
                            <td>
                            {{ $event->end_date ?? '' }} {{ $event->end_time ?? '' }}
                            </td>
                            <td>
                            {{ $event->event_total_amount ?? '' }}
                            </td>
                            <td>
                                {{ $event->event_total_participant ?? '' }}
                            </td>
                            
                            <td>
                                @can('user_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.events.show', $event->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan
                                <!-- @can('user_edit')
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

@endsection
@endsection