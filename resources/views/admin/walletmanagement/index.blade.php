@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
    Admin Wallet Transaction
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th>
                            Transaction Type
                        </th>
                        <th>
                            Transaction Amount
                        </th>
                        <th>
                            Transaction for (Event Fees/Event Creator)
                        </th>
                        <th>
                            Paypal Transaction ID
                        </th>
                        <th>
                            comssion
                        </th>
                        <th>
                            Amount Before Transaction
                        </th>
                        <th>
                            Amount After Transaction
                        </th>
                        <th>
                            Transaction Date Time
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactionResult as $key => $transaction)
                        <tr data-entry-id="{{$transaction['id'] }}">
                            <td>
                                {{ ucfirst($transaction['transaction_type']) ?? '' }}
                            </td>
                            <td>
                                {{ $transaction['deposite_amount'] ?? '' }}  {{ $transaction['withdraw_amount'] ?? '' }}
                            </td>
                            <td>
                                @if($transaction['transaction_type'] == 'deposite')
                                    @php 
                                    echo App\Http\Controllers\Admin\UserWalletsController::getEventDetails($transaction['user_joined_event_id']);
                                    @endphp
                                @endif
                                @if($transaction['transaction_type'] == 'withdraw')
                                    @php 
                                    echo App\Http\Controllers\Admin\EventsController::getUserDetails($transaction['user_id']);
                                    @endphp
                                @endif
                            </td>
                            <td>
                                {{ $transaction['paypal_transaction_id'] ?? '' }}
                            </td>

                            <td>
                                {{ $transaction['comssion'] ?? '--' }}
                            </td>

                            <td>
                                {{ $transaction['amount_before_transaction'] ?? '' }}
                            </td>
                            <td>
                                {{ $transaction['amount_after_transaction'] ?? '' }}
                            </td>
                            <td>
                                {{ $transaction['created_at'] ?? '' }}
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