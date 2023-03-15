<?php

namespace App\Console\Commands\TelegramBot;

use Telegram\Bot\Commands\Command;
use Telegram\Bot\FileUpload\InputFile; 
use Illuminate\Database\Eloquent\Builder;
use App\Pelanggan;
use App\Pegawai;
use App\Pengangkutan;
use App\TelegramChatLog;

class pengangkutanCommand extends Command
{
   /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'pengangkutan';

    /**
    * @var array Command Aliases
    */
   protected $aliases = ['pengangkutanHelp'];

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'command untuk memperlihatkan request pengangkutan pending';

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
            
            $pengangkutan = Pengangkutan::where('id_pelanggan', $pelanggan->id)->where('status', "pending")->doesntHave('pembayaran')->get();

            if($pengangkutan->isNotEmpty()){
                foreach($pengangkutan as $p){
                    $data = "Alamat : ".$p->alamat."\n"."Nominal : "."Rp".number_format($r->nominal ?? 0,2,',','.')."\nStatus : ".$p->status."\n\nLokasi\n\n".$this->google.$p->lat.','.$p->lng;
                    $this->replyWithMessage([
                        'text' => $data,
                        'chat_id' => $chat_id
                    ]);
                }
            }else{
                $reply = "Tidak terdapat Request Pengangkutan Sampah !";
                $this->replyWithMessage([
                    'text' => $reply,
                    'chat_id' => $chat_id
                ]);
            }

        }elseif(isset($pegawai)){
            $chat->pengguna_type = "App\Pegawai";
            $chat->pengguna_id = $pegawai->id_pegawai;
            $chat->save();

            $pengangkutan = Pengangkutan::where('id_desa_adat', $pegawai->id_desa_adat)->with('pelanggan')->where('status', "Selesai")->doesnthave('pembayaran')->get();
            
            if($pengangkutan->isNotEmpty()){
                foreach($pengangkutan as $p){
                    $data = "Alamat : ".$p->alamat."\nPelanggan: ".$p->pelanggan->kependudukan->nama."\nNominal : "."Rp".number_format($p->nominal ?? 0,2,',','.')."\nStatus : Belum Terbayar"."\n\nLokasi\n\n".$this->google.$p->lat.','.$p->lng;
                    $this->replyWithMessage([
                        'text' => $data,
                        'chat_id' => $chat_id
                    ]);
                }
            }else{
                $reply = "Tidak terdapat Request Pengangkutan Sampah !";
                $this->replyWithMessage([
                    'text' => $reply,
                    'chat_id' => $chat_id
                ]);
            }

        }else{
            $chat->save();

            $text = "Anda belum terdaftar dalam telegram bot !\nuntuk melihat informasi pendaftaran gunakan perintah /daftar";
            $this->replyWithMessage(compact('text'));
        }
    }
}
