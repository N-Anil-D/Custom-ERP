<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $subData;
    public $subj;
    public $view;

    public function __construct($data, $subData, $subj, $view)
    {
        $this->data     = $data;
        $this->subData  = $subData;
        $this->subj     = $subj;
        $this->view     = $view;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('info@invamed.com','INVAportal')
                ->subject($this->subj)
                ->view($this->view)
                ->with([
                    'data'      => $this->data,
                    'subData'   => $this->subData,
                ]);
    }
}
