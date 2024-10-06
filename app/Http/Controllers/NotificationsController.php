<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use Session;
class NotificationsController extends Controller
{

    public function getAllNotifications(){
        $user=User::where('email',Session::get('sessionUser'))->first();
        return [
            'read'      => $user->readNotifications,
            'unread'    => $user->unreadNotifications,
            'all'       => $user->notifications,
        ];
        
    }

    // public function getMarkAsReadNotifications(Request $req){
    //     $user=User::where('email',Session::get('sessionUser'))->first();
    //     return $user->notifications->where(['id'=>$req->id])->markAsRead();
    // }

    public function getMarkAsReadNotificationsAndRedirect($id){
        $user=User::where('email',Session::get('sessionUser'))->first();
        $notification= $user->notifications->where('id',$id)->first();
        $notification->markAsRead();
            if($notification->type='App\Notifications\NewCommentForOwnerNotify'){
                return 'notificationForNewComment';
            }elseif($notification->type='App\Notifications\NewMentionForUserNotify'){
                return 'notificationForNewMention';
            }else {
                return redirect()->back();

            }
    }

}
