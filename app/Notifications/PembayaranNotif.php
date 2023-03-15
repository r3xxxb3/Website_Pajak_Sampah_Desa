<?php

namespace App\Notifications;

use App\Pembayaran;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PembayaranNotif extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Pembayaran $pembayaran, $type)
    {
        //
        $this->pembayaran = $pembayaran;
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
        // dd($this->type, $this->properti);
        if(isset($notifiable->pelanggan)){
            if($this->type == "create"){
                return [
                    //
                    'message' => $notifiable->pelanggan->kependudukan->nama." Membuat Pembayaran",
                    'user_id' => $notifiable->pelanggan->id,
                    'item_id' => $this->pembayaran->id_pembayaran,
                    'type' => $this->type,
                ];
            }elseif($this->type == "update"){
                return [
                    //
                    'message' => $notifiable->pelanggan->kependudukan->nama." Mengubah Pembayaran",
                    'user_id' => $notifiable->pelanggan->id,
                    'item_id' => $this->pembayaran->id_pembayaran,
                    'type' => $this->type,
                ];
            }elseif($this->type == "cancel"){
                return [
                    //
                    'message' => $notifiable->pelanggan->kependudukan->nama." Menambahkan Pembayaran",
                    'user_id' => $notifiable->pelanggan->id,
                    'item_id' => $this->pembayaran->id_pembayaran,
                    'type' => $this->type,
                ];
            }
        }else{
            if($this->type == "create"){
                return [
                    //
                    'message' => "Admin membuat Pembayaran",
                    'user_id' => $this->properti->id_pelanggan,
                    'item_id' => $this->pembayaran->id_pembayaran,
                    'type' => $this->type,
                ];
            }elseif($this->type == "update"){
                return [
                    //
                    'message' => "Admin Mengubah Pembayaran ",
                    'user_id' => $this->pembayaran->id_pelanggan,
                    'item_id' => $this->pembayaran->id_pembayaran,
                    'type' => $this->type,
                ];
            }elseif($this->type == "cancel"){
                return [
                    //
                    'message' => "Admin Membatalkan Pembayaran",
                    'user_id' => $this->pembayaran->id_pelanggan,
                    'item_id' => $this->pembayaran->id_pembayaran,
                    'type' => $this->type,
                ];
            }
        }
    }
}
