<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class denie extends Notification
{
    public $loan_applicant_name;
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($loan_applicant_name)
    {
        //
        $this->loan_applicant_name = $loan_applicant_name;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->greeting('Application Denied')
                    ->line('Hi ' .$this->loan_applicant_name. ' we regret to inform you that your Loan application was denied for not meeting the minimum requirements.')
                    ->action('Notification Action', url('/'))
                    ->line('For further enquiries please do contact us at info@cashxpress.com')
                    ->line('Thank you for using eliana cashxpress!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
