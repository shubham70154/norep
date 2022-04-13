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
                            Name 
                        </th>
                        <th>
                            Eamil
                        </th>
                        <th>
                            Gender
                        </th>
                        <th>
                            Mobile No.
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($participantList as $key => $participant)
                        <tr data-entry-id="{{ $participant->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $participant->name ?? '' }}
                            </td>
                            <td>
                                {{ $participant->email ?? '' }}
                            </td>
                           
                            <td>
                                {{ $participant->gender}}
                            </td>
                            <td>
                                {{ $participant->mobile_no}}
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