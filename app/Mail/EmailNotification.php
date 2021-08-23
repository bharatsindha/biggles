<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailNotification extends Mailable
{
    use Queueable, SerializesModels;
    protected $event;

    /**
     * Create a new message instance.
     *
     * EmailNotification constructor.
     * @param $event
     */
    public function __construct($event)
    {
        $this->event = $event;
    }

   /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $message = $this->view($this->event['view'])
            ->with($this->event['data'])
            ->from('support@muval.com.au', 'Muval Support')
            ->subject($this->event['subject']);

        // Add send to email address
        if (isset($this->event['to']) && !is_null($this->event['to']) && $this->event['to'] != '') {
            if (!is_array($this->event['to'])) {
                $this->event['to'] = explode(',', $this->event['to']);
            }
            if (count($this->event['to']) > 0) {
                $message->to($this->event['to']);
            }
        }

        // Add CC details to email address
        if (isset($this->event['cc']) && !is_null($this->event['cc']) && $this->event['cc'] != '') {
            if (!is_array($this->event['cc'])) {
                $this->event['cc'] = explode(',', $this->event['cc']);
            }
            if (count($this->event['cc']) > 0) {
                $message->cc($this->event['cc']);
            }
        }

        return $message;
    }
}