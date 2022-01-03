@extends('layouts.admin')
@section('content')
<div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.events.subevent/lists', $subEvent->id) }}">
                Back to Sub Event List
            </a>
        </div>
    </div>
<div class="card">
    <div class="card-header">
        Show Sub Event Details
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <th>
                        {{ trans('global.sub_event.fields.title') }}
                    </th>
                    <td>
                        {{ $subEvent->name }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('global.sub_event.fields.description') }}
                    </th>
                    <td>
                        {!! $subEvent->description !!}
                    </td>
                </tr>
                <tr>
                    <th>
                    Category
                    </th>
                    <td>
                        {!! $subEvent->category !!}
                    </td>
                </tr>
                <tr>
                    <th>
                        Sub Event Type
                    </th>
                    @if($subEvent->event_type_id == 1)
                    <td>
                        Virtual Event
                    </td>
                    @endif
                    @if($subEvent->event_type_id == 2)
                    <td>
                        On Site Event
                    </td>
                    @endif
                </tr>
                <tr>
                    <th>
                    Start Date
                    </th>
                    <td>
                        {!! $subEvent->start_date !!}
                    </td>
                </tr>
                <tr>
                    <th>
                        Start Time
                    </th>
                    <td>
                        {!! $subEvent->start_time !!}
                    </td>
                </tr>
                <tr>
                    <th>
                        End Date
                    </th>
                    <td>
                        {!! $subEvent->end_date !!}
                    </td>
                </tr>
                <tr>
                    <th>
                        End Time
                    </th>
                    <td>
                        {!! $subEvent->end_time !!}
                    </td>
                </tr>
                <tr>
                    <th>
                        Location
                    </th>
                    <td>
                        {!! $subEvent->location !!}
                    </td>
                </tr>
                <tr>
                    <th>
                        Age
                    </th>
                    <td>
                        {!! $subEvent->age !!}
                    </td>
                </tr>
                <tr>
                    <th>
                        Timer
                    </th>
                    <td>
                        {!! $subEvent->timer !!}
                    </td>
                </tr>
                <tr>
                    <th>
                        ScoreBoard
                    </th>
                    <td>
                        {!! $subEvent->scoreboard !!}
                    </td>
                </tr>
                <!-- <tr>
                    <th>
                        {{ trans('global.sub_event.fields.price') }}
                    </th>
                    <td>
                        ${{ $subEvent->price }}
                    </td>
                </tr> -->
            </tbody>
        </table>
    </div>
</div>

@endsection