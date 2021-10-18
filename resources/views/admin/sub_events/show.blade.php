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
        {{ trans('global.show') }} {{ trans('global.event.title') }}
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <th>
                        {{ trans('global.sub_event.fields.title') }}
                    </th>
                    <td>
                        {{ $subEvent->title }}
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