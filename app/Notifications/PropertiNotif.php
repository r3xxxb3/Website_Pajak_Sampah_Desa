<?php

namespace App\Notifications;

use App\Properti;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PropertiNotif extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Properti $properti, $type)
    {
        //
        $this->properti = $properti;
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
        // Mail Notification
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
        // dd($notifiable->id_pegawai == null);
        if($notifiable->id_pegawai != null){
            if($this->type == "create"){
                return [
                    //
                    'message' => auth()->guard('web')->user()->kependudukan->nama." Menambahkan Properti dengan Jenis ".$this->properti->jasa->jenis_jasa,
                    'user_id' => auth()->guard('web')->user()->id,
                    'item_id' => $this->properti->id,
                    'type' => $this->type,
                ];
            }elseif($this->type == "update"){
                return [
                    //
                    'message' => auth()->guard('web')->user()->kependudukan->nama." Mengubah Properti ".$this->properti->nama_properti." menjadi Jenis ".$this->properti->jasa->jenis_jasa,
                    'user_id' => auth()->guard('web')->user()->id,
                    'item_id' => $this->properti->id,
                    'type' => $this->type,
                ];
            }elseif($this->type == "cancel"){
                return [
                    //
                    'message' => auth()->guard('web')->user()->kependudukan->nama." melakukan permintaan pembatalan Properti dengan Jenis ".$this->properti->jasa->jenis_jasa,
                    'user_id' => auth()->guard('web')->user()->id,
                    'item_id' => $this->properti->id,
                    'type' => $this->type,
                ];
            }elseif($this->type == "warning"){
                return [
                    'message' => auth()->guard('web')->user()->kependudukan->nama.", segera lakukan pembayaran untuk properti ".$this->properti->nama_properti." !",
                    'user_id' => auth()->guard('web')->user()->id,
                    'item_id' => $this->properti->id,
                    'type' => $this->type,
                ];
            }
        }else{
            if($this->type == "create"){
                return [
                    //
                    'message' => "Admin Menambahkan Properti dengan Jenis ".$this->properti->jasa->jenis_jasa,
                    'user_id' => $this->properti->id_pelanggan,
                    'item_id' => $this->properti->id,
                    'type' => $this->type,
                ];
            }elseif($this->type == "update"){
                return [
                    //
                    'message' => "Admin Mengubah Properti ".$this->properti->nama_properti." menjadi Jenis ".$this->properti->jasa->jenis_jasa,
                    'user_id' => $this->properti->id_pelanggan,
                    'item_id' => $this->properti->id,
                    'type' => $this->type,
                ];
            }elseif($this->type == "cancel"){
                return [
                    //
                    'message' => "Admin Membatalkan Properti dengan Jenis ".$this->properti->jasa->jenis_jasa,
                    'user_id' => $this->properti->id_pelanggan,
                    'item_id' => $this->properti->id,
                    'type' => $this->type,
                ];
            }
        }

    }
}
