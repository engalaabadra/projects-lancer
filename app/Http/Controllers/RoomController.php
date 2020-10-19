<?php

namespace App\Http\Controllers;
use App\Events\MessageDelivered;
use App\User;
use App\Message;
use Illuminate\Http\Request;
use DB;
use Session;
class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($room_id)
    {
        if(!empty($room_id)){
            $messagesUsers=  DB::table('messages')->where(['room_id'=>$room_id])->get();
            return view('rooms.rooms')->with(compact('messagesUsers'));
        }else{
            return redirect('/create-chat/');
        }
        
    }
//all chats(rooms) that this user speak with others in it
    public function roomsUser($userId)
    {
        $roomsUsers=  DB::table('rooms_members')->where(['member_id'=>$userId])->get();
        return view('rooms.rooms_user')->with(compact('roomsUsers'));
    }
    public function room($room_id){
        $user=DB::table('users')->where(['email'=>Session::get('sessionUser')])->first();
        $userId=$user->id;
        $countJoinAcceptedMember=  DB::table('rooms_members')->where(['room_id'=>$room_id,'member_id'=>$userId,'status_joining_in_room'=>'accepted_status_joining'])->count();
        $countJoinPendingMember=  DB::table('rooms_members')->where(['room_id'=>$room_id,'member_id'=>$userId,'status_joining_in_room'=>'pending_status_joining'])->count();
        if($countJoinAcceptedMember!==0){
            $room= DB::table('rooms')->where(['id'=>$room_id])->first();
            return view('rooms.roomMember')->with(compact('room'));
        }elseif($countJoinPendingMember!==0){
            return redirect()->back()->with('flash_message_error','You Can not access here ,Please wait accepting on nyour joining in this room');
        }else{
            $join='<a href="{{url("/join-room/".$room_id)}}">join</a>';
            return redirect()->back()->with('flash_message_error','You Can not access here ,Please send request to join in it'.$join);
        }
    }
    public function roomsPublic($public_type){
        $roomsPublic=DB::table('rooms')->where(['type'=>'public'])->get();
        return view('rooms.rooms_public')->with(compact('roomsPublic'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $req, $user_id)
    {
        $userId=$user_id;
        //
        if($req->isMethod('post')){
            $data=$req->all();

            $room=DB::table('rooms')->insert(['leader_id'=>$user_id,'type'=>'public','name'=>$data['name']]);
        }
        return view('rooms.create_room')->with(compact('userId'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
         $message=new Message();
         $message->body=$req->chatText;
         $message->room_id=$req->roomId;
         $message->user_id=$req->userId;
         $message->type=$req->type_chat;
         $message->save();    
         broadcast(new MessageDelivered($message))->toOthers();//to listen the others my message 
        // broadcast(new MessageDelivered($message->load('user')))->toOthers();//load fun -> to load user from model file
         return $req->all();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($user_id,$member_id)
    {
        $user=DB::table('users')->where(['email'=>Session::get('sessionUser')])->first();
        if($user->id==$user_id){//if this user same session user
        $memberCount=DB::table('users')->where(['id'=>$member_id])->count();
            if($memberCount!==0){//if member exsit in db

                if($user_id!==$member_id){//if user and member not same
                    $member=DB::table('users')->where(['id'=>$member_id])->first();
                    $memberName=$member->username;
                  
                   return view('messages.messages_two_members')->with(compact('member_id','user_id','memberName'));
                }else{
                return abort(404);
        
                }
            }else{
                return abort(404);
        
                }
            }else{
                return abort(404);
        
                }
            
    }
    public function showPublicRoom($room_id){
        $roomId=$room_id;
        $user=DB::table('users')->where(['email'=>Session::get('sessionUser')])->first();
        $userId=$user->id;
        $roomPublic=DB::table('rooms')->where(['id'=>$room_id,'type'=>'Public'])->first();
        $messagesRoomPublic=DB::table('messages')->where(['room_id'=>$roomPublic->id])->get();
        return view('rooms.room_public')->with(compact('roomPublic','messagesRoomPublic','roomId','userId'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}