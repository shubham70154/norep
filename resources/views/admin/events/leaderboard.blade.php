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
  .jumbotron {
    color: white;
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
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

<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
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

        <div class="tab-content">
          <div id="11" class="tab-pane fade in active">


            <div class="row">
              <div class="col d-flex justify-content-center">

                <p><b style="font-size: 30px;"> 1st</b> <br> OverAll</p>
              </div>
              <div class="col d-flex justify-content-center">
                <p><b style="font-size: 30px;"> 7</b> <br> Total</p>
              </div>
            </div>
            <div class="row">
              <table class="table">

                <tbody style="margin-left: 50px;">
                  <tr>

                    <td><b>WOD 1</b><br>
                      Points: 1</td>
                    <td width="100px">Reps:5</td>
                  </tr>
                  <tr>

                    <td><b>WOD 1</b><br>
                      Points: 1</td>
                    <td>Time: 06:45</td>
                  </tr>
                  <tr>

                    <td><b>WOD 1</b><br>
                      Points: 1</td>
                    <td>Time: 06:45</td>
                  </tr>
                  <tr>

                    <td><b>WOD 1</b><br>
                      Points: 1</td>
                    <td>Time: 06:45</td>
                  </tr>
                  <tr>

                    <td><b>WOD 1</b><br>
                      Points: 1</td>
                    <td>Time: 06:45</td>
                  </tr>
                </tbody>
              </table>
            </div>

          </div>
          <div id="12" class="tab-pane fade">
            <div class="row">
              <div class="col d-flex justify-content-center">

                <p><b style="font-size: 30px;"> 1st</b> <br> OverAll</p>
              </div>
              <div class="col d-flex justify-content-center">
                <p><b style="font-size: 30px;"> 7</b> <br> Total</p>
              </div>
            </div>
            <div class="row">
              <table class="table">

                <tbody style="margin-left: 50px;">
                  <tr>

                    <td><b>WOD 1</b><br>
                      Points: 1</td>
                    <td width="100px">Reps:5</td>
                  </tr>
                  <tr>

                    <td><b>WOD 1</b><br>
                      Points: 1</td>
                    <td>Time: 06:45</td>
                  </tr>
                  <tr>

                    <td><b>WOD 1</b><br>
                      Points: 1</td>
                    <td>Time: 06:45</td>
                  </tr>
                  <tr>

                    <td><b>WOD 1</b><br>
                      Points: 1</td>
                    <td>Time: 06:45</td>
                  </tr>
                  <tr>

                    <td><b>WOD 1</b><br>
                      Points: 1</td>
                    <td>Time: 06:45</td>
                  </tr>
                </tbody>
              </table>
            </div>

          </div>
          <div id="13" class="tab-pane fade">
            <div class="row">

              <div class="col">
                <!-- <img src="./assets/images/download.png" style="width: 50px; margin-top: 15px;"> -->
              </div>
              <div class="col" style="margin-top: 15px; margin-left: -350px;">
                <p2><b>KARA SKELLEY</b><br>AREA 56 WAREHOUSE GYM</p2>

              </div>
            </div>
            <hr>
            <div class="row">

              <div class="col">
                <!-- <img src="./assets/images/download.png" style="width: 50px; margin-top: 15px;"> -->
              </div>
              <div class="col" style="margin-top: 15px; margin-left: -350px;">
                <p2><b>KARA SKELLEY</b><br>AREA 56 WAREHOUSE GYM</p2>

              </div>
            </div>
            <hr>
            <div class="row">

              <div class="col">
                <!-- <img src="./assets/images/download.png" style="width: 50px; margin-top: 15px;"> -->
              </div>
              <div class="col" style="margin-top: 15px; margin-left: -350px;">
                <p2><b>KARA SKELLEY</b><br>AREA 56 WAREHOUSE GYM</p2>

              </div>
            </div>
            <hr>
            <div class="row">

              <div class="col">
                <!-- <img src="./assets/images/download.png" style="width: 50px; margin-top: 15px;"> -->
              </div>
              <div class="col" style="margin-top: 15px; margin-left: -350px;">
                <p2><b>KARA SKELLEY</b><br>AREA 56 WAREHOUSE GYM</p2>

              </div>
            </div>
            <hr>
            <div class="row">

              <div class="col">
                <!-- <img src="./assets/images/download.png" style="width: 50px; margin-top: 15px;"> -->
              </div>
              <div class="col" style="margin-top: 15px; margin-left: -350px;">
                <p2><b>KARA SKELLEY</b><br>AREA 56 WAREHOUSE GYM</p2>

              </div>
            </div>
            <hr>
            <div class="row">

              <div class="col">
                <!-- <img src="./assets/images/download.png" style="width: 50px; margin-top: 15px;"> -->
              </div>
              <div class="col" style="margin-top: 15px; margin-left: -350px;">
                <p2><b>KARA SKELLEY</b><br>AREA 56 WAREHOUSE GYM</p2>

              </div>
            </div>
            <hr>
            <div class="row">

              <div class="col">
                <!-- <img src="./assets/images/download.png" style="width: 50px; margin-top: 15px;"> -->
              </div>
              <div class="col" style="margin-top: 15px; margin-left: -350px;">
                <p2><b>KARA SKELLEY</b><br>AREA 56 WAREHOUSE GYM</p2>

              </div>
            </div>
            <hr>

          </div>

        </div>
      </div>

    </div>
  </div>
</div>

<div class="modal fade" id="exampleModal22" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
</div>

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
    <div class="jumbotron jumbotron-fluid">
        <div class="container">
          <div class="row ">
            <ul class="nav nav-tabs owl-carousel owl-theme">
            @foreach($specifiedList as $key => $list)
            <li class="active ">
                <a href="{{ route('admin.events.leaderboard', [$list->id]) }}" data-toggle="tab" style="border: none; margin: 10px; font-size:18px; ">
                <b>{{$list->title}}</b>
              </a>
              </li>
            @endforeach

              <!-- <li class="active ">
                <a data-toggle="tab" style="border: none; margin: 10px;  " href="#1"><b>Female Novice (Team)</b></a>
              </li>

              <li>
                <a data-toggle="tab" style="border: none; margin: 10px;  " href="#2"><b>Female Intermediate
                    (Team)</b></a>
              </li>
              <li>
                <a data-toggle="tab" style="border: none; margin: 10px;  " href="#3"><b>Male Intermediate
                    (Team)</b></a>
              </li>
              <li><a data-toggle="tab" style="border: none; margin: 10px;  " href="#4"><b>Female Masters 45+
                    (Team)</b></a>
              </li>
              <li><a data-toggle="tab" style="border: none; margin: 10px;  " href="#5"><b>Male Masters 45+
                    (Team)</b></a>
              </li>
              <li><a data-toggle="tab" style="border: none; margin: 10px;  " href="#6"><b>Female Intermediate</b></a>
              </li>
              <li><a data-toggle="tab" style="border: none; margin: 10px;  " href="#7"><b> Female Novice</b></a>
              </li>
              <li><a data-toggle="tab" style="border: none; margin: 10px;  " href="#8"><b>Male Intermediate</b></a>
              </li>
              <li><a data-toggle="tab" style="border: none; margin: 10px;  " href="#9"><b>Male Masters 45+</b></a>
              </li> -->
            </ul>

            <div class="tab-content" style="width: 1200px; margin-top: 70px">
              <table class="table" style="margin-left: -25px; ">

              </table>
              <div id="1" class="tab-pane fade in active">
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">Participants 1</th>
                      <th scope="col">Total</th>
                      <th scope="col">WOD 1 <br><a data-toggle="modal" data-target="#exampleModal22" href="#">
                          details</a></th>
                      <th scope="col">WOD 2 <br><a data-toggle="modal" data-target="#exampleModal22" href="#">
                          details</a></th>
                      <th scope="col">WOD 3: Row <br><a data-toggle="modal" data-target="#exampleModal22" href="#">
                          details</a></th>
                    </tr>
                  </thead>
                  <thead>
                    <tr style="background-color: rgba(134, 134, 134, 0.192);">
                      <th scope="col" style="color: grey;"># &nbsp;&nbsp;&nbsp;&nbsp;Name</th>
                      <th scope="col" style="color: grey;">Points</th>
                      <th scope="col" style="color: grey;">Points &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Reps</th>
                      <th scope="col" style="color: grey;">Points&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Reps</th>
                      <th scope="col" style="color: grey;">Points &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Reps</th>
                    </tr>
                  </thead>

                  <tbody>
                    <tr>
                      <th scope="row" width="30%">1
                        <span style="margin-left: 10px;"><a style="color: black; text-decoration: none; " href=""
                            data-toggle="modal" data-target="#exampleModalCenter">QUICK, GRAB MY INHALER!<br>
                            <span style="font-size: 10px; margin-left: 25px;">AREA 56 WAREHOUSE GYM</a></span></span>
                      </th>
                      <td>20</td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                    </tr>
                    <tr>
                      <th scope="row" width="30%">2
                        <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                          <span style="font-size: 10px; margin-left: 25px;">AREA 56 WAREHOUSE GYM</span></span>
                      </th>
                      <td>20</td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                    </tr>
                    <tr>
                      <th scope="row" width="30%">3
                        <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                          <span style="font-size: 10px; margin-left: 25px;">AREA 56 WAREHOUSE GYM</span></span>
                      </th>
                      <td>20</td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                    </tr>
                  </tbody>
                </table>


              </div>
              <div id="2" class="tab-pane fade">
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">Participants 2</th>
                      <th scope="col">Total</th>
                      <th scope="col">WOD 1 <br>details</th>
                      <th scope="col">WOD 2 <br>details</th>
                      <th scope="col">WOD 3: Row <br>details</th>
                    </tr>
                  </thead>
                  <thead>
                    <tr style="background-color: rgba(134, 134, 134, 0.192);">
                      <th scope="col" style="color: grey;"># &nbsp;&nbsp;&nbsp;&nbsp;Name</th>
                      <th scope="col" style="color: grey;">Points</th>
                      <th scope="col" style="color: grey;">Points &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Reps</th>
                      <th scope="col" style="color: grey;">Points&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Reps</th>
                      <th scope="col" style="color: grey;">Points &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Reps</th>
                    </tr>
                  </thead>

                  <tbody>
                    <tr>
                      <th scope="row" width="30%">1
                        <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                          <span style="font-size: 10px; margin-left: 25px;">AREA 56 WAREHOUSE GYM</span></span>
                      </th>
                      <td>20</td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                    </tr>
                    <tr>
                      <th scope="row" width="30%">2
                        <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                          <span style="font-size: 10px; margin-left: 25px;">AREA 56 WAREHOUSE GYM</span></span>
                      </th>
                      <td>20</td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                    </tr>
                    <tr>
                      <th scope="row" width="30%">3
                        <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                          <span style="font-size: 10px; margin-left: 25px;">AREA 56 WAREHOUSE GYM</span></span>
                      </th>
                      <td>20</td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                    </tr>
                  </tbody>
                </table>

              </div>
              <div id="3" class="tab-pane fade">
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">Participants 3</th>
                      <th scope="col">Total</th>
                      <th scope="col">WOD 1 <br>details</th>
                      <th scope="col">WOD 2 <br>details</th>
                      <th scope="col">WOD 3: Row <br>details</th>
                    </tr>
                  </thead>
                  <thead>
                    <tr style="background-color: rgba(134, 134, 134, 0.192);">
                      <th scope="col" style="color: grey;"># &nbsp;&nbsp;&nbsp;&nbsp;Name</th>
                      <th scope="col" style="color: grey;">Points</th>
                      <th scope="col" style="color: grey;">Points &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Reps</th>
                      <th scope="col" style="color: grey;">Points&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Reps</th>
                      <th scope="col" style="color: grey;">Points &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Reps</th>
                    </tr>
                  </thead>

                  <tbody>
                    <tr>
                      <th scope="row" width="30%">1
                        <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                          <span style="font-size: 10px; margin-left: 25px;">AREA 56 WAREHOUSE GYM</span></span>
                      </th>
                      <td>20</td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                    </tr>
                    <tr>
                      <th scope="row" width="30%">2
                        <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                          <span style="font-size: 10px; margin-left: 25px;">AREA 56 WAREHOUSE GYM</span></span>
                      </th>
                      <td>20</td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                    </tr>
                    <tr>
                      <th scope="row" width="30%">3
                        <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                          <span style="font-size: 10px; margin-left: 25px;">AREA 56 WAREHOUSE GYM</span></span>
                      </th>
                      <td>20</td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                    </tr>
                  </tbody>
                </table>

              </div>
              <div id="4" class="tab-pane fade">
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">Participants 4</th>
                      <th scope="col">Total</th>
                      <th scope="col">WOD 1 <br>details</th>
                      <th scope="col">WOD 2 <br>details</th>
                      <th scope="col">WOD 3: Row <br>details</th>
                    </tr>
                  </thead>
                  <thead>
                    <tr style="background-color: rgba(134, 134, 134, 0.192);">
                      <th scope="col" style="color: grey;"># &nbsp;&nbsp;&nbsp;&nbsp;Name</th>
                      <th scope="col" style="color: grey;">Points</th>
                      <th scope="col" style="color: grey;">Points &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Reps</th>
                      <th scope="col" style="color: grey;">Points&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Reps</th>
                      <th scope="col" style="color: grey;">Points &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Reps</th>
                    </tr>
                  </thead>

                  <tbody>
                    <tr>
                      <th scope="row" width="30%">1
                        <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                          <span style="font-size: 10px; margin-left: 25px;">AREA 56 WAREHOUSE GYM</span></span>
                      </th>
                      <td>20</td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                    </tr>
                    <tr>
                      <th scope="row" width="30%">2
                        <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                          <span style="font-size: 10px; margin-left: 25px;">AREA 56 WAREHOUSE GYM</span></span>
                      </th>
                      <td>20</td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                    </tr>
                    <tr>
                      <th scope="row" width="30%">3
                        <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                          <span style="font-size: 10px; margin-left: 25px;">AREA 56 WAREHOUSE GYM</span></span>
                      </th>
                      <td>20</td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                    </tr>
                  </tbody>
                </table>


              </div>
              <div id="5" class="tab-pane fade">
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">Participants 5</th>
                      <th scope="col">Total</th>
                      <th scope="col">WOD 1 <br>details</th>
                      <th scope="col">WOD 2 <br>details</th>
                      <th scope="col">WOD 3: Row <br>details</th>
                    </tr>
                  </thead>
                  <thead>
                    <tr style="background-color: rgba(134, 134, 134, 0.192);">
                      <th scope="col" style="color: grey;"># &nbsp;&nbsp;&nbsp;&nbsp;Name</th>
                      <th scope="col" style="color: grey;">Points</th>
                      <th scope="col" style="color: grey;">Points &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Reps</th>
                      <th scope="col" style="color: grey;">Points&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Reps</th>
                      <th scope="col" style="color: grey;">Points &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Reps</th>
                    </tr>
                  </thead>

                  <tbody>
                    <tr>
                      <th scope="row" width="30%">1
                        <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                          <span style="font-size: 10px; margin-left: 25px;">AREA 56 WAREHOUSE GYM</span></span>
                      </th>
                      <td>20</td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                    </tr>
                    <tr>
                      <th scope="row" width="30%">2
                        <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                          <span style="font-size: 10px; margin-left: 25px;">AREA 56 WAREHOUSE GYM</span></span>
                      </th>
                      <td>20</td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                    </tr>
                    <tr>
                      <th scope="row" width="30%">3
                        <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                          <span style="font-size: 10px; margin-left: 25px;">AREA 56 WAREHOUSE GYM</span></span>
                      </th>
                      <td>20</td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                    </tr>
                  </tbody>
                </table>


              </div>
              <div id="6" class="tab-pane fade">
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">Participants 6</th>
                      <th scope="col">Total</th>
                      <th scope="col">WOD 1 <br>details</th>
                      <th scope="col">WOD 2 <br>details</th>
                      <th scope="col">WOD 3: Row <br>details</th>
                    </tr>
                  </thead>
                  <thead>
                    <tr style="background-color: rgba(134, 134, 134, 0.192);">
                      <th scope="col" style="color: grey;"># &nbsp;&nbsp;&nbsp;&nbsp;Name</th>
                      <th scope="col" style="color: grey;">Points</th>
                      <th scope="col" style="color: grey;">Points &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Reps</th>
                      <th scope="col" style="color: grey;">Points&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Reps</th>
                      <th scope="col" style="color: grey;">Points &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Reps</th>
                    </tr>
                  </thead>

                  <tbody>
                    <tr>
                      <th scope="row" width="30%">1
                        <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                          <span style="font-size: 10px; margin-left: 25px;">AREA 56 WAREHOUSE GYM</span></span>
                      </th>
                      <td>20</td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                    </tr>
                    <tr>
                      <th scope="row" width="30%">2
                        <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                          <span style="font-size: 10px; margin-left: 25px;">AREA 56 WAREHOUSE GYM</span></span>
                      </th>
                      <td>20</td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                    </tr>
                    <tr>
                      <th scope="row" width="30%">3
                        <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                          <span style="font-size: 10px; margin-left: 25px;">AREA 56 WAREHOUSE GYM</span></span>
                      </th>
                      <td>20</td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                    </tr>
                  </tbody>
                </table>


              </div>
              <div id="7" class="tab-pane fade">
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">Participants 7</th>
                      <th scope="col">Total</th>
                      <th scope="col">WOD 1 <br>details</th>
                      <th scope="col">WOD 2 <br>details</th>
                      <th scope="col">WOD 3: Row <br>details</th>
                    </tr>
                  </thead>
                  <thead>
                    <tr style="background-color: rgba(134, 134, 134, 0.192);">
                      <th scope="col" style="color: grey;"># &nbsp;&nbsp;&nbsp;&nbsp;Name</th>
                      <th scope="col" style="color: grey;">Points</th>
                      <th scope="col" style="color: grey;">Points &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Reps</th>
                      <th scope="col" style="color: grey;">Points&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Reps</th>
                      <th scope="col" style="color: grey;">Points &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Reps</th>
                    </tr>
                  </thead>

                  <tbody>
                    <tr>
                      <th scope="row" width="30%">1
                        <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                          <span style="font-size: 10px; margin-left: 25px;">AREA 56 WAREHOUSE GYM</span></span>
                      </th>
                      <td>20</td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                    </tr>
                    <tr>
                      <th scope="row" width="30%">2
                        <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                          <span style="font-size: 10px; margin-left: 25px;">AREA 56 WAREHOUSE GYM</span></span>
                      </th>
                      <td>20</td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                    </tr>
                    <tr>
                      <th scope="row" width="30%">3
                        <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                          <span style="font-size: 10px; margin-left: 25px;">AREA 56 WAREHOUSE GYM</span></span>
                      </th>
                      <td>20</td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                    </tr>
                  </tbody>
                </table>


              </div>
              <div id="8" class="tab-pane fade">
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">Participants 8</th>
                      <th scope="col">Total</th>
                      <th scope="col">WOD 1 <br>details</th>
                      <th scope="col">WOD 2 <br>details</th>
                      <th scope="col">WOD 3: Row <br>details</th>
                    </tr>
                  </thead>
                  <thead>
                    <tr style="background-color: rgba(134, 134, 134, 0.192);">
                      <th scope="col" style="color: grey;"># &nbsp;&nbsp;&nbsp;&nbsp;Name</th>
                      <th scope="col" style="color: grey;">Points</th>
                      <th scope="col" style="color: grey;">Points &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Reps</th>
                      <th scope="col" style="color: grey;">Points&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Reps</th>
                      <th scope="col" style="color: grey;">Points &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Reps</th>
                    </tr>
                  </thead>

                  <tbody>
                    <tr>
                      <th scope="row" width="30%">1
                        <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                          <span style="font-size: 10px; margin-left: 25px;">AREA 56 WAREHOUSE GYM</span></span>
                      </th>
                      <td>20</td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                    </tr>
                    <tr>
                      <th scope="row" width="30%">2
                        <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                          <span style="font-size: 10px; margin-left: 25px;">AREA 56 WAREHOUSE GYM</span></span>
                      </th>
                      <td>20</td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                    </tr>
                    <tr>
                      <th scope="row" width="30%">3
                        <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                          <span style="font-size: 10px; margin-left: 25px;">AREA 56 WAREHOUSE GYM</span></span>
                      </th>
                      <td>20</td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                    </tr>
                  </tbody>
                </table>


              </div>
              <div id="9" class="tab-pane fade">
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">Participants 9</th>
                      <th scope="col">Total</th>
                      <th scope="col">WOD 1 <br>details</th>
                      <th scope="col">WOD 2 <br>details</th>
                      <th scope="col">WOD 3: Row <br>details</th>
                    </tr>
                  </thead>
                  <thead>
                    <tr style="background-color: rgba(134, 134, 134, 0.192);">
                      <th scope="col" style="color: grey;"># &nbsp;&nbsp;&nbsp;&nbsp;Name</th>
                      <th scope="col" style="color: grey;">Points</th>
                      <th scope="col" style="color: grey;">Points &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Reps</th>
                      <th scope="col" style="color: grey;">Points&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Reps</th>
                      <th scope="col" style="color: grey;">Points &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Reps</th>
                    </tr>
                  </thead>

                  <tbody>
                    <tr>
                      <th scope="row" width="30%">1
                        <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                          <span style="font-size: 10px; margin-left: 25px;">AREA 56 WAREHOUSE GYM</span></span>
                      </th>
                      <td>20</td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                    </tr>
                    <tr>
                      <th scope="row" width="30%">2
                        <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                          <span style="font-size: 10px; margin-left: 25px;">AREA 56 WAREHOUSE GYM</span></span>
                      </th>
                      <td>20</td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                    </tr>
                    <tr>
                      <th scope="row" width="30%">3
                        <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                          <span style="font-size: 10px; margin-left: 25px;">AREA 56 WAREHOUSE GYM</span></span>
                      </th>
                      <td>20</td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                      <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                    </tr>
                  </tbody>
                </table>


              </div>

            </div>


          </div>
        </div>
      </div>
      <!-- </div> -->


    </div>
  </div>
</div>


@push('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js">
  </script>

  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js">
  </script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.0.0-beta.3/owl.carousel.min.js"></script>
@endpush
@endsection