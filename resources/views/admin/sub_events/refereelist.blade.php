@extends('layouts.admin')
@section('content')
@can('product_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ url()->previous() }}">
                Back to Event List
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        Referee List
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th width="10">
                        Id
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
                    </tr>
                </thead>
                <tbody>
                    @foreach($refereeList as $key => $referee)
                        <tr data-entry-id="{{ $referee->id }}">
                            <td>
{{$key+1}}
                            </td>
                            <td>
                                {{ $referee->name ?? '' }}
                            </td>
                            <td>
                                {{ $referee->email ?? '' }}
                            </td>
                           
                            <td>
                                {{ $referee->gender}}
                            </td>
                            <td>
                                {{ $referee->mobile_no}}
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