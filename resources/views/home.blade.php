@extends('layouts.admin')
@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <h5 class="card-title">Welcome to Norep Dashboard</h5>
        </div>
        <div class="col-sm-3 mb-3 mb-md-0">
            <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Welcome {{ $user->name}}</h5>
                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary">Update Profile</a>
            </div>
            </div>
        </div>
        <div class="col-sm-3 mb-3 mb-md-0">
            <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Admin Amount Earned : {{$user->total_amount}} </h5>
                <a href="{{ route('admin.walletmanagement.list', 1) }}" class="btn btn-primary">View Transaction</a>
            </div>
            </div>
        </div>

        <div class="col-sm-3 mb-3 mb-md-0">
            <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Events Created : {{$events}}</h5>
                <a href="{{ route("admin.events.index") }}" class="btn btn-primary">View All Events</a>
            </div>
            </div>
        </div>

        <div class="col-sm-3 mb-3 mb-md-0">
            <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Total Running Events : {{$activeEvents}} </h5>
                <a href="{{ route('admin.events.runningeventlist') }}" class="btn btn-primary">View All Running Events</a>
            </div>
            </div>
        </div>

        <div class="col-sm-3 mb-3 mb-md-0">
            <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Total CrossFiter : {{$crossFiters}} </h5>
                <a href="{{ route('admin.users.index') }}" class="btn btn-primary">View All CrossFiter</a>
            </div>
            </div>
        </div>

        <div class="col-sm-3 mb-3 mb-md-0">
            <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Total Judge : {{$judges}} </h5>
                <a href="{{ route('admin.users.refereelist') }}" class="btn btn-primary">View All Judges</a>
            </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent

@endsection