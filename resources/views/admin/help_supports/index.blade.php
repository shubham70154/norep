@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        Help & Support List
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th>
                            Title
                        </th>
                        <th>
                            Description
                        </th>
                        <th>
                            Submitted by
                        </th>
                        
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($helpSupports as $key => $helpSupport)
                        <tr data-entry-id="{{ $helpSupport->id }}">
                            <td>
                                {{ $helpSupport->title ?? '' }}
                            </td>
                            <td>
                                {{ $helpSupport->description ?? '' }}
                            </td>
                            <td>
                                {{ $helpSupport->name ?? '' }}
                            </td>
                            
                            
                            <td>
                                @can('user_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.helpsupports.show', $helpSupport->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan
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