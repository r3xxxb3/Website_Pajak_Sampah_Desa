<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TelegramChatLog extends Model
{
    //
    protected $table = "tb_telegram_message_log";
    protected $timestamp = true;
    protected $fillable = ["id_pelanggan", "chat_id", "message", "context", 'step', 'is_confirmed'];

}
