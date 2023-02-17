<?php

namespace App\Console\Commands\TelegramBot;

use Telegram\Bot\Commands\Command;
use App\Kependudukan;
use App\Pelanggan;
use App\Pegawai;
use App\TelegramChatLog;


class daftarCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'daftar';

    /**
    * @var array Command Aliases
    */
   protected $aliases = ['daftarHelp'];

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'command untuk mendaftarkan pelanggan';

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

            $text = "Halo ".$updates->message->chat->first_name." ! \nAnda telah terdaftar ke dalam sistem sebagai pelanggan !\nBerikut merupakan command list yang dapat anda gunakan : ";
            $this->replyWithMessage(compact('text'));
        }elseif(isset($pegawai)){
            $chat->pengguna_type = "App\Pegawai";
            $chat->pengguna_id = $pegawai->id_pegawai;
            $chat->save();

            $text = "Halo ".$updates->message->chat->first_name." ! \nAnda telah terdaftar ke dalam sistem sebagai pegawai !\nBerikut merupakan command list yang dapat anda gunakan : \n1. /jadwal -> cek jadwal pengangkutan sampah\n2. /retribusi -> cek retribusi pending\n3. /request -> cek request pengangkutan sampah\n4. /pembayaran -> cek pembayaran pending";
            $this->replyWithMessage(compact('text'));
        }else{
            $chat->save();

            $text = "Halo ".$updates->message->chat->first_name." ! \nUntuk melakukan pendaftaran gunakan format daftar-nik-username untuk mengirimkan pesan !";
            $this->replyWithMessage(compact('text'));
        }
        
    }
}
