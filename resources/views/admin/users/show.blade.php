@extends('layouts.admin')
@section('content')
<div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.users.index") }}">
                Back to User List
            </a>
        </div>
    </div>
<div class="card">
    <div class="card-header">
        Show Details
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <th>
                        {{ trans('global.user.fields.name') }}
                    </th>
                    <td>
                        {{ $user->name }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('global.user.fields.email') }}
                    </th>
                    <td>
                        {{ $user->email }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Mobile No.
                    </th>
                    <td>
                        {{ $user->mobile_no }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Age
                    </th>
                    <td>
                        {{ $user->age }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Weight
                    </th>
                    <td>
                        {{ $user->weight }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Gender
                    </th>
                    <td>
                        {{ $user->gender }}
                    </td>
                </tr>
                
                <!-- <tr>
                    <th>
                        Roles
                    </th>
                    <td>
                        @foreach($user->roles as $id => $roles)
                            <span class="label label-info label-many">{{ $roles->title }}</span>
                        @endforeach
                    </td>
                </tr> -->
            </tbody>
        </table>
    </div>
</div>

@endsection