<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use DB;
class NewMentionForUserNotify extends Notification implements ShouldQueue,ShouldBroadcast
{
    use Queueable;
    protected $mention;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($mention)
    {
        $this->mention=$mention;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
   {//notifiable: this user that will recieve the email
    $arr=['database','broadcast'];
    //if this user recived the noti , will we send email
    // if($notifiable->receive_email==1){
        $arr[]='mail';
    // }
    return $arr;
}

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $userMention=DB::table('users')->where(['id'=>$this->mention->user_id])->first();
        $commentUserMention=DB::table('comments')->where(['id'=>$this->mention->comment_id])->first();
        $postUserMention=DB::table('posts')->where(['id'=>$this->mention->post_id])->first();
        
        return (new MailMessage)
                    ->line('There is new mention from.'.$userMention->username.'in comment'.$commentUserMention->body.'on post'.$postUserMention->body)
                    // ->action('Notification Action', route('user/details-post-dashboard/',$postUserMention->id))

                    ->line('Thank you for using HT website!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        $userMention=DB::table('users')->where(['id'=>$this->mention->user_id])->first();
        $commentUserMention=DB::table('comments')->where(['id'=>$this->mention->comment_id])->first();
        $postUserMention=DB::table('posts')->where(['id'=>$this->mention->post_id])->first();
        return [
            'email'=>$userMention->email,
            'comment'=>$commentUserMention->body,
            'post'=>$postUserMention->body,
            'post_id'=>$postUserMention->id,
            'type_notification'=>'mentionUser',
            'created_at'=>$this->mention->created_at->format('d M, Y h:i a')
        ];
    }
    public function toBroadcast($notifiable)
    {
        $userMention=DB::table('users')->where(['id'=>$this->mention->user_id])->first();
        $commentUserMention=DB::table('comments')->where(['id'=>$this->mention->comment_id])->first();
        $postUserMention=DB::table('posts')->where(['id'=>$this->mention->post_id])->first();
        return [
            'email'=>$userMention->email,
            'comment'=>$commentUserMention->body,
            'post'=>$postUserMention->body,
            'created_at'=>$this->mention->created_at->format('d M, Y h:i a')
        ];
    }
}
