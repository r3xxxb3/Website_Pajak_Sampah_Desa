<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Kependudukan;
use App\Properti;
use App\StandarRetribusi;
use App\Retribusi;
use App\BanjarAdat;
use App\DesaAdat;
use App\KramaMipil;
use App\KramaTamiu;
use App\Tamiu;
use App\Pelanggan;
use App\Pegawai;
use App\Pembayaran;
use App\JadwalPengangkutan;
use Illuminate\Support\Facades\Hash;
use Telegram;
use Log;
use App\TelegramChatLog;
use App\TelegramChatContext;
use App\TelegramChatContextDetails;

class TelegramBotController extends Controller
{
    //

    public function telegramTest(){
        // Laravel
        $response = Telegram::setWebhook(['url' => env('TELEGRAM_WEBHOOK_URL')]);
        
    }

    public function webhook(){
        $updates = Telegram::commandsHandler(true);
        // dd($updates);
        $chat_id = $updates->message->chat->id;
        $username = $updates->message->chat->first_name;
        
        $context = TelegramChatLog::where('chat_id', $chat_id)->latest('created_at')->first();

        if(!$updates->message->isEmpty()){
            $messages = explode("-", $updates->message->text);
            // dd($context);
                if(strtolower($messages[0]) === 'halo'){
                    // dd('True');
                    return Telegram::sendMessage([
                        'chat_id' => $chat_id,
                        'text' => 'Halo '.$username." ada yang bisa dibantu ?"
                    ]);
                }elseif(strtolower($messages[0]) === 'daftar'){
                    if($messages[1] == null){
                        $text = "NIK tidak terdeteksi ! gunakan format daftar-nik-username";
                        return Telegram::sendMessage([
                            'chat_id' =>$chat_id,
                            'text' => $text
                        ]);
                    }elseif($messages[2] == null){
                        $text = "Username tidak terdeteksi ! gunakan format daftar-nik-username";
                        return Telegram::sendMessage([
                            'chat_id' =>$chat_id,
                            'text' => $text
                        ]);
                    }else{
                        $kependudukan = Kependudukan::where('nik', $messages[1])->first();
                        $pelanggan = Pelanggan::where('username', $messages[2])->first();
                        $pegawai = Pegawai::where('username', $messages[2])->first();
                        if(!isset($kependudukan)){
                            $text = "Anda belum terdaftar sebagai penduduk, daftarkan diri anda terlebih dahulu !";
                            return Telegram::sendMessage([
                                'chat_id' =>$chat_id,
                                'text' => $text
                            ]);
                        }elseif(isset($pelanggan)){
                            $pelanggan->chat_id = $updates->message->chat->id;
                            $pelanggan->update();
                            $text = "Halo ".$username." !\nAnda berhasil terdaftar pada Bot sebagai Pelanggan !\n untuk melihat daftar command gunakan perintah /list";
                            
                            return Telegram::sendMessage([
                                'chat_id' =>$chat_id,
                                'text' => $text
                            ]); 
                        }elseif(isset($pegawai)){
                            $pegawai->chat_id = $updates->message->chat->id;
                            $pegawai->update();
                            $text = "Halo ".$username." !\nAnda berhasil terdaftar pada Bot sebagai Pegawai !\n untuk melihat daftar command gunakan perintah /list";
                            
                            return Telegram::sendMessage([
                                'chat_id' =>$chat_id,
                                'text' => $text
                            ]); 
                        }else{
                            $text = "Anda Belum terdaftar dalam sistem Retribusi BUPDA !\nLakukan pendaftaran akun terlebih dahulu melalui website !";
                            return Telegram::sendMessage([
                                'chat_id' =>$chat_id,
                                'text' => $text
                            ]);
                        }
                    }
                }elseif(strtolower($messages[0]) === 'help' || strtolower($messages[0]) === 'bantu' || strtolower($messages[0]) === 'list'){
                    $message = $this->commandList($username);
                    return Telegram::sendMessage([
                        'chat_id' =>$chat_id,
                        'text' => $message
                    ]);
                }elseif(strtolower($messages[0]) === 'pengumuman'){
                    $pegawai = Pegawai::where('chat_id', $chat_id)->first();
                    if(isset($pegawai)){
                        if(!empty($messages[1])){
                            $reply = $messages[1];
                            $banjarAdat = BanjarAdat::where('desa_adat_id', $pegawai->id_desa_adat)->get();
                            $mipil = KramaMipil::whereIn('banjar_adat_id', $banjarAdat->map->id)->get();
                            $ktamiu = KramaTamiu::whereIn('banjar_adat_id', $banjarAdat->map->id)->get();
                            $tamiu = Tamiu::whereIn('banjar_adat_id', $banjarAdat->map->id)->get();
                            $penduduk = Kependudukan::whereIn('id', $mipil->map->penduduk_id)->orWhereIn('id', $ktamiu)->orWhereIn('id', $tamiu->map->penduduk_id)->get();
                            $pelanggan = Pelanggan::whereIn('id_penduduk', $penduduk->map->id)->where('chat_id', '!=', 'null')->get();
                            // dd($pelanggan);
                            foreach($pelanggan as $p){
                                Telegram::sendMessage([
                                    'chat_id' =>$p->chat_id,
                                    'text' => $reply
                                ]);
                            }
                            return Telegram::sendMessage([
                                    'chat_id' =>$pegawai->chat_id,
                                    'text' => "Berhasil mengirimkan pengumuman !"
                                ]);
                        }else{
                            $reply = "Tidak dapat melakukan pengumuman jika isi pengumuman kosong !";
                            return Telegram::sendMessage([
                                'chat_id' =>$chat_id,
                                'text' => $reply
                            ]);
                        }

                    }else{
                        $reply = "Anda tidak dapat menggunakan perintah ini !";
                        
                        return Telegram::sendMessage([
                            'chat_id' =>$chat_id,
                            'text' => $reply
                        ]);
                    }

                }else{
                    if($updates->message->entities != null){
    
                    }else{
                        return Telegram::sendMessage([
                            'chat_id' =>$chat_id,
                            'text' => 'halo '.$username."\n untuk memulai pilih /start"
                        ]);
                    }
                }
        }
        // dd($updates);
    }

