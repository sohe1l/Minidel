<?php

namespace App\Mailers;

use Illuminate\Contracts\Mail\Mailer;
use App\User;

class AppMailer{
    
    protected $mailer;
    protected $from = 'hello@minidel.com';
    protected $to;
    protected $subject;
    protected $view;
    protected $data = [];

    public function __construct(Mailer $mailer){
        $this->mailer = $mailer;
    }

    public function deliver()
    {
        $this->mailer->send($this->view, $this->data, function ($message){
            $message->from($this->from, 'Minidel')
                    ->to($this->to)
                    ->subject($this->subject);

        });
    }

    public function sendEmailConfirmationTo(User $user)
    {
        $this->to = $user->email;
        $this->view = 'emails.confirm';
        $this->subject = 'Welcome to Minidel';
        $this->data = compact('user');
        $this->deliver();
    }

    public function sendContactEmail($inputs)
    {
        $this->to = "hello@minidel.com";
        $this->view = 'emails.contact';
        $this->subject = 'Minidel Contact';
        $this->data = $inputs;
        $this->deliver();
    }

} 


?>