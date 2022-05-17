@extends('layouts.admin')
@push('styles')
<link rel="stylesheet" type="text/css"
  href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.0.0-beta.3/assets/owl.carousel.min.css">
<link rel="stylesheet" type="text/css"
  href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.0.0-beta.3/assets/owl.theme.default.min.css">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
  integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.2/css/bootstrap.min.css'>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<style>
  .specified {
    background-color: #e4e7ea;
    padding: 0.5rem 1.5rem !important;
  }

  a {
    color: rgb(156, 156, 156);
    text-decoration: none;
  }

  a:hover {
    color: red;
    text-decoration: none;
  }

  #jeko {
    margin-right: 30px;
    margin-top: 120px;
    border-radius: 0px;
    margin-left: -10px;
  }

  .btn-group {
    margin-left: -20px;
  }
</style>
@endpush

<!-- <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header"
        style="height: 200px;">
        <h5
          style="text-align: center; margin-left: 50px; margin-right: 50px; margin-top: 80px; color: white; font-size: 20px;"
          class="modal-title" id="exampleModalLongTitle"><b>QUICK, GRAB MY INHALER!
            AREA 56 WAREHOUSE GYM</b></h5>
        <button type="button" style="color: white;" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" style="color: white;">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <ul class="nav nav-tabs d-flex justify-content-center" style="height: 50px;">
          <li class="active">
            <a data-toggle="tab" style="border: none;  margin: 10px;" href="#11"><b>Rank</b></a>
          </li>

          <li>
            <a data-toggle="tab" style="border: none;  margin: 10px;" href="#12"><b>Schedule</b></a>
          </li>
          <li>
            <a data-toggle="tab" style="border: none;  margin: 10px;" href="#13"><b>Teammates</b></a>
          </li>
        </ul>

       
      </div>

    </div>
  </div>
</div> -->

<!-- <div class="modal fade" id="exampleModal22" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">WOD</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        All details here
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

      </div>
    </div>
  </div>
</div> -->

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
    View Leaderboard for <b>{{ $eventDetail->name ?? '' }}</b>
  </div>

  <div class="card-body">
    <div class="specified">
        <div class="row ">
        <ul class="nav nav-pills">
        <li class="nav-item">
            <a href="{{ route('admin.events.leaderboard', $eventDetail->id) }}" style="border: none; margin: 10px; font-size:18px; " class="nav-link">
                <b>All</b>
            </a>
        </li>
        @foreach($specifiedList as $key => $list)
            <li>
            <a href="{{ route('admin.events.leaderboard', [$eventDetail->id,$list->id]) }}" style="border: none; margin: 10px; font-size:18px; " class="nav-link active">
                <b>{{$list->title}}</b>
            </a>
            </li>
        @endforeach
        </ul>

        <div class="tab-content" style="width: 100%; margin-top: 30px">
            <div class="tab-pane in active">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Participants</th>
                    <th scope="col">Total</th>
                    @foreach($eventDetail['sub_events'] as $key => $list)
                    <th scope="col">{{$list->name}}<br>
                        <!-- <a data-toggle="modal" data-target="#exampleModal22" href="#">details</a> -->
                    </th>
                    
                    @endforeach
                </tr>
                </thead>
                <thead>
                <tr style="background-color: rgba(134, 134, 134, 0.192);">
                    <th scope="col" style="color: grey;"># &nbsp;&nbsp;&nbsp;&nbsp;Name</th>
                    <th scope="col" style="color: grey;">Points</th>
                    @foreach($eventDetail['sub_events'] as $key => $list)
                    <th scope="col" style="color: grey;">Points &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Time</th>
                    @endforeach
                </tr>
                </thead>

                <tbody>

                <tr>
                @foreach($eventDetail['total']['participants'] as $key => $list)
                    <th scope="row" width="30%"> {{$key+1}}
                    <span style="margin-left: 10px;"><a style="color: black; text-decoration: none;" href=""
                        data-toggle="modal" data-target="#exampleModalCenter">{{$list->name}} </a></span>
                    </th>
                    <td>00</td>
                    @foreach($eventDetail['sub_events'] as $listing)
                    <td>{{$list->points}} <span id="reps" style="margin-left: 55px;">{{$list->time}}</span></td>
                    @endforeach
                </tr>
                @endforeach
                
                </tbody>
            </table>
            </div>

        </div>


        </div>
    </div>
      <!-- </div> -->


    </div>
  </div>
</div>


<!-- @push('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js">
  </script>

  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js">
  </script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.0.0-beta.3/owl.carousel.min.js"></script>
@endpush -->
@endsection