    public function daftar($context, $step, $message){
        // registering chat id by checking if the user is a customer or not
        
        if($step == 1){
            $kependudukan = Kependudukan::where('nik', $message->message->text)->first();
            if(isset($kependudukan)){
                $chatlog = new TelegramChatLog;
                $chatlog->chat_id = $message->message->chat->id;
                $chatlog->context = 2;
                $chatlog->step = $step;
                $chatlog->is_confirmed = 1;
                $chatlog->message = $message->message->text;
                $chatlog->save();
                
                $reply = TelegramChatContextDetails::where('context_id', $step+1)->first();
                return Telegram::sendMessage([
                    'chat_id' => $message->message->chat->id,
                    'text' => $reply->message
                ]);
            }else{
                $reply = "Anda belum terdaftar dalam daftar kependudukan, daftarkan data anda sebagai penduduk terlebih dahulu ! \nuntuk membatalkan proses pendaftaran pilih /batal";
                return Telegram::sendMessage([
                    'chat_id' =>$message->message->chat->id,
                    'text' => $reply
                ]);
            }
        }elseif($step == 2){
            $kependudukan = Kependudukan::where('nik', $context->message)->first();
            $pelanggan = Pelanggan::where('id_penduduk', $kependudukan->id)->first();
            $pegawai = Pegawai::where('id_penduduk', $kependudukan->id)->first();
            if(isset($pegawai)){
                if($message->message->text == $pegawai->username){
                    $chatlog = new TelegramChatLog;
                    $chatlog->message = $message->message->text;
                    $chatlog->chat_id = $message->message->chat->id;
                    $chatlog->context = $context->context;
                    $chatlog->step = $step+1;
                    $chatlog->pengguna_id = $pegawai->id;
                    $chatlog->pengguna->type = "App\Pegawai";
                    $chatlog->save();

                    $pegawai->chat_id = $message->message->chat->id;
                    $pegawai->update();

                    $reply = TelegramChatContextDetails::where('context_id')->where('step', $step+1)->first();
                    return Telegram::sendMessage([
                        'chat_id' => $message->message->chat->id,
                        'text' => $reply->message
                    ]);
                    $chatlog = new TelegramChatLog;
                    $chatlog->message = $reply;
                    $chatlog->chat_id = $message->message->chat->id;
                    $chatlog->context = 1;
                    $chatlog->step = 0;
                    $chatlog->pengguna_id = $pelanggan->id;
                    $chatlog->pengguna->type = "App\pelanggan";
                    $chatlog->save();
                }else{
                    $reply = "Username yang anda masukan salah !";
                    return Telegram::sendMessage([
                        'chat_id' => $message->message->chat->id,
                        'text' => $reply
                    ]);
                }
            }elseif(isset($pelanggan)){
                if($message->message->text == $pelanggan->username){
                    $chatlog = new TelegramChatLog;
                    $chatlog->message = $message->message->text;
                    $chatlog->chat_id = $message->message->chat->id;
                    $chatlog->context = $context->context;
                    $chatlog->step = $step+1;
                    $chatlog->pengguna_id = $pelanggan->id;
                    $chatlog->pengguna->type = "App\pelanggan";
                    $chatlog->save();

                    $pelanggan->chat_id = $message->message->chat->id;
                    $pelanggan->update();

                    $reply = TelegramChatContextDetails::where('context_id')->where('step', $step+1)->first();
                    return Telegram::sendMessage([
                        'chat_id' => $message->message->chat->id,
                        'text' => $reply->message
                    ]);

                    $chatlog = new TelegramChatLog;
                    $chatlog->message = $reply;
                    $chatlog->chat_id = $message->message->chat->id;
                    $chatlog->context = 1;
                    $chatlog->step = 0;
                    $chatlog->pengguna_id = $pelanggan->id;
                    $chatlog->pengguna->type = "App\pelanggan";
                    $chatlog->save();
                }else{
                    $reply = "Username yang anda masukan salah !";
                    return Telegram::sendMessage([
                        'chat_id' => $message->message->chat->id,
                        'text' => $reply
                    ]);
                }
            }else{
                $reply = "Anda belum terdaftar sebagai pengguna dalam Sistem ! \nlakukan pendaftaran sebagai pelanggan terlebih dahulu !";
                return Telegram::sendMessage([
                    'chat_id' => $message->message->chat->id,
                    'text' => $reply
                ]);
            }
        }elseif($step == 3){
            $reply = TelegramChatContextDetails::where('context_id')->where('step', $step)->first();
            return Telegram::sendMessage([
                'chat_id' => $message->message->chat->id,
                'text' => $reply
            ]);

            $chatlog = new TelegramChatLog;
            $chatlog->message = $message->message->text;
            $chatlog->chat_id = $message->message->chat->id;
            $chatlog->context = 1;
            $chatlog->step = 0;
            $chatlog->is_confirmed = 1;
            $chatlog->save();
        }
    }

    public function checkContext($context, $message){
        // Returns the current context of chat and step also if the answer were confirmed

        if($context->context == 2){
            // dd('true');
            $this->daftar($context, $context->step, $message);
        }elseif($context->context == 3){

        }elseif($context->context == 4){

        }
    }

    public function commandList($username){
        $list = "Halo ".$username." !\n berikut ini merupakan list command yang dapat digunakan pada bot ini, \n 1. daftar /daftar \n 2. cek jadwal /jadwal \n 3. cek retribusi /retribusi";
        return $list;
    }
}
