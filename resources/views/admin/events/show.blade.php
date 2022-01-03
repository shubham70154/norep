@extends('layouts.admin')
@section('content')

<div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.events.index") }}">
                Back to Event List
            </a>
        </div>
    </div>

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('global.event.title') }}
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tbody>
                <!-- <tr>
                    <th>
                        Event Type
                    </th>
                    @if($event->event_type_id == 1)
                    <td>
                        Virtual Event
                    </td>
                    @endif
                    @if($event->event_type_id == 2)
                    <td>
                        On Site Event
                    </td>
                    @endif
                </tr> -->
                <tr>
                    <th>
                        {{ trans('global.event.fields.title') }}
                    </th>
                    <td>
                        {{ $event->name }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('global.event.fields.description') }}
                    </th>
                    <td>
                        {!! $event->description !!}
                    </td>
                </tr>
                <tr>
                    <th>
                        Start Date
                    </th>
                    <td>
                        {!! $event->start_date !!}
                    </td>
                </tr>
                <tr>
                    <th>
                        Start Time
                    </th>
                    <td>
                        {!! $event->start_time !!}
                    </td>
                </tr>
                <tr>
                    <th>
                        End Date
                    </th>
                    <td>
                        {!! $event->end_date !!}
                    </td>
                </tr>
                <tr>
                    <th>
                        End Time
                    </th>
                    <td>
                        {!! $event->end_time !!}
                    </td>
                </tr>
                <tr>
                    <th>
                        Location
                    </th>
                    <td>
                        {!! $event->location !!}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('global.event.fields.price') }}
                    </th>
                    <td>
                        $ {{ $event->price }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Player Limit
                    </th>
                    <td>
                         {{ $event->player_limit }}
                    </td>
                </tr>

                <tr>
                    <th>
                        Assigned Referee
                    </th>
                    <td>
                         {{ $event->refereenames }}
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
</div>

@endsection