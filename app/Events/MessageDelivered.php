<?php

namespace App\Events;

use App\Message;
use App\Room;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageDelivered implements ShouldBroadcastNow //when listen an event will can make broadcast for channel name it -> chat-group
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $message;
    
    
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message)
    {
       
        $this->message=$message;
        
        
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('AppRoom.'.$this->message->member_id);


    //  $messageD=   DB::table('messages')->where(['id'=>$msg->id])->first();
        // $roomId=$messageD->room_id;
        // return new PrivateChannel('channel-name');
        // return new Channel('chanel-group');
    }

    
}

