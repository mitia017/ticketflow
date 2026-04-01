<?php
namespace App\Events;
use App\Models\Ticket;
use App\Models\Comment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
class CommentAdded implements ShouldBroadcast
{
    public $ticket,$comment;
    public function __construct(Ticket $ticket, Comment $comment){$this->ticket=$ticket;$this->comment=$comment;}
    public function broadcastOn(){return new Channel('ticket.'.$this->ticket->id);}
    public function broadcastWith(){return ['comment'=>['id'=>$this->comment->id,'content'=>$this->comment->content,'user'=>['id'=>$this->comment->user->id,'name'=>$this->comment->user->name],'created_at'=>$this->comment->created_at->toISOString()],'ticket_id'=>$this->ticket->id];}
}
