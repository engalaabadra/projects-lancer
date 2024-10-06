<?php

namespace App\Http\Controllers;
use DB;
use App\Sphere;
use App\Event;
use App\User;
use Session;
use Carbon\Carbon;

use Illuminate\Http\Request;

class EventsController extends Controller
{
  public function getAllEvents($sphere_id=null){
    $userSession=Session::get('sessionUser');
    if($userSession){
      if($sphere_id){
        $user=User::where(['email'=>Session::get('sessionUser')])->first();
        $userId=$user->id;
        $sphereId=$sphere_id;
        $founderSphere=DB::table('spheres')->where(['founder_id'=>$userId,'id'=>$sphereId])->count();
        $userJoinedRequestSphere=DB::table('sphere_users')->where(['user_id'=>$userId,'sphere_id'=>$sphereId,'request_joining_status'=>'accepted_status_request_joining'])->count();
        $userJoinedInvitationSphere=DB::table('sphere_users')->where(['user_id'=>$userId,'sphere_id'=>$sphereId,'invitation_status'=>'accepted_inivitation_status'])->count();
          if($founderSphere==0&&$userJoinedRequestSphere==0&&$userJoinedInvitationSphere==0){
        return view('spheres.go_into_sphere')->with(compact('userId','sphereId'));
        }else{
        $sphereId=$sphere_id;
        $sphereCount=Sphere::where(['id'=>$sphere_id])->count();
        if($sphereCount!==0){
          $sphere=Sphere::where(['id'=>$sphere_id])->first();
        $sphereImage=$sphere->image;
        $sphereCoverImage=$sphere->cover_image;
        }
        $eventCount= Event::where(['sphere_id'=>$sphere_id])->count();
        if($eventCount!==0){
          $event= Event::where(['sphere_id'=>$sphere_id])->first();
          $eventName=$event->title;
          $events= Event::where(['sphere_id'=>$sphere_id])->get();
        }
        
        return view('events.get_all_events')->with(compact('sphereCount','eventCount','sphere','sphereCoverImage','sphereImage','eventName','events','sphereId'));
        }
      }else{
        return abort(404);    
      }
    }else{
    return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');    
    }
  }

  public function scheduleEventUpcoming(Request $req,$sphere_id=null){
    $userSession=Session::get('sessionUser');
    if($userSession){
      if($sphere_id){
        $user=User::where(['email'=>Session::get('sessionUser')])->first();
        $userId=$user->id;
        $sphereId=$sphere_id;
        $founderSphere=DB::table('spheres')->where(['founder_id'=>$userId,'id'=>$sphereId])->count();
        $userJoinedRequestSphere=DB::table('sphere_users')->where(['user_id'=>$userId,'sphere_id'=>$sphereId,'request_joining_status'=>'accepted_status_request_joining'])->count();
        $userJoinedInvitationSphere=DB::table('sphere_users')->where(['user_id'=>$userId,'sphere_id'=>$sphereId,'invitation_status'=>'accepted_inivitation_status'])->count();
          if($founderSphere==0&&$userJoinedRequestSphere==0&&$userJoinedInvitationSphere==0){
        return view('spheres.go_into_sphere')->with(compact('userId','sphereId'));
        }else{
        $user=User::where(['email'=>Session::get('sessionUser')])->first();
        $userId=$user->id;
        $sphereId=$sphere_id;
        if($req->isMethod('post')){
          $data=$req->all();
          // $carbon=Carbon::now();
          $todayDate = date('m/d/Y');
          $validateData = $req->validate([
              'event_time' => 'after_or_equal:'.$todayDate
              // 'event_time' => 'date_format:m/d/Y'
      
          ]);
            $newEvent=new Event();
            $newEvent->event_time=$data['event_time'];
            $newEvent->sphere_id=$sphere_id;
            $newEvent->user_id=$userId;
            $newEvent->title=$data['title_event'];
            $newEvent->description=$data['description_event'];
            $newEvent->save();
        return redirect('sphere/'.$sphere_id.'/events');

        }
        return view('events.add_event_upcoming')->with(compact('sphereId'));
        }
      }else{
        return abort(404);    
      }
      }else{
      return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');    
      }
    }


  public function scheduleEventPrevious(Request $req,$sphere_id=null){
    $userSession=Session::get('sessionUser');
    if($userSession){
      if($sphere_id){
          $user=User::where(['email'=>Session::get('sessionUser')])->first();
          $userId=$user->id;
          $sphereId=$sphere_id;
          $founderSphere=DB::table('spheres')->where(['founder_id'=>$userId,'id'=>$sphereId])->count();
          $userJoinedRequestSphere=DB::table('sphere_users')->where(['user_id'=>$userId,'sphere_id'=>$sphereId,'request_joining_status'=>'accepted_status_request_joining'])->count();
          $userJoinedInvitationSphere=DB::table('sphere_users')->where(['user_id'=>$userId,'sphere_id'=>$sphereId,'invitation_status'=>'accepted_inivitation_status'])->count();
            if($founderSphere==0&&$userJoinedRequestSphere==0&&$userJoinedInvitationSphere==0){
          return view('spheres.go_into_sphere')->with(compact('userId','sphereId'));
          }else{
          $user=User::where(['email'=>Session::get('sessionUser')])->first();
          $userId=$user->id;
          $sphereId=$sphere_id;
          if($req->isMethod('post')){
            $data=$req->all();
            // $carbon=Carbon::now();
            $todayDate = date('m/d/Y');
            $validateData = $req->validate([
              // 'event_time' => 'date_format:m/d/Y|after_or_equal:'.$todayDate
              'event_time' => 'before_or_equal:'.$todayDate

          ]);
              $newEvent=new Event();
              $newEvent->event_time=$data['event_time'];
              $newEvent->sphere_id=$sphere_id;
              $newEvent->user_id=$userId;
              $newEvent->title=$data['title_event'];
              $newEvent->description=$data['description_event'];
              $newEvent->save();
              return redirect('sphere/'.$sphere_id.'/events');

          }
          return view('events.add_event_previous')->with(compact('sphereId'));
        }
      }else{
        return abort(404);    
      }
      }else{
      return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');    
      }
  }

