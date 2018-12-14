<?php

namespace App\Mail;

use App\Signal;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SignalReceived extends Mailable
{
    use Queueable, SerializesModels;

    protected $signal;

    /**
     * Create a new message instance.
     *
     * @param Signal $signal
     */
    public function __construct(Signal $signal)
    {
        $this->signal = $signal;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('kaveh.s@live.com')->view('email.signalReceived',[
            'signal' => $this->signal
        ]);
    }
}
