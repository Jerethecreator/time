<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class approve extends Notification
{
    use Queueable;
    public $loan_number,$loan_applicant_name;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($loan_number,$loan_applicant_name)
    {
        $this->loan_number = $loan_number;
        $this->loan_applicant_name = $loan_applicant_name;
        //
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
                    ->greeting('Congratulations')
                    ->line('Hi '.$this->loan_applicant_name.' Hope you are doing well, the Loan You applied for has been approved succesfully')
                    ->line('The funds will be credited within 2 Business working days using the payment method you selected')
                    ->action('Notification Action', url('/'))
                    ->line('Your Loan number is '.$this->loan_number. ' Keep it secure.')
                    ->line('Thank you for using our system!');
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
