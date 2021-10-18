@extends('layouts.admin')
@section('content')
<div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.referees.index') }}">
                Back to Referee List
            </a>
        </div>
    </div>

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('global.referee.title') }}
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <th>
                        {{ trans('global.referee.fields.name') }}
                    </th>
                    <td>
                        {{ $referee->name }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('global.referee.fields.email') }}
                    </th>
                    <td>
                        {{ $referee->email }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('global.referee.fields.gender') }}
                    </th>
                    <td>
                        {{ $referee->gender }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('global.referee.fields.phone_number') }}
                    </th>
                    <td>
                        {{ $referee->phone_number }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('global.referee.fields.alternate_number') }}
                    </th>
                    <td>
                        {{ $referee->alternate_number }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('global.referee.fields.age') }}
                    </th>
                    <td>
                        {{ $referee->age }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('global.referee.fields.details') }}
                    </th>
                    <td>
                        {{ $referee->details }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('global.referee.fields.address') }}
                    </th>
                    <td>
                        {{ $referee->address }}, {{ $referee->pincode }}, {{ $referee->city }}, {{ $referee->state }}, {{ $referee->country }}
                    </td>
                </tr>
                
            </tbody>
        </table>
    </div>
</div>

@endsection