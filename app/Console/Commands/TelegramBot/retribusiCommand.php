<?php

namespace App\Console\Commands\TelegramBot;

use Telegram\Bot\Commands\Command;
use Telegram\Bot\FileUpload\InputFile; 
use Illuminate\Database\Eloquent\Builder;
use App\Pelanggan;
use App\Pegawai;
use App\Properti;
use App\Retribusi;
use App\TelegramChatLog;

class retribusiCommand extends Command
{
   /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'retribusi';

    /**
    * @var array Command Aliases
    */
   protected $aliases = ['retribusiHelp'];

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'command untuk melihat data retribusi yang masih pending';

    public function __construct(){
        return $url = "https://e36a-114-5-36-99.ap.ngrok.io/";
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
            
            $retribusi = Retribusi::where('id_pelanggan', $pelanggan->id)->where('status', "pending")->get();
            $reply = null;
            // dd($retribusi);
            if($retribusi->isNotEmpty()){
                foreach($retribusi as $r){
                    // $url = str_replace(" ", "%20",$r->properti->file);
                    // $image = "https://7eb5-114-5-111-25.ap.ngrok.io/assets/img/properti/".$url;
                    // dd(new InputFile($image, $r->properti->nama_properti));
                    $data =  $r->properti->nama_properti.")\nTanggal Retribusi : ".$r->created_at->format('d M Y')."\nNominal : "."Rp".number_format($r->nominal ?? 0,2,',','.')."\n\nLokasi\n\n"."https://www.google.com/maps/search/?api=1&query=".$r->properti->lat.",".$r->properti->lng;
                    // $this->replyWithPhoto([
                    //     'photo' => new InputFile($image, $r->properti->nama_properti),
                    //     'caption' => $reply,
                    //     'chat_id' => $chat_id
                    // ]);
                    $this->replyWithMessage([
                        'text' => $data,
                        'chat_id' => $chat_id
                    ]);
                }
            }else{
                $reply = "Tidak terdapat retribusi bulanan !";
                $this->replyWithMessage([
                    'text' => $reply,
                    'chat_id' => $chat_id
                ]);
            }

        }elseif(isset($pegawai)){
            $chat->pengguna_type = "App\Pegawai";
            $chat->pengguna_id = $pegawai->id_pegawai;
            $chat->save();

            $retribusi = Retribusi::with('properti')->whereHas('properti', function(Builder $query ) use ($pegawai){
                $query->where('id_desa_adat', $pegawai->id_desa_adat);
            })->with('pelanggan')->where('status', "pending")->get();
            
            // dd($retribusi);
            if($retribusi->isNotEmpty()){
                foreach($retribusi as $r){
                    // $url = str_replace(" ", "%20",$r->properti->file);
                    // $image = "https://7eb5-114-5-111-25.ap.ngrok.io/assets/img/properti/".$url;
                    // dd(new InputFile($image, $r->properti->nama_properti));
                    $data =  $r->properti->nama_properti." \n(".$r->pelanggan->kependudukan->nama.")\nTanggal Retribusi : ".$r->created_at->format('d M Y')."\nNominal : "."Rp".number_format($r->nominal ?? 0,2,',','.')."\n\nLokasi\n\n"."https://www.google.com/maps/search/?api=1&query=".$r->properti->lat.",".$r->properti->lng;
                    // $this->replyWithPhoto([
                    //     'photo' => new InputFile($image, $r->properti->nama_properti),
                    //     'caption' => $data,
                    //     'chat_id' => $chat_id
                    // ]);
                    $this->replyWithMessage([
                        'text' => $data,
                        'chat_id' => $chat_id
                    ]);
                }
            }else{
                $reply = "Tidak terdapat retribusi bulanan !";
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
