<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public $password;
    public $username;

    /**
     * NewUserMail constructor.
     * @param string $username
     * @param string $password
     */
    public function __construct(string $username, string $password)
    {
        $this->password = $password;
        $this->username = $username;
    }

    /**
     *
     */
    public function build()
    {
        $this->view('newUserMail');
        $this->subject('创建用户通知[Visual Data Structure]');
    }
}