<?php

namespace App\Notifications;

use App\Pengangkutan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PengangkutanNotif extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Pengangkutan $pengangkutan, $type)
    {
        //
        $this->pengangkutan = $pengangkutan;
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // return (new MailMessage)
        //             ->line('The introduction to the notification.')
        //             ->action('Notification Action', url('/'))
        //             ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        // dd($this->type, $this->pengangkutan);
        if(isset($notifiable->pelanggan)){
            if($this->type == "create"){
                return [
                    //
                    'message' => $notifiable->pelanggan->kependudukan->nama." Menambahkan Permintaan Pengangkutan dengan alamat ".$this->pengangkutan->alamat,
                    'user_id' => $notifiable->pelanggan->id,
                    'item_id' => $this->pengangkutan->id,
                    'type' => $this->type,
                ];
            }elseif($this->type == "update"){
                return [
                    //
                    'message' => $notifiable->pelanggan->kependudukan->nama." Mengubah permintaan pengangkutan ",
                    'user_id' => $notifiable->pelanggan->id,
                    'item_id' => $this->pengangkutan->id,
                    'type' => $this->type,
                ];
            }elseif($this->type == "cancel"){
                return [
                    //
                    'message' => $notifiable->pelanggan->kependudukan->nama." membatalkan permintaan pengangkutan dengan alamat ".$this->pengangkutan->alamat,
                    'user_id' => $notifiable->pelanggan->id,
                    'item_id' => $this->pengangkutan->id,
                    'type' => $this->type,
                ];
            }
        }else{
            if($this->type == "create"){
                return [
                    //
                    'message' => "Admin Menambahkan Permintaan Pengangkutan dengan alamat ".$this->pengangkutan->alamat,
                    'user_id' => $this->pengangkutan->id_pelanggan,
                    'item_id' => $this->pengangkutan->id,
                    'type' => $this->type,
                ];
            }elseif($this->type == "update"){
                return [
                    //
                    'message' => "Admin Mengubah Permintaan Pengangkutan ",
                    'user_id' => $this->pengangkutan->id_pelanggan,
                    'item_id' => $this->pengangkutan->id,
                    'type' => $this->type,
                ];
            }elseif($this->type == "cancel"){
                return [
                    //
                    'message' => "Admin Membatalkan Perminataan Pengangkutan",
                    'user_id' => $this->pengangkutan->id_pelanggan,
                    'item_id' => $this->pengangkutan->id,
                    'type' => $this->type,
                ];
            }
        }
    }
}
