@extends('layouts.admin')
@section('content')
<div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.referees.index') }}">
                Back to Referee Management
            </a>
        </div>
    </div>

<div class="card">
    <div class="card-header">
        Referee details
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <th>
                        Name
                    </th>
                    <td>
                        {{ $details->referee->name }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Email
                    </th>
                    <td>
                        {{ $details->referee->email }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Gender
                    </th>
                    <td>
                        {{ $details->referee->gender }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Mobile Number
                    </th>
                    <td>
                        {{ $details->referee->mobile_no }}
                    </td>
                </tr>
                
            </tbody>
        </table>
    </div>

    <div class="card-header">
        Events details
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tbody>
            <tr>
                    <th>
                        {{ trans('global.event.fields.title') }}
                    </th>
                    <td>
                        {{ $details->event->name ?? ''}}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('global.event.fields.description') }}
                    </th>
                    <td>
                        {{ $details->event->description ?? ''}}
                    </td>
                </tr>
                <tr>
                    <th>
                        Start DateTime
                    </th>
                    <td>
                        {{ $details->event->start_date ?? ''}}  {{ $details->event->start_time ?? ''}}
                    </td>
                </tr>
                <tr>
                    <th>
                        End DateTime
                    </th>
                    <td>
                        {{ $details->event->end_date ?? ''}} {{ $details->event->end_time ?? ''}}
                    </td>
                </tr>
               
                <tr>
                    <th>
                        Location
                    </th>
                    <td>
                        {{ $details->event->location ?? ''}}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('global.event.fields.price') }}
                    </th>
                    <td>
                        $ {{ $details->event->price?? '' }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Player Limit
                    </th>
                    <td>
                         {{ $details->event->player_limit ?? ''}}
                    </td>
                </tr>
                
            </tbody>
        </table>
    </div>
</div>

@endsection