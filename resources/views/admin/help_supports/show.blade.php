@extends('layouts.admin')
@section('content')
<div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.helpsupports.list') }}">
                Back to Supprot section
            </a>
        </div>
    </div>

<div class="card">
    <div class="card-header">
        Details
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <th>
                        Title
                    </th>
                    <td>
                        {{ $result->title ?? '' }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Description
                    </th>
                    <td>
                        {{ $result->description ?? '' }}
                    </td>
                </tr>
                <tr>
                    <th>
                    Submitted By 
                    </th>
                    <td>
                        {{ $result->name ?? '' }}
                    </td>
                </tr>
                <tr>
                    <th>
                    Submitted At
                    </th>
                    <td>
                        {{ $result->created_at ?? '' }}
                    </td>
                </tr>
                
            </tbody>
        </table>
    </div>
</div>

@endsection