<!DOCTYPE html>
<html lang="en">
  <script src="{{asset('/js/AgoraRTCCSDK-2.4.0.js')}}"></script>
  <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  
  <script src="../../node_modules/popper.js/dist/popper.js"></script>
  <script src="{{asset('/js/app.js')}}"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="{{asset('js/jquery-migrate.min.js')}}"></script>
  <script src="{{asset('js/jquery.matchHeight.js')}}"></script>
  <script src="{{asset('js/autoheight.js')}}"></script>
  <script src="{{asset('js/jquery-ui.min.js')}}"></script>
  <script src="{{asset('js/jquery.mousewheel.min.js')}}"></script>

  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.1.13/jquery.mousewheel.min.js"></script>
  <script src="{{asset('js/jquery.mCustomScrollbar.concat.min.js')}}"></script>
  <script src="{{asset('js/script.js')}}"></script>
  <script src="js/mobilenav.js"></script>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="userId" content="{{ Session::has('sessionUser') ? App\User::whereEmail(Session::get('sessionUser'))->first()->id : null }}">
    <link rel="stylesheet" href="{{asset('css/form.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link rel="stylesheet" href="{{asset('css/mCustomScrollbar.concat.css')}}">
    <link rel="stylesheet" href="{{asset('css/innerstyle.css')}}">
    <link rel="stylesheet" href="{{asset('css/innerresponsive.css')}}">
    <link rel="stylesheet" href="{{asset('fonts/material-icon/css/material-design-iconic-font.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/jquery-ui.css')}}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <title>HT</title>
  </head>
  <style>
.rytheadsec {
  float: right !important;
    margin-top: -36px !important;
    margin-right: -270px;
    margin-left: 12px;
}
.main-nav {
    margin-top: 24px ;
    margin-left: 17px;
}
.createicon {
    float: left;
    line-height: 41px;
    margin-left: 0px;
    margin-top: 16px;
    color: #000;
    font-size: 15px;
}
  .links {
    padding-top: 10px;
    padding-bottom: 10px;
    line-height: 20px;
    padding-left: 29px;
    border-radius: 3px;
    text-transform: uppercase;
    padding-left: 15px;
    padding-right: 10px;
    color: white;
  }
  .links.active,.links:hover{
    background:#1b9bff;
    transition:.5s;
  }
  .link-logout{
    padding-top: 10px;
    padding-bottom: 10px;
    line-height: 20px;
    padding-left: 29px;
    border-radius: 3px;
    text-transform: uppercase;
    padding-left: 15px;
    padding-right: 10px;
  }
  .link-logout.active,.link-logout:hover{
    background-color: rgb(225, 73, 79);
    transition:.5s;
  }

  .link-email{
    padding-top: 10px;
    padding-bottom: 10px;
    line-height: 20px;
    padding-left: 29px;
    border-radius: 3px;
    text-transform: uppercase;
    padding-left: 15px;
    padding-right: 10px;
    color:white;
  }
  .link-email.active,.link-email:hover{
    background-color: rgb(32, 203, 100);
    transition:.5s;
  }
 
</style>
  <body>
    <div id="app">
    <?php
    use App\User;
    ?>

        <div class="innerheader">
          <div class="container-fluid">
              <div class="logo"><img src="{{asset('images/uploads/logo.png')}}" alt="logo"></div>
              <form action="{{url('/search-data')}}" method="post" style="margin-left: 244px;">
                {{csrf_field()}}
              <div class="searchfieldsec" style="margin-left: -160px;!important" >
                  <input type="text" name="text" class="searchfield" />
                  <input type="submit" name="" id="" value="Search" style="margin-top: -37px;
                  margin-left: 286px;
                  width: 98px;
                  border-radius: 34%;">
              </div>
              </form>
              @if(Session::get('sessionAdmin'))
              <ul class="main-nav" >
                <li><a href="{{url('/admin/view-spheres')}}">view Spheres</a></li>
                <li><a href="{{url('/admin/view-users')}}">My Users</a></li>
              </ul>
              @endif
              <div class="right-del">
                @if(!empty(Session::get('sessionUser')))
                <?php
                    $userCount=DB::table('users')->where(['email'=>Session::get('sessionUser')])->count();?>
                    @if($userCount!==0)
                    <?php
                    $user=DB::table('users')->where(['email'=>Session::get('sessionUser')])->first();                        
                  ?>
                  
                <div class="createicon" style="    margin-left: 127px;!important"><img src="{{asset('images/uploads/createicon.png')}}" alt=""><a href="{{url('/user/add-sphere/')}}">Create / Find Sphere</a> </div>
                <ul class="main-nav" >
                <li><a href="{{url('/user/my-spheres')}}">My Spheres</a></li>
                <li><a href="{{url('/user/my-tasks')}}">My Tasks</a></li>
                    <li><a href="/user/my-projects">My Projects</a></li>
                    <li><a href="/get-all-surveys-joined-it">My Surveys</a></li>
                </ul>
                <div class="rytheadsec">
                    <ul>
                        <li><img src="{{asset('images/uploads/msg.png')}}" alt="">Message</li>
                        <li class="drop">
                            <img src="images/uploads/taps.png" alt="" style="    border-radius: 50%;">Taps
                            <span class="caret"></span>
                            <ul class="sub_taps">
                                <li><a href="">Item 1</a></li>
                                <li><a href="">Item 2</a></li>
                                <li><a href="">Item 3</a></li>
                            </ul>
                        </li>


                      </ul>
                </div>

                    
                    
                <div class="rytheadsec ">
                  <ul style="margin-right: 282px !important;
                  margin-bottom: 35px;!important">
                    <div class="dropdown mr-5" style="margin-top: -25px;!important;">
                      <button class="main-nav dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        notifications
                      </button>
                      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                        <notifications />
                      </div>
                    </div>
                    </ul>
                  </div>
                  <div style="margin-top: -30px;!important;" >
                    <a href="{{url('user/view-profile/'.$user->email)}}">
                      <img  src="{{asset('/images/backend_images/user/small/'.$user->image)}}" alt="" style="    width: 4%;!important;
                      margin-left: 28rem;!important;
                      margin-top: -27px;!important;"></a></div>
                
                  <a class="burgermenu" onclick="openNav()">&#9776;</a>
            </div>
            @endif
            @endif
          </div>
      </div>
    @yield('content')
    <div class="scrollbar scrollbar-lady-lips">
      <div class="force-overflow"></div>
    </div>
      @if(Session::has('flash_message_error'))
        <div class="alert alert-danger alert-block">
            <strong>{!!session('flash_message_error')!!}</strong>
        </div>
    @endif
    @if(Session::has('flash_message_success'))
        <div class="alert alert-success alert-block">
            <strong>{!!session('flash_message_success')!!}</strong>
        </div>
    @endif
    </div>

 @yield('scripts')
  </body>
</html>