  public function viewDetailsEvent($sphere_id,$event_id){
    $userSession=Session::get('sessionUser');
    if($userSession){
      if($event_id&&$sphere_id){
          $user=User::where(['email'=>Session::get('sessionUser')])->first();
          $userId=$user->id;
          $sphereId=$sphere_id;
          $founderSphere=DB::table('spheres')->where(['founder_id'=>$userId,'id'=>$sphereId])->count();
          $userJoinedRequestSphere=DB::table('sphere_users')->where(['user_id'=>$userId,'sphere_id'=>$sphereId,'request_joining_status'=>'accepted_status_request_joining'])->count();
          $userJoinedInvitationSphere=DB::table('sphere_users')->where(['user_id'=>$userId,'sphere_id'=>$sphereId,'invitation_status'=>'accepted_inivitation_status'])->count();
            if($founderSphere==0&&$userJoinedRequestSphere==0&&$userJoinedInvitationSphere==0){
          return view('spheres.go_into_sphere')->with(compact('userId','sphereId'));
          }else{
          $sphere=DB::table('votes')->where(['sphere_id'=>$sphere_id])->get();
          $sphereThisEvents=Sphere::where(['id'=>$sphere_id])->first();
          $nameSphereThisEvents  = $sphereThisEvents->name;
          $user=User::where(['email'=>Session::get('sessionUser')])->first();
          $userId=$user->id;
          $eventId=$event_id;
          $sphereId=$sphere_id;
          $eventFoundedByUserCount=DB::table('events')->where(['id'=>$event_id,'sphere_id'=>$sphere_id,'user_id'=>$userId])->count();
          $eventAcceptedInvitationUserCount=DB::table('events_users')->where(['event_id'=>$event_id,'sphere_id'=>$sphere_id,'user_id'=>$userId,'invitation_status'=>'accepted_inivitation_status'])->count();
          $eventAcceptedRequestUserCount=DB::table('events_users')->where(['event_id'=>$event_id,'sphere_id'=>$sphere_id,'user_id'=>$userId,'request_joining_status'=>'accepted_status_request_joining'])->count();
          $eventPendingInvitationUserCount=DB::table('events_users')->where(['event_id'=>$event_id,'sphere_id'=>$sphere_id,'user_id'=>$userId,'invitation_status'=>'status_pending'])->count();
          $eventPendingRequestUserCount=DB::table('events_users')->where(['event_id'=>$event_id,'sphere_id'=>$sphere_id,'user_id'=>$userId,'request_joining_status'=>'accepted_status_request_joining'])->count();
          $event=Event::where(['id'=>$eventId])->first();
          $eventPendingRequestJoinUserCount=DB::table('events_users')->where(['event_id'=>$eventId,'sphere_id'=>$sphereId,'user_id'=>$userId,'request_joining_status'=>'pending_status_request_joining'])->count();
          $eventAcceptedRequestJoinUserCount=DB::table('events_users')->where(['event_id'=>$eventId,'sphere_id'=>$sphereId,'user_id'=>$userId,'request_joining_status'=>'accepted_status_request_joining'])->count();
          if($eventFoundedByUserCount!==0){
            $event=Event::where(['id'=>$event_id,'sphere_id'=>$sphere_id])->first();
            $eventCount=Event::where(['id'=>$event_id,'sphere_id'=>$sphere_id])->count();
            return view('events.view_details_event')->with(compact('event','eventCount','sphere_id','userId'));
          }elseif($eventAcceptedInvitationUserCount!==0){
            $event=Event::where(['id'=>$event_id,'sphere_id'=>$sphere_id])->first();
            $eventCount=Event::where(['id'=>$event_id,'sphere_id'=>$sphere_id])->count();
            return view('events.view_details_event')->with(compact('event','eventCount','sphere_id','userId'));
          }elseif($eventAcceptedRequestUserCount!==0){
            $event=Event::where(['id'=>$event_id,'sphere_id'=>$sphere_id])->first();
            $eventCount=Event::where(['id'=>$event_id,'sphere_id'=>$sphere_id])->count();
            return view('events.view_details_event')->with(compact('event','eventCount','sphere_id','userId'));
          }elseif($eventPendingInvitationUserCount!==0){
            return redirect('/user/view-profile/'.$user->email)->with('flash_message_error','Please, go into your invitations to accept on invitation to this event to enter in it ');
          }elseif($eventPendingRequestUserCount!==0){
            return redirect('/user/view-profile/'.$user->email)->with('flash_message_error','Please, go into your invitations to accept on invitation to this event to enter in it ');
          }else{
            return view('user.join_into_specific_event')->with(compact('userId','eventId','sphereId','event','eventPendingRequestJoinUserCount','eventAcceptedRequestJoinUserCount'));
          }
          $event=Event::where(['id'=>$event_id])->first();
          
          return view('events.view_details_event')->with(compact('userId','event_id','sphereThisEvents','event','sphere_id'));
          }

      }else{
          return abort(404);    
      }
      }else{
      return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');    
      }
  }
}
