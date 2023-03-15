<?php

namespace App\Notifications;

use App\Retribusi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RetribusiNotif extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Retribusi $retribusi, $type)
    {
        //
        $this->retribusi = $retribusi;
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
        if($this->type == "create"){
            return [
                //
                'message' => "Admin Menambahkan Retribusi untuk Properti ".$this->retribusi->properti->jasa->jenis_jasa,
                'user_id' => $this->retribusi->properti->id_pelanggan,
                'item_id' => $this->retribusi->id,
                'type' => $this->type,
            ];
        }
    }
}
