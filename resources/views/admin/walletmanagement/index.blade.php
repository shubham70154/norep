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
                            Transaction for (Event Name)
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
                    <?php print_r($transaction);?>
                        <tr data-entry-id="{{ $key }}">
                            <td>
                                {{ $transaction['transaction_type'] ?? '' }}
                            </td>
                            <td>
                                {{ $transaction->deposite ?? '' }}  {{ $transaction->withdraw ?? '' }}
                            </td>
                            <td>
                                {{ $transaction->joining_event_name ?? '' }}
                            </td>
                            <td>
                                {{ $transaction->amount_before_transaction ?? '' }}
                            </td>
                            <td>
                                {{ $transaction->amount_after_transaction ?? '' }}
                            </td>
                            <td>
                                {{ $transaction->transaction_date_time ?? '' }}
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