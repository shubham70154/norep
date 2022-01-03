@extends('layouts.admin')
@section('content')
@can('user_create')
    <!-- <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.referees.create") }}">
                {{ trans('global.add') }} {{ trans('global.referee.title_singular') }}
            </a>
        </div>
    </div> -->
@endcan
<div class="card">
    <div class="card-header">
        Referee Assigned Events
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th>
                        Referee Name
                        </th>
                        <th>
                            Referee Email
                        </th>
                        <th>
                            Assigned Event
                        </th>
                        <th>
                            Event Start DateTime
                        </th>
                        <th>
                            Event Ebd DateTime
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($referleeLists as $key => $referleeList)
                        <tr data-entry-id="{{ $referleeList->id }}">
                            <td>
                            {{ $referleeList->referee->name ?? '' }}
                            </td>
                            <td>
                            {{ $referleeList->referee->email ?? '' }}
                            </td>
                            <td>
                                {{ $referleeList->event->name ?? '' }}
                            </td>
                            <td>
                                {{ $referleeList->event->start_date ?? '' }} {{ $referleeList->event->start_time ?? '' }}
                            </td>
                            <td>
                            {{ $referleeList->event->end_date ?? '' }} {{ $referleeList->event->end_time ?? '' }}
                            </td>
                            <td>
                                @can('user_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.referees.show', $referleeList->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan
                                <!-- @can('user_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.referees.edit', $referleeList->id) }}">
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