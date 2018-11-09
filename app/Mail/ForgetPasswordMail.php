<?php
/**
 * Created by PhpStorm.
 * User: qiankaihua
 * Date: 09/11/2018
 * Time: 7:32 PM
 */

namespace App\Mail;

use App\Models\Verify;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $verify;

    /**
     * ForgetPasswordMail constructor.
     *
     * @param Verify $verify
     */
    public function __construct(Verify $verify)
    {
        $this->verify = $verify;
    }

    /**
     *
     */
    public function build()
    {
        $this->view('resetPasswordMail');
        $this->subject('重置密码通知[Visual Data Structure]');
    }
}