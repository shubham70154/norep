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
        <!-- <div class="col-sm-3 mb-3 mb-md-0">
            <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Special title treatment</h5>
                
                <a href="#!" class="btn btn-primary">Go somewhere</a>
            </div>
            </div>
        </div> -->
    </div>
</div>
@endsection
@section('scripts')
@parent

@endsection