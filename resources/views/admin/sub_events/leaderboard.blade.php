@extends('layouts.admin')
@section('content')
@can('product_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ url()->previous() }}">
                Back
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        View Leaderboard for {{ $result['sub_event']->name ?? '' }}
    </div>

    <div class="card-body">
        @if(isset($result['scoreboard']->header))
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                    @foreach($result['scoreboard']->header as $key => $header)
                        <th width="10">
                        {{ $header ?? '' }}
                        </th>
                    @endforeach
                    </tr>
                   
                </thead>
                <tbody>
                    @foreach($result['scoreboard']->data as $key => $data)
                        <tr data-entry-id="{{ $data['round'] }}">
                            @if(isset($data['round']))
                                <td>
                                    {{ $data['round'] ?? ''}}
                                </td>
                            @endif
                            @if(isset($data['task1']))
                                <td>
                                    {{ $data['task1'] ?? ''}}
                                </td>
                            @endif
                            @if(isset($data['task2']))
                                <td>
                                    {{ $data['task2'] ?? ''}}
                                </td>
                            @endif
                            @if(isset($data['task3']))
                                <td>
                                 {{ $data['task3'] ?? ''}}
                                </td>
                            @endif
                            @if(isset($data['task4']))
                                <td>
                                    {{ $data['task4'] ?? ''}}
                                </td>
                            @endif
                            @if(isset($data['task5']))
                                <td>
                                    {{ $data['task5'] ?? ''}}
                                </td>
                            @endif
                            @if(isset($data['task6']))
                                <td>
                                    {{ $data['task6'] ?? ''}}
                                </td>
                            @endif
                            @if(isset($data['task7']))
                                <td>
                                    {{ $data['task7'] ?? ''}}
                                </td>
                            @endif
                            @if(isset($data['task8']))
                                <td>
                                    {{ $data['task8'] ?? ''}}
                                </td>
                            @endif
                            @if(isset($data['task9']))
                                <td>
                                    {{ $data['task9'] ?? ''}}
                                </td>
                            @endif
                            @if(isset($data['task10']))
                                <td>
                                    {{ $data['task10'] ?? ''}}
                                </td>
                            @endif
                            @if(isset($data['reps'])) 
                                <td>
                                    {{ $data['reps'] ?? ''}}
                                </td>
                            @endif
                            @if(isset($data['timer']))
                                <td>
                                    {{ $data['timer'] ?? ''}}
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <h2 class="text-center">No Leaderboard found</h2>
        @endif
    </div>
</div>

@endsection