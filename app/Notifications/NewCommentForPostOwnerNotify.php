<?php

namespace App\Notifications;
use DB;
use Session;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
class NewCommentForPostOwnerNotify extends Notification implements ShouldQueue,ShouldBroadcast
{
    use Queueable;
    protected $comment;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($comment)
    {
        $this->comment=$comment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)//notifiable->post this user
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
        $userComment=DB::table('users')->where(['id'=>$this->comment->user_id])->first();
        $postComment=DB::table('posts')->where(['id'=>$this->comment->post_id])->first();
        return (new MailMessage)
                    ->line('There is new comment from.'.$userComment->username.'on your post'.$postComment->body)
                    // ->action('Notification Action', route('user/details-post-dashboard/',$postComment->id))
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
        $userComment=DB::table('users')->where(['id'=>$this->comment->user_id])->first();
        $postComment=DB::table('posts')->where(['id'=>$this->comment->post_id])->first();
        return [
            'comment'=>$this->comment->body,
            'email'=>$userComment->email,
            'post_id'=>$this->comment->post_id,
            'post_title'=>$postComment->body,
            'type_notification'=>'commentUser',
            'created_at'=>$this->comment->created_at->format('d M, Y h:i a')
        ];
    }

    public function toBroadcast($notifiable)
    {
        $userComment=DB::table('users')->where(['id'=>$this->comment->user_id])->first();
        $postComment=DB::table('posts')->where(['id'=>$this->comment->post_id])->first();
        return new BroadcastMessage ([
            'data'=>[
            'comment'=>$this->comment->body,
            'email'=>$userComment->email,
            'post_id'=>$this->comment->post_id,
            'post_title'=>$postComment->body,
            'type_notification'=>'commentUser',
            'created_at'=>$this->comment->created_at->format('d M, Y h:i a')
            ]
            
        ]);
    }
}
