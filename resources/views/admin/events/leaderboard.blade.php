@extends('layouts.admin')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.0.0-beta.3/assets/owl.carousel.min.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.0.0-beta.3/assets/owl.theme.default.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.2/css/bootstrap.min.css'><link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href=
    "https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
     
        <script src=
    "https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js">
        </script>
     
        <script src=
    "https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js">
        </script>
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
        View Leaderboard for {{ $eventDetail['data']->name ?? '' }}
    </div>

    <div class="jumbotron jumbotron-fluid">
      <div class="container">

        <div class="row ">        
            <ul class="nav nav-tabs owl-carousel owl-theme" style="height: 50px;">

                <li class="active " >
                    <a data-toggle="tab"  style="border: none; margin: 10px;  "
                    href="#1"><b >Female Novice (Team)</b></a>
                </li>
                
                <li>
                    <a data-toggle="tab" style="border: none; margin: 10px;  "
                    href="#2"><b>Female Intermediate (Team)</b></a>
                </li>
                <li>
                    <a data-toggle="tab" style="border: none; margin: 10px;  "
                    href="#3"><b>Male Intermediate (Team)</b></a>
                </li>
                <li><a data-toggle="tab" style="border: none; margin: 10px;  "
                    href="#4"><b>Female Masters 45+ (Team)</b></a>
                </li>
                <li><a data-toggle="tab" style="border: none; margin: 10px;  "
                    href="#5"><b>Male Masters 45+ (Team)</b></a>
                </li>
                <li><a data-toggle="tab" style="border: none; margin: 10px;  "
                    href="#6"><b>Female Intermediate</b></a>
                </li>
                <li><a data-toggle="tab" style="border: none; margin: 10px;  "
                    href="#7"><b> Female Novice</b></a>
                </li>
                <li><a data-toggle="tab" style="border: none; margin: 10px;  "
                    href="#8"><b>Male Intermediate</b></a>
                </li>
                <li><a data-toggle="tab" style="border: none; margin: 10px;  "
                    href="#9"><b>Male Masters 45+</b></a>
                </li>
            </ul>
                    
            <div class="tab-content" style="width: 1200px; margin-top: 70px">
                <table class="table" style="margin-left: -25px; ">
                    
                </table>
                <div id="1" class=
                    "tab-pane fade in active">
                    <table class="table">
                        <thead>
                            <tr >
                              <th scope="col">Participants 1</th>  
                              <th scope="col">Total</th>
                              <th scope="col">WOD 1 <br><a data-toggle="modal" data-target="#exampleModal22" href="#"> details</a></th>
                              <th scope="col">WOD 2 <br><a data-toggle="modal" data-target="#exampleModal22" href="#"> details</a></th>
                              <th scope="col">WOD 3: Row <br><a data-toggle="modal" data-target="#exampleModal22" href="#"> details</a></th>
                            </tr>
                          </thead>
                          <thead>
                            <tr style="background-color: rgba(134, 134, 134, 0.192);" >
                              <th scope="col" style="color: grey;"># &nbsp;&nbsp;&nbsp;&nbsp;Name</th>  
                              <th scope="col" style="color: grey;">Points</th>
                              <th scope="col" style="color: grey;">Points &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Reps</th>
                              <th scope="col" style="color: grey;">Points&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Reps</th>
                              <th scope="col" style="color: grey;">Points &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Reps</th>
                            </tr>
                          </thead>
                        
                        <tbody>
                          <tr>
                            <th scope="row"  width= "30%">1 
                                <span style="margin-left: 10px;"><a style="color: black; text-decoration: none; "  href=""data-toggle="modal" data-target="#exampleModalCenter">QUICK, GRAB MY INHALER!<br>
                                <span style="font-size: 10px; margin-left: 25px;" >AREA 56 WAREHOUSE GYM</a></span></span></th>
                            <td>20</td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                          </tr>
                          <tr>
                            <th scope="row"  width= "30%">2 
                                <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                                <span style="font-size: 10px; margin-left: 25px;" >AREA 56 WAREHOUSE GYM</span></span></th>
                            <td>20</td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                          </tr>
                          <tr>
                            <th scope="row"  width= "30%">3 
                                <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                                <span style="font-size: 10px; margin-left: 25px;" >AREA 56 WAREHOUSE GYM</span></span></th>
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
                            <tr >
                              <th scope="col">Participants 2</th>  
                              <th scope="col">Total</th>
                              <th scope="col">WOD 1 <br>details</th>
                              <th scope="col">WOD 2 <br>details</th>
                              <th scope="col">WOD 3: Row <br>details</th>
                            </tr>
                          </thead>
                          <thead>
                            <tr style="background-color: rgba(134, 134, 134, 0.192);" >
                              <th scope="col" style="color: grey;"># &nbsp;&nbsp;&nbsp;&nbsp;Name</th>  
                              <th scope="col" style="color: grey;">Points</th>
                              <th scope="col" style="color: grey;">Points &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Reps</th>
                              <th scope="col" style="color: grey;">Points&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Reps</th>
                              <th scope="col" style="color: grey;">Points &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Reps</th>
                            </tr>
                          </thead>
                        
                        <tbody>
                          <tr>
                            <th scope="row"  width= "30%">1 
                                <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                                <span style="font-size: 10px; margin-left: 25px;" >AREA 56 WAREHOUSE GYM</span></span></th>
                            <td>20</td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                          </tr>
                          <tr>
                            <th scope="row"  width= "30%">2 
                                <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                                <span style="font-size: 10px; margin-left: 25px;" >AREA 56 WAREHOUSE GYM</span></span></th>
                            <td>20</td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                          </tr>
                          <tr>
                            <th scope="row"  width= "30%">3 
                                <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                                <span style="font-size: 10px; margin-left: 25px;" >AREA 56 WAREHOUSE GYM</span></span></th>
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
                            <tr >
                              <th scope="col">Participants 3</th>  
                              <th scope="col">Total</th>
                              <th scope="col">WOD 1 <br>details</th>
                              <th scope="col">WOD 2 <br>details</th>
                              <th scope="col">WOD 3: Row <br>details</th>
                            </tr>
                          </thead>
                          <thead>
                            <tr style="background-color: rgba(134, 134, 134, 0.192);" >
                              <th scope="col" style="color: grey;"># &nbsp;&nbsp;&nbsp;&nbsp;Name</th>  
                              <th scope="col" style="color: grey;">Points</th>
                              <th scope="col" style="color: grey;">Points &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Reps</th>
                              <th scope="col" style="color: grey;">Points&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Reps</th>
                              <th scope="col" style="color: grey;">Points &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Reps</th>
                            </tr>
                          </thead>
                        
                        <tbody>
                          <tr>
                            <th scope="row"  width= "30%">1 
                                <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                                <span style="font-size: 10px; margin-left: 25px;" >AREA 56 WAREHOUSE GYM</span></span></th>
                            <td>20</td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                          </tr>
                          <tr>
                            <th scope="row"  width= "30%">2 
                                <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                                <span style="font-size: 10px; margin-left: 25px;" >AREA 56 WAREHOUSE GYM</span></span></th>
                            <td>20</td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                          </tr>
                          <tr>
                            <th scope="row"  width= "30%">3 
                                <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                                <span style="font-size: 10px; margin-left: 25px;" >AREA 56 WAREHOUSE GYM</span></span></th>
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
                            <tr >
                              <th scope="col">Participants 4</th>  
                              <th scope="col">Total</th>
                              <th scope="col">WOD 1 <br>details</th>
                              <th scope="col">WOD 2 <br>details</th>
                              <th scope="col">WOD 3: Row <br>details</th>
                            </tr>
                          </thead>
                          <thead>
                            <tr style="background-color: rgba(134, 134, 134, 0.192);" >
                              <th scope="col" style="color: grey;"># &nbsp;&nbsp;&nbsp;&nbsp;Name</th>  
                              <th scope="col" style="color: grey;">Points</th>
                              <th scope="col" style="color: grey;">Points &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Reps</th>
                              <th scope="col" style="color: grey;">Points&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Reps</th>
                              <th scope="col" style="color: grey;">Points &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Reps</th>
                            </tr>
                          </thead>
                        
                        <tbody>
                          <tr>
                            <th scope="row"  width= "30%">1 
                                <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                                <span style="font-size: 10px; margin-left: 25px;" >AREA 56 WAREHOUSE GYM</span></span></th>
                            <td>20</td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                          </tr>
                          <tr>
                            <th scope="row"  width= "30%">2 
                                <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                                <span style="font-size: 10px; margin-left: 25px;" >AREA 56 WAREHOUSE GYM</span></span></th>
                            <td>20</td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                          </tr>
                          <tr>
                            <th scope="row"  width= "30%">3 
                                <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                                <span style="font-size: 10px; margin-left: 25px;" >AREA 56 WAREHOUSE GYM</span></span></th>
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
                            <tr >
                              <th scope="col">Participants 5</th>  
                              <th scope="col">Total</th>
                              <th scope="col">WOD 1 <br>details</th>
                              <th scope="col">WOD 2 <br>details</th>
                              <th scope="col">WOD 3: Row <br>details</th>
                            </tr>
                          </thead>
                          <thead>
                            <tr style="background-color: rgba(134, 134, 134, 0.192);" >
                              <th scope="col" style="color: grey;"># &nbsp;&nbsp;&nbsp;&nbsp;Name</th>  
                              <th scope="col" style="color: grey;">Points</th>
                              <th scope="col" style="color: grey;">Points &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Reps</th>
                              <th scope="col" style="color: grey;">Points&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Reps</th>
                              <th scope="col" style="color: grey;">Points &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Reps</th>
                            </tr>
                          </thead>
                        
                        <tbody>
                          <tr>
                            <th scope="row"  width= "30%">1 
                                <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                                <span style="font-size: 10px; margin-left: 25px;" >AREA 56 WAREHOUSE GYM</span></span></th>
                            <td>20</td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                          </tr>
                          <tr>
                            <th scope="row"  width= "30%">2 
                                <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                                <span style="font-size: 10px; margin-left: 25px;" >AREA 56 WAREHOUSE GYM</span></span></th>
                            <td>20</td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                          </tr>
                          <tr>
                            <th scope="row"  width= "30%">3 
                                <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                                <span style="font-size: 10px; margin-left: 25px;" >AREA 56 WAREHOUSE GYM</span></span></th>
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
                            <tr >
                              <th scope="col">Participants 6</th>  
                              <th scope="col">Total</th>
                              <th scope="col">WOD 1 <br>details</th>
                              <th scope="col">WOD 2 <br>details</th>
                              <th scope="col">WOD 3: Row <br>details</th>
                            </tr>
                          </thead>
                          <thead>
                            <tr style="background-color: rgba(134, 134, 134, 0.192);" >
                              <th scope="col" style="color: grey;"># &nbsp;&nbsp;&nbsp;&nbsp;Name</th>  
                              <th scope="col" style="color: grey;">Points</th>
                              <th scope="col" style="color: grey;">Points &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Reps</th>
                              <th scope="col" style="color: grey;">Points&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Reps</th>
                              <th scope="col" style="color: grey;">Points &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Reps</th>
                            </tr>
                          </thead>
                        
                        <tbody>
                          <tr>
                            <th scope="row"  width= "30%">1 
                                <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                                <span style="font-size: 10px; margin-left: 25px;" >AREA 56 WAREHOUSE GYM</span></span></th>
                            <td>20</td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                          </tr>
                          <tr>
                            <th scope="row"  width= "30%">2 
                                <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                                <span style="font-size: 10px; margin-left: 25px;" >AREA 56 WAREHOUSE GYM</span></span></th>
                            <td>20</td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                          </tr>
                          <tr>
                            <th scope="row"  width= "30%">3 
                                <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                                <span style="font-size: 10px; margin-left: 25px;" >AREA 56 WAREHOUSE GYM</span></span></th>
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
                            <tr >
                              <th scope="col">Participants 7</th>  
                              <th scope="col">Total</th>
                              <th scope="col">WOD 1 <br>details</th>
                              <th scope="col">WOD 2 <br>details</th>
                              <th scope="col">WOD 3: Row <br>details</th>
                            </tr>
                          </thead>
                          <thead>
                            <tr style="background-color: rgba(134, 134, 134, 0.192);" >
                              <th scope="col" style="color: grey;"># &nbsp;&nbsp;&nbsp;&nbsp;Name</th>  
                              <th scope="col" style="color: grey;">Points</th>
                              <th scope="col" style="color: grey;">Points &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Reps</th>
                              <th scope="col" style="color: grey;">Points&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Reps</th>
                              <th scope="col" style="color: grey;">Points &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Reps</th>
                            </tr>
                          </thead>
                        
                        <tbody>
                          <tr>
                            <th scope="row"  width= "30%">1 
                                <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                                <span style="font-size: 10px; margin-left: 25px;" >AREA 56 WAREHOUSE GYM</span></span></th>
                            <td>20</td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                          </tr>
                          <tr>
                            <th scope="row"  width= "30%">2 
                                <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                                <span style="font-size: 10px; margin-left: 25px;" >AREA 56 WAREHOUSE GYM</span></span></th>
                            <td>20</td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                          </tr>
                          <tr>
                            <th scope="row"  width= "30%">3 
                                <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                                <span style="font-size: 10px; margin-left: 25px;" >AREA 56 WAREHOUSE GYM</span></span></th>
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
                            <tr >
                              <th scope="col">Participants 8</th>  
                              <th scope="col">Total</th>
                              <th scope="col">WOD 1 <br>details</th>
                              <th scope="col">WOD 2 <br>details</th>
                              <th scope="col">WOD 3: Row <br>details</th>
                            </tr>
                          </thead>
                          <thead>
                            <tr style="background-color: rgba(134, 134, 134, 0.192);" >
                              <th scope="col" style="color: grey;"># &nbsp;&nbsp;&nbsp;&nbsp;Name</th>  
                              <th scope="col" style="color: grey;">Points</th>
                              <th scope="col" style="color: grey;">Points &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Reps</th>
                              <th scope="col" style="color: grey;">Points&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Reps</th>
                              <th scope="col" style="color: grey;">Points &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Reps</th>
                            </tr>
                          </thead>
                        
                        <tbody>
                          <tr>
                            <th scope="row"  width= "30%">1 
                                <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                                <span style="font-size: 10px; margin-left: 25px;" >AREA 56 WAREHOUSE GYM</span></span></th>
                            <td>20</td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                          </tr>
                          <tr>
                            <th scope="row"  width= "30%">2 
                                <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                                <span style="font-size: 10px; margin-left: 25px;" >AREA 56 WAREHOUSE GYM</span></span></th>
                            <td>20</td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                          </tr>
                          <tr>
                            <th scope="row"  width= "30%">3 
                                <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                                <span style="font-size: 10px; margin-left: 25px;" >AREA 56 WAREHOUSE GYM</span></span></th>
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
                            <tr >
                              <th scope="col">Participants 9</th>  
                              <th scope="col">Total</th>
                              <th scope="col">WOD 1 <br>details</th>
                              <th scope="col">WOD 2 <br>details</th>
                              <th scope="col">WOD 3: Row <br>details</th>
                            </tr>
                          </thead>
                          <thead>
                            <tr style="background-color: rgba(134, 134, 134, 0.192);" >
                              <th scope="col" style="color: grey;"># &nbsp;&nbsp;&nbsp;&nbsp;Name</th>  
                              <th scope="col" style="color: grey;">Points</th>
                              <th scope="col" style="color: grey;">Points &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Reps</th>
                              <th scope="col" style="color: grey;">Points&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Reps</th>
                              <th scope="col" style="color: grey;">Points &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Reps</th>
                            </tr>
                          </thead>
                        
                        <tbody>
                          <tr>
                            <th scope="row"  width= "30%">1 
                                <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                                <span style="font-size: 10px; margin-left: 25px;" >AREA 56 WAREHOUSE GYM</span></span></th>
                            <td>20</td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                          </tr>
                          <tr>
                            <th scope="row"  width= "30%">2 
                                <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                                <span style="font-size: 10px; margin-left: 25px;" >AREA 56 WAREHOUSE GYM</span></span></th>
                            <td>20</td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                            <td>20 <span id="reps" style="margin-left: 55px;">55</span></td>
                          </tr>
                          <tr>
                            <th scope="row"  width= "30%">3 
                                <span style="margin-left: 10px;">QUICK, GRAB MY INHALER!<br>
                                <span style="font-size: 10px; margin-left: 25px;" >AREA 56 WAREHOUSE GYM</span></span></th>
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
</div>

@endsection