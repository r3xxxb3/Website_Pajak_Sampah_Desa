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

class pembayaranCommand extends Command
{
   /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'pembayaran';

    /**
    * @var array Command Aliases
    */
   protected $aliases = ['pembayaranHelp'];

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'command untuk memperlihatkan pembayaran pending';

    public function __construct(){
        $this->url = "https://myrottenproject.org/";
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
            
            $pembayaran = Pembayaran::where('id_pelanggan', $pelanggan->id)->where('status', "pending")->with('detail')->get();

            if($pembayaran->isNotEmpty()){
                foreach($pembayaran as $p){
                    $item = null;
                        foreach($p->detail as $m){
                            if($m->model_type == "App\Retribusi"){
                                $item .= "(Retribusi) ".$m->model->nama_properti." ("."Rp".number_format($m->model->nominal ?? 0,2,',','.').")\n\n";
                            }else{
                                $item .= "(Pengangkutan) ".$m->model->alamat." ("."Rp".number_format($m->model->nominal ?? 0,2,',','.').")\n\n";
                            }
                        }
                    $data = "Item \n\n".$item."\n"."Tanggal Pembayaran : ".$p->created_at->format('d M Y')."\nTotal Nominal : "."Rp".number_format($p->nominal ?? 0,2,',','.')."\n\nKeterangan\n\n".(isset($p->keterangan) ? $p->keterangan : 'Tidak Ada Keterangan !');
                    $this->replyWithMessage([
                        'text' => $data,
                        'chat_id' => $chat_id
                    ]);
                }
            }else{
                $reply = "Tidak terdapat Pembayaran !";
                $this->replyWithMessage([
                    'text' => $reply,
                    'chat_id' => $chat_id
                ]);
            }

        }elseif(isset($pegawai)){
            $chat->pengguna_type = "App\Pegawai";
            $chat->pengguna_id = $pegawai->id_pegawai;
            $chat->save();
            $desa = $pegawai->id_desa_adat;
            $pembayaran = Pembayaran::with('detail')->whereHas('detail', function(Builder $query) use ($desa){
                $query->with('model')->whereHasMorph('model', ['App\Retribusi','App\Pengangkutan'], function(Builder $querys) use ($desa){
                    $prop = Properti::where('id_desa_adat', $desa)->get();
                    $ret = Retribusi::whereIn('id_properti', $prop->map->id)->get();
                    $req = Pengangkutan::where('id_desa_adat', $desa)->get();
                    $querys->where('model_type', "App\Retribusi" )->whereIn('model_id', $ret->map->id)->orWhere('model_type', "App\Pengangkutan")->whereIn('model_id',$req->map->id);
                });
            })->where('status', "pending")->get();
            
            if($pembayaran->isNotEmpty()){
                foreach($pembayaran as $p){
                    $item = null;
                    $wall = "____________________________________";
                    // dd($p->detail->map->model);
                    foreach($p->detail as $m){
                        if($m->model_type == "App\Retribusi"){
                            $item .= "(Retribusi) ".$m->model->pelanggan->kependudukan->nama."\n".$m->model->nama_properti." ("."Rp".number_format($m->model->nominal ?? 0,2,',','.').")\n\n";
                        }else{
                            $item .= "(Pengangkutan) ".$m->model->pelanggan->kependudukan->nama."\n".$m->model->alamat." ("."Rp".number_format($m->model->nominal ?? 0,2,',','.').")\n\n";
                        }
                    }
                    $data = "List Item \n\n".$item."\n".$wall."\nTanggal Pembayaran : ".$p->created_at->format('d M Y')."\nTotal Nominal : "."Rp".number_format($p->nominal ?? 0,2,',','.')."\n\nKeterangan\n\n".($p->keterangan != null ? $p->keterangan : 'Tidak Ada Keterangan !');
                    // dd("Item \n\n".$item."\n"."Tanggal Pembayaran : ".$p->created_at->format('d M Y')."\nTotal Nominal : "."Rp".number_format($p->nominal ?? 0,2,',','.')."\n\nKeterangan\n\n".($p->keterangan == null ? $p->keterangan : 'Tidak Ada Keterangan !'));
                    $this->replyWithMessage([
                        'text' => $data,
                        'chat_id' => $chat_id,
                        
                    ]);
                }
            }else{
                $reply = "Tidak terdapat Pembayaran !";
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
