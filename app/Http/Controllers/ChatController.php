<?php

namespace App\Http\Controllers;

use App\Events\ChatEvent;
use App\Events\MessageDelivered;
use App\Message;
use Illuminate\Http\Request;
use App\Events\TypeimgEvent;

use DB;
use Session;
class ChatController extends Controller
{

    public function typingEvent(){
        $userSession=Session::get('sessionUser');
        if($userSession){
        event(new TypeimgEvent());
        return response()->json([
          'status' => 200,
      ]);
      }else{
        return response()->json([
            'status'=>401,
            'message'=>'You have not authurization to access into this page'
        ]);
      }
    }
    public function send(Request $req){
        $userSession=Session::get('sessionUser');
        if($userSession){
        if($req->isMethod('post')){
            $user=DB::table('users')->where(['email'=>Session::get('sessionUser')])->first();
            event(new ChatEvent($req->message,$user));
        }else{
            $message='hello';
            $user=DB::table('users')->where(['email'=>Session::get('sessionUser')])->first();
            $this->saveToSession($req);
            event(new ChatEvent($message,$user));
        }

        }else{
            return response()->json([
                'status'=>401,
                'message'=>'You have not authurization to access into this page'
            ]);
        }
    }
    public function sendMessagesUserMember(Request $req){
        $userSession=Session::get('sessionUser');
        if($userSession){
        if($req->isMethod('post')){
        $message = Message::create([
            'user_id'=>$req->userId,'member_id'=>$req->memberId,'body'=>$req->message,'type'=>'Individual'
        ]);
        event(new MessageDelivered($message));
        return response()->json($message);
        }else{

            $message='hello';
            $user=DB::table('users')->where(['email'=>Session::get('sessionUser')])->first();
            $this->saveToSession($req);
            event(new ChatEvent($message,$user));
        }
    }else{
        return response()->json([
            'status'=>401,
            'message'=>'You have not authurization to access into this page'
        ]);
    }
    }
    public function sendMessagesUserSphere(Request $req){
        $userSession=Session::get('sessionUser');
        if($userSession){
        if($req->isMethod('post')){
         $user=DB::table('users')->where(['email'=>Session::get('sessionUser')])->first();       
         $message = Message::create([
            'user_id'=>$req->userId,'sphere_id'=>$req->sphereId,'body'=>$req->message,'type'=>'public'
        ]);
        event(new ChatEvent($message,$user));
        return response()->json($message);
        }else{
            $user=DB::table('users')->where(['email'=>Session::get('sessionUser')])->first();
            $this->saveToSession($req);
            event(new ChatEvent($message,$user));
        }
    }else{
        return response()->json([
            'status'=>401,
            'message'=>'You have not authurization to access into this page'
        ]);
    }
        
    }
    
    public function savePublicMessages(Request $req,$userId,$memberId,$message){
        $userSession=Session::get('sessionUser');
        if($userSession){
            if($req->isMethod('post')){
                $msgSave=new Message();
                $msgSave->user_id=$userId;
                $msgSave->member_id=0;
                $msgSave->body=$message;
                $msgSave->type='public';
            return  $msgSave->save();
            }
        }else{
            return response()->json([
                'status'=>401,
                'message'=>'You have not authurization to access into this page'
            ]);
        }
    }
    public function savePrivateMessages(Request $req,$userId,$memberId,$message){
        $userSession=Session::get('sessionUser');
        if($userSession){
            if($req->isMethod('post')){
                $msgSave=new Message();
                $msgSave->user_id=$userId;
                $msgSave->member_id=$memberId;
                $msgSave->body=$message;
                $msgSave->type='invidual';
            return  $msgSave->save();
            }
        }else{
            return response()->json([
                'status'=>401,
                'message'=>'You have not authurization to access into this page'
            ]);
        }
    }

public function getAllsphereMessages($sphereId){
    $userSession=Session::get('sessionUser');
    if($userSession){
        $messagesSphere=Message::where(['sphere_id'=>$sphereId,'type'=>'public'])->with('user')->get();
    
        return response()->json([
            'status' => 200,
            'message' =>  $messagesSphere
        ]);

}else{
    return response()->json([
    'status'=>401,
    'message'=>'You have not authurization to access into this page'
]);
}
}
    public function getAllPrivateMessagesUserMember($userId,$memberId){
        $userSession=Session::get('sessionUser');
        if($userSession){
                $messagesUserMember=Message::where(['user_id' => $userId, 'member_id' => $memberId])
                ->orWhere('user_id' , $memberId)->where('member_id',  $userId)->get();
            
                return response()->json([
                    'status' => 200,
                    'message' =>  $messagesUserMember
                ]);

    }else{
    return response()->json([
    'status'=>401,
    'message'=>'You have not authurization to access into this page'
    ]);
    }
}

    public function getAllPrivateMessagesMemberUser($memberId,$userId){
        $userSession=Session::get('sessionUser');
        if($userSession){
                $user=DB::table('users')->where(['email'=>Session::get('sessionUser')])->first();
                if($user->id==$userId){//if this user same session user
                $memberCount=DB::table('users')->where(['id'=>$memberId])->count();
                    if($memberCount!==0){//if member exsit in db

                        if($userId!==$memberId){//if user and member not same
                $messagesMemberUser=DB::table('messages')->where(['user_id'=>$userId,'member_id'=>$memberId])->get();

                return response()->json([
                    'status' => 200,
                    'message' =>  $messagesMemberUser
                ]);
            }else{
                return response()->json([
                    'status'=>404,
                    'message'=>'Not Found'
                    ]);
                }
            }else{
                return response()->json([
                    'status'=>404,
                    'message'=>'Not Found'
                    ]);

                }
            }else{
                return response()->json([
                    'status'=>404,
                    'message'=>'Not Found'
                    ]);

            }

}else{
    return response()->json([
    'status'=>401,
    'message'=>'You have not authurization to access into this page'
    ]);
}
}
    

    public function deleteMessagesSphere($sphereId){
        $userSession=Session::get('sessionUser');
        if($userSession){
          if($sphereId){
      $messageSphere=  DB::table('messages')->where(['sphere_id'=>$sphereId])->first();
        if(!empty($messageSphere)){
             DB::table('messages')->where(['sphere_id'=>$sphereId])->delete();
        return response()->json([
            'status' => 200,
            'message' => 'deleted successfully'
        ]);
        }else{
            return response()->json([
                'status'=>404,
                'message'=>'Data in this route is empty'
                ]);
        }
    }else{
        return response()->json([
        'status'=>404,
        'message'=>'This rout not found'
        ]);
        }
    }else{
        return response()->json([
        'status'=>401,
        'message'=>'You have not authurization to access into this page'
        ]);
    }
     
    }

   
}
