<?php

namespace App\Console\Commands\TelegramBot;

use Telegram\Bot\Commands\Command;
use App\Kependudukan;
use App\Pelanggan;
use App\Jadwal;
use App\DesaAdat;
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
            
            $desa = $pelanggan->kependudukan->mipil->banjarAdat->desaAdat;
            $jadwal = Jadwal::where('id_desa', $desa->id)->get();
            $scheda = null;
            $schedb = null;
            $schedc = null;
            $schedd = null;
            $schede = null;
            $schedf= null;
            $schedg = null;
            if($jadwal != null){
                foreach($jadwal as $i=>$j){
                    if(strtolower($j->hari) === "senin"){
                        $scheda .= $j->mulai."-".$j->selesai."->".(isset($j->jenis) ? $j->jenis->jenis_sampah : "Umum" )."\n";
                    }elseif(strtolower($j->hari) === "selasa"){
                        $schedb .= $j->mulai."-".$j->selesai."->".(isset($j->jenis) ? $j->jenis->jenis_sampah : "Umum" )."\n";
                    }elseif(strtolower($j->hari) === "minggu"){
                        $schedg .= $j->mulai."-".$j->selesai."->".(isset($j->jenis) ? $j->jenis->jenis_sampah : "Umum" )."\n";
                    }elseif(strtolower($j->hari) === "rabu"){
                        $schedc .= $j->mulai."-".$j->selesai."->".(isset($j->jenis) ? $j->jenis->jenis_sampah : "Umum" )."\n";
                    }elseif(strtolower($j->hari) === "kamis"){
                        $schedd .= $j->mulai."-".$j->selesai."->".(isset($j->jenis) ? $j->jenis->jenis_sampah : "Umum" )."\n";
                    }elseif(strtolower($j->hari) === "jumat"){
                        $schede .= $j->mulai."-".$j->selesai."->".(isset($j->jenis) ? $j->jenis->jenis_sampah : "Umum" )."\n";
                    }elseif(strtolower($j->hari) === "sabtu"){
                        $schedf .= $j->mulai."-".$j->selesai."->".(isset($j->jenis) ? $j->jenis->jenis_sampah : "Umum" )."\n";
                    }
                }
                // dd($sched);
                $text = "Berikut merupakan jadwal Pengangkutan sampah dari Desa Adat ".$desa->desadat_nama."\n(Format 24 jam)\n\n"."*Senin*\n".$scheda."\n"."*Selasa*\n".$schedb."\n"."*Rabu*\n".$schedc."\n"."*Kamis*\n".$schedd."\n"."*Jumat*\n".$schede."\n"."*Sabtu*\n".$schedf."\n"."*Minggu*\n".$schedg."\n";
                $this->replyWithMessage([
                    'text' => $text,
                    'parse_mode' => 'Markdown'
                ]);
            }else{
                $text = "Mohon maaf sebelumnya\nData Jadwal belum tersedia !";
                $this->replyWithMessage(compact('text'));
            }
        }elseif(isset($pegawai)){
            $chat->pengguna_type = "App\Pegawai";
            $chat->pengguna_id = $pegawai->id_pegawai;
            $chat->save();

            $jadwal = Jadwal::where('id_desa', $pegawai->id_desa_adat)->get();
            $desa = DesaAdat::where('id', $pegawai->id_desa_adat)->first();
            $scheda = null;
            $schedb = null;
            $schedc = null;
            $schedd = null;
            $schede = null;
            $schedf= null;
            $schedg = null;
            if($jadwal != null){
                foreach($jadwal as $i=>$j){
                    if(strtolower($j->hari) === "senin"){
                        $scheda .= $j->mulai."-".$j->selesai."->".(isset($j->jenis) ? $j->jenis->jenis_sampah : "Umum" )."\n";
                    }elseif(strtolower($j->hari) === "selasa"){
                        $schedb .= $j->mulai."-".$j->selesai."->".(isset($j->jenis) ? $j->jenis->jenis_sampah : "Umum" )."\n";
                    }elseif(strtolower($j->hari) === "minggu"){
                        $schedg .= $j->mulai."-".$j->selesai."->".(isset($j->jenis) ? $j->jenis->jenis_sampah : "Umum" )."\n";
                    }elseif(strtolower($j->hari) === "rabu"){
                        $schedc .= $j->mulai."-".$j->selesai."->".(isset($j->jenis) ? $j->jenis->jenis_sampah : "Umum" )."\n";
                    }elseif(strtolower($j->hari) === "kamis"){
                        $schedd .= $j->mulai."-".$j->selesai."->".(isset($j->jenis) ? $j->jenis->jenis_sampah : "Umum" )."\n";
                    }elseif(strtolower($j->hari) === "jumat"){
                        $schede .= $j->mulai."-".$j->selesai."->".(isset($j->jenis) ? $j->jenis->jenis_sampah : "Umum" )."\n";
                    }elseif(strtolower($j->hari) === "sabtu"){
                        $schedf .= $j->mulai."-".$j->selesai."->".(isset($j->jenis) ? $j->jenis->jenis_sampah : "Umum" )."\n";
                    }
                }
                // dd($sched);
                $text = "Berikut merupakan jadwal Pengangkutan sampah dari Desa Adat ".$desa->desadat_nama."\n(Format 24 jam)\n\n"."*Senin*\n".$scheda."\n"."*Selasa*\n".$schedb."\n"."*Rabu*\n".$schedc."\n"."*Kamis*\n".$schedd."\n"."*Jumat*\n".$schede."\n"."*Sabtu*\n".$schedf."\n"."*Minggu*\n".$schedg."\n";
                $this->replyWithMessage([
                    'text' => $text,
                    'parse_mode' => 'Markdown'
                ]);
            }else{
                $text = "Data Jadwal belum ditentukan !\nLakukan pengisian data jadwal melalui website !";
                $this->replyWithMessage(compact('text'));
            }
        }else{
            $chat->save();

            $text = "Anda belum terdaftar dalam telegram bot !\nuntuk melihat informasi pendaftaran gunakan perintah /daftar";
            $this->replyWithMessage(compact('text'));
        }
        
    }
}
