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
        {{ trans('global.create') }} {{ trans('global.referee.title_singular') }}
    </div>

    <div class="card-body">
        <form class="container" action="{{ route("admin.referees.store") }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                        <label for="name">{{ trans('global.referee.fields.name') }}*</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name', isset($referee) ? $referee->name : '') }}">
                        @if($errors->has('name'))
                            <em class="invalid-feedback">
                                {{ $errors->first('name') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('global.referee.fields.name_helper') }}
                        </p>
                    </div>
                    <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                        <label for="email">{{ trans('global.referee.fields.email') }}*</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email', isset($referee) ? $referee->email : '') }}">
                        @if($errors->has('email'))
                            <em class="invalid-feedback">
                                {{ $errors->first('email') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('global.referee.fields.email_helper') }}
                        </p>
                    </div>
                    <div class="form-group {{ $errors->has('phone_number') ? 'has-error' : '' }}">
                        <label for="phone_number">{{ trans('global.referee.fields.phone_number') }}*</label>
                        <input type="text" id="phone_number" name="phone_number" class="form-control" value="{{ old('phone_number', isset($referee) ? $referee->phone_number : '') }}">
                        @if($errors->has('phone_number'))
                            <em class="invalid-feedback">
                                {{ $errors->first('phone_number') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('global.referee.fields.phone_number_helper') }}
                        </p>
                    </div>
                    <div class="form-group {{ $errors->has('alternate_number') ? 'has-error' : '' }}">
                        <label for="alternate_number">{{ trans('global.referee.fields.alternate_number') }}</label>
                        <input type="text" id="alternate_number" name="alternate_number" class="form-control" value="{{ old('alternate_number', isset($referee) ? $referee->alternate_number : '') }}">
                        @if($errors->has('alternate_number'))
                            <em class="invalid-feedback">
                                {{ $errors->first('alternate_number') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('global.referee.fields.alternate_number_helper') }}
                        </p>
                    </div>
                    <div class="form-group {{ $errors->has('gender') ? 'has-error' : '' }}">
                        <label for="gender">{{ trans('global.referee.fields.gender') }}</label>
                        <select name="gender" id="gender" class="form-control">
                                <option value="" >--Select--</option>
                                <option value="Male" >Male</option>
                                <option value="Female" >Female</option>
                        </select>
                         @if($errors->has('gender'))
                            <em class="invalid-feedback">
                                {{ $errors->first('gender') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('global.referee.fields.gender_helper') }}
                        </p>
                    </div>
                    <div class="form-group {{ $errors->has('age') ? 'has-error' : '' }}">
                        <label for="age">{{ trans('global.referee.fields.age') }}</label>
                        <input type="number" id="age" name="age" class="form-control" value="{{ old('age', isset($referee) ? $referee->age : '') }}">
                        @if($errors->has('age'))
                            <em class="invalid-feedback">
                                {{ $errors->first('age') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('global.referee.fields.age_helper') }}
                        </p>
                    </div>
                    <div class="form-group {{ $errors->has('details') ? 'has-error' : '' }}">
                        <label for="details">{{ trans('global.referee.fields.details') }}</label>
                        <textarea rows="3" id="details" name="details" class="form-control" value="{{ old('details', isset($referee) ? $referee->details : '') }}"></textarea>
                        @if($errors->has('details'))
                            <em class="invalid-feedback">
                                {{ $errors->first('details') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('global.referee.fields.details_helper') }}
                        </p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
                        <label for="address">{{ trans('global.referee.fields.address') }}</label>
                        <input type="text" id="address" name="address" class="form-control" value="{{ old('address', isset($referee) ? $referee->address : '') }}">
                        @if($errors->has('address'))
                            <em class="invalid-feedback">
                                {{ $errors->first('address') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('global.referee.fields.address_helper') }}
                        </p>
                    </div>
                    <div class="form-group {{ $errors->has('pincode') ? 'has-error' : '' }}">
                        <label for="pincode">{{ trans('global.referee.fields.pincode') }}</label>
                        <input type="number" id="pincode" name="pincode" class="form-control" value="{{ old('pincode', isset($referee) ? $referee->pincode : '') }}">
                        @if($errors->has('pincode'))
                            <em class="invalid-feedback">
                                {{ $errors->first('pincode') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('global.referee.fields.pincode_helper') }}
                        </p>
                    </div>
                    <div class="form-group {{ $errors->has('country') ? 'has-error' : '' }}">
                        <label for="country">{{ trans('global.referee.fields.country') }}</label>
                        <select class="form-control" id="country-dropdown" name="country">
                            <option value="">Select Country</option>
                            @foreach ($countries as $country) 
                            <option value="{{$country->id}}">
                            {{$country->name}}
                            </option>
                            @endforeach
                        </select>
                        
                        @if($errors->has('country'))
                            <em class="invalid-feedback">
                                {{ $errors->first('country') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('global.referee.fields.country_helper') }}
                        </p>
                    </div>
                    <div class="form-group {{ $errors->has('state') ? 'has-error' : '' }}">
                        <label for="state">{{ trans('global.referee.fields.state') }}</label>
                        <select class="form-control" name="state" id="state-dropdown">
                        </select>
                        @if($errors->has('state'))
                            <em class="invalid-feedback">
                                {{ $errors->first('state') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('global.referee.fields.state_helper') }}
                        </p>
                    </div>
                    <div class="form-group {{ $errors->has('city') ? 'has-error' : '' }}">
                        <label for="city">{{ trans('global.referee.fields.city') }}</label>
                        <select class="form-control" name="city" id="city-dropdown">
                        </select>
                        @if($errors->has('city'))
                            <em class="invalid-feedback">
                                {{ $errors->first('city') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('global.referee.fields.city_helper') }}
                        </p>
                    </div>
                </div>
                
            </div>
            
            
            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>
    </div>
</div>

@endsection
@section('scripts')
@parent
<script>
$(function () {
    $('#country-dropdown').on('change', function() {
    var country_id = this.value;
    $("#state-dropdown").html('');
    $.ajax({
        url:"{{url('admin/get-states-by-country')}}",
        type: "POST",
        data: {
        country_id: country_id,
        _token: '{{csrf_token()}}' 
    },
    dataType : 'json',
    success: function(result){
            $('#state-dropdown').html('<option value="">Select State</option>'); 
            $.each(result.states,function(key,value){
            $("#state-dropdown").append('<option value="'+value.id+'">'+value.name+'</option>');
            });
            $('#city-dropdown').html('<option value="">Select State First</option>'); 
        }
    });
    });    
    $('#state-dropdown').on('change', function() {
    var state_id = this.value;
    $("#city-dropdown").html('');
    $.ajax({
    url:"{{url('admin/get-cities-by-state')}}",
    type: "POST",
    data: {
    state_id: state_id,
    _token: '{{csrf_token()}}' 
    },
    dataType : 'json',
    success: function(result){
        $('#city-dropdown').html('<option value="">Select City</option>'); 
        $.each(result.cities,function(key,value){
        $("#city-dropdown").append('<option value="'+value.id+'">'+value.name+'</option>');
        });
    }
    });
    });
});  
</script>
@endsection