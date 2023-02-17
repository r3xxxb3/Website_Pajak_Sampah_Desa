<?php

namespace App\Console\Commands\TelegramBot;

use Telegram\Bot\Commands\Command;
use App\Kependudukan;
use App\Pelanggan;
use App\Pegawai;
use App\TelegramChatLog;


class jadwalCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'jadwal';

    /**
    * @var array Command Aliases
    */
   protected $aliases = ['jadwalHelp'];

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'command untuk melihat jadwal pengangkutan sampah';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $updates = $this->getUpdate();
        $chat_id = $updates->message->chat->id;
        $chat = new TelegramChatLog;
        $chat->message = $updates->message->text;
        $chat->chat_id = $updates->message->chat->id;

        $pegawai = Pegawai::where('chat_id', $updates->message->chat->id)->first();
        $pelanggan = Pelanggan::where('chat_id', $updates->message->chat->id)->first();


        if(isset($pelanggan)){
            $chat->pengguna_type = "App\Pelanggan";
            $chat->pengguna_id = $pelanggan->id;
            $chat->save();

            $text = " ";
            $this->replyWithMessage(compact('text'));
        }elseif(isset($pegawai)){
            $chat->pengguna_type = "App\Pegawai";
            $chat->pengguna_id = $pegawai->id_pegawai;
            $chat->save();

            $text = " ";
            $this->replyWithMessage(compact('text'));
        }else{
            $chat->save();

            $text = " ";
            $this->replyWithMessage(compact('text'));
        }
        
    }
}
