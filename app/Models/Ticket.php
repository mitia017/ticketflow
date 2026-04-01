<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Ticket extends Model
{
    use HasFactory;
    protected $fillable = ['title','description','status','priority','created_by','assigned_to','closed_at'];
    protected $casts = ['closed_at'=>'datetime'];
    public function creator(){return $this->belongsTo(User::class,'created_by');}
    public function assignee(){return $this->belongsTo(User::class,'assigned_to');}
    public function comments(){return $this->hasMany(Comment::class)->latest();}
}
