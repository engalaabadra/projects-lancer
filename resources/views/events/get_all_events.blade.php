


@extends ("layout")
@section("content")
<?php
use App\User;
?>
<div class="innerbodybg events">

    <div class="bodysec">
        <div class="container-fluid">
            <div class="row">
                <div class="leftpanel">
                    <div class="top">
                        <?php 
                        $user=DB::table('users')->where(['email'=>Session::get('sessionUser')])->first();
                        ?>
                        @if($sphereCount!==0)
                            <img src="{{asset('/images/backend_images/spheres/small/'.$sphereImage)}}" alt="" />
                            <h1>{{$sphere->name}} <br /><span>Events</span></h1>
                        @endif
                    </div>
                    <div class="button-back">
                        <a href="{{url('/schedule-event-previous/'.$sphereId)}}" class="button">Schedule Prevoius event</a>
                        <a href="{{url('/schedule-event-upcoming/'.$sphereId)}}" class="button">Schedule Upcoming event</a>
                        <div class="clear"></div>
                        <a href="{{url('/create-room/'.$sphereId)}}" class="button">Create Room</a>
                    </div>
                    <div class="clear"></div>
                    <div id="datepicker"></div>
                    
                </div>
                <div class="rightpanel">
                    <!--<div class="box">
                        
                    </div>-->
                    <div class="desktopview">
                        <div class="column-section">
                            <div class="cover">
                                <div class="col-pro">
                                    <h2>Upcoming Events</h2>
                                    @if($eventCount!==0)
                                    @foreach ($events as $event)
                                        @if($event->event_time>now())

                                        <div>
                                            <div class="sglelistsec">
                                                <div class="row items-container">
                                                    <div class="imagesec item">
                                                        <div class="verticalalign">
                                                            <div class="imgthumbnail"><img src="{{asset('/images/backend_images/spheres/small/'.$sphereImage)}}" alt="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="topictxt item first">
                                                        <div class="verticalalign">
                                                            <div>
                                                                <?php
                                                                $user=User::where(['id'=>$event->user_id])->first();
                                                                ?>
                                                                
                                                                
                                                                <h4>Title Of Discussion: <a href="{{url('/view-details-event/'.$sphereId.'/'.$event->id)}}">Title: {{$event->title}}</a></h4>
                                                                <p>Description Event:    {{$event->description}}</p>
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="topictxt item last">
                                                        <div class="verticalalign">
                                                                
                                                            <div>
                                                                <p>Date : {{$event->event_time}}</p>
                                                                {{-- <p>Type</p>
                                                                <p>{{$event->type}}</p> --}}
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            
                                        </div>


                                        @endif
                                    @endforeach
                                    @else
                                        <div class="alert alert-info">
                                            theres is no Upcoming events , until now 
                                        </div>
                                    @endif

                                    

                                </div>

                                <div class="col-pro">
                                    <h2>Prevoius Events</h2>
                                    @if($eventCount!==0)
                                    @if($event->event_time<now())

                                    @foreach ($events as $event)
                                    <div>
                                        <div class="sglelistsec">
                                            <div class="row items-container">
                                                <div class="imagesec item">
                                                    <div class="verticalalign">
                                                        <div class="imgthumbnail"><img src="{{asset('/images/backend_images/spheres/small/'.$sphereImage)}}" alt="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="topictxt item first">
                                                    <div class="verticalalign">
                                                        <div>
                                                            <?php
                                                            $user=User::where(['id'=>$event->user_id])->first();
                                                            ?>
                                                            
                                                            
                                                            <h4>Title Of Discussion: <a href="{{url('/view-details-event/'.$sphereId.'/'.$event->id)}}">Title: {{$event->title}}</a></h4>
                                                            <p>Description Event:    {{$event->description}}</p>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="topictxt item last">
                                                    <div class="verticalalign">
                                                            
                                                        <div>
                                                            <p>Date : {{$event->event_time}}</p>
                                                            {{-- <p>Type</p>
                                                            <p>{{$event->type}}</p> --}}
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        
                                    </div>
                                    @endforeach
                                    @endif
                                    @else
                                        <div class="alert alert-info">
                                            theres is no Prevoius events , until now 
                                        </div>
                                    @endif

                                </div>
                            </div>

                        </div>
                    </div>
                   
                    
                </div>
            </div>
        </div>
    </div>
    <div class="footer">
        <div class="container-fluid">
          <marquee behavior="scroll" direction="left" onmouseover="this.stop();" onmouseout="this.start();">
          <p>Ticker tape of info............................Messages coming soon..............Survey..........Arena open</p>
          </marquee>
        </div>
      </div>
</div>
<style>
     .innerbodybg.events .bodysec{
    background-image:url("{{asset('/images/backend_images/spheres/small/'.$sphereCoverImage)}}") 
  }
</style>
@endsection
