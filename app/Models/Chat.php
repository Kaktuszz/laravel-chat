<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable =[
        'id',
        'participant_1',
        'participant_2',
    ];

    public function messages()
    {
        return $this->hasMany(Message::class, 'chat_room_id');
    }

    public function participantOne()
    {
        return $this->belongsTo(User::class, 'participant_1');
    }

    public function participantTwo()
    {
        return $this->belongsTo(User::class, 'participant_2');
    }
}
