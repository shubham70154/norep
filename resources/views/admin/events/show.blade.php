@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('global.event.title') }}
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <th>
                        {{ trans('global.event.fields.title') }}
                    </th>
                    <td>
                        {{ $event->title }}
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
                        {{ trans('global.event.fields.price') }}
                    </th>
                    <td>
                        ${{ $event->price }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection