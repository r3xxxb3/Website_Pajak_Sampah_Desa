<?php

namespace App\Console\Commands\TelegramBot;

use Telegram\Bot\Commands\Command;
use Telegram\Bot\FileUpload\InputFile; 
use Illuminate\Database\Eloquent\Builder;
use App\Pelanggan;
use App\Pegawai;
use App\Properti;
use App\Retribusi;
use App\Pengangkutan;
use App\Pembayaran;
use App\TelegramChatLog;

class pengumumanCommand extends Command
{
   /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'pengumuman';

    /**
    * @var array Command Aliases
    */
   protected $aliases = ['pengumumanHelp'];

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'command untuk melakukan pengumuman';

    public function __construct(){
        $this->url = "https://c291-114-5-36-99.ap.ngrok.io/";
        $this->google = "https://www.google.com/maps/search/?api=1&query=";
    }

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
            $reply = "Pelanggan tidak dapat menggunakan Perintah ini !";

            $this->replyWithMessage([
                'text' => $reply,
                'chat_id' => $chat_id
            ]);

        }elseif(isset($pegawai)){
            $chat->pengguna_type = "App\Pegawai";
            $chat->pengguna_id = $pegawai->id_pegawai;
            $chat->save();
            $desa = $pegawai->id_desa_adat;
            $reply = "Untuk membuat pengumuman gunakan perintah berikut" ;

            $this->replyWithMessage([
                'text' => $reply,
                'chat_id' => $chat_id
            ]);

            $reply = "pengumuman-(isi pengumuman)" ;

            $this->replyWithMessage([
                'text' => $reply,
                'chat_id' => $chat_id
            ]);

        }else{
            $chat->save();

            $text = "Anda belum terdaftar dalam telegram bot !\nuntuk melihat informasi pendaftaran gunakan perintah /daftar";
            $this->replyWithMessage(compact('text'));
        }
    }
}
