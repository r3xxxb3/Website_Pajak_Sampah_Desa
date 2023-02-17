<?php

namespace App\Console\Commands\TelegramBot;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use App\Pelanggan;
use App\Pegawai;
use App\TelegramChatLog;


class startCommand extends Command
{ 
    /**
    * @var string Command Name
    */
   protected $name = 'start';

   /**
    * @var array Command Aliases
    */
   protected $aliases = ['startHelp'];

   /**
    * @var string Command Description
    */
   protected $description = 'Help command, Get a list of commands';

   /**
    * {@inheritdoc}
    */
   public function handle()
   {   
       $updates = $this->getUpdate();
       $name = $updates->message->from->first_name;
       
       $telegramchat = new TelegramChatLog;
       $pelanggan = Pelanggan::where('chat_id', $updates->message->chat->id)->first();
       $pegawai = Pegawai::where('chat_id', $updates->message->chat->id)->first();

       if(isset($pegawai)){
        $text = "Halo ".$name." ! \nBerikut ini daftar perintah yang dapat anda gunakan ! \n1. Cek Jadwal Pengangkutan Sampah /jadwal \n2. Cek Retribusi Pending /retribusi \n3. Cek Request Pengangkutan Sampah \n4. Cek Pembayaran ";
        $telegramchat->pengguna_id = $pegawai->id;
        $telegramchat->pengguna_type = "App\\Pegawai";
       }elseif(isset($pelanggan)){
        $text = "Halo ".$name." ! \nBerikut ini daftar perintah yang dapat anda gunakan ! \n1. Cek Jadwal Pengangkutan Sampah /jadwal \n2. Cek Retribusi Pending /retribusi \n3. Cek Request Pengangkutan Sampah \n4. Cek Status Pembayaran";
        $telegramchat->pengguna_id = $pegawai->id;
        $telegramchat->pengguna_type = "App\\Pelanggan";
       }else{
        $text = "Halo ".$name." !\n Anda belum terdaftar ke dalam sistem !\nUntuk melihat informasi pendaftaran gunakan perintah /daftar";
       }

       $telegramchat->chat_id = $updates->message->chat->id;
       $telegramchat->message = $updates->message->text;

        $this->replyWithMessage(compact('text'));
        $telegramchat->save();

   }
}
