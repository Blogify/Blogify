<?php

namespace jorenvanhocht\Blogify\Services;

use Illuminate\Contracts\Mail\Mailer;

class BlogifyMailer
{

    /**
     * Holds an instance of the Mailter contract
     *
     * @var \Illuminate\Contracts\Mail\Mailer
     */
    protected $mail;

    /**
     * Construct the class
     *
     * @param \Illuminate\Contracts\Mail\Mailer $mail
     */
    public function __construct(Mailer $mail)
    {
        $this->mail = $mail;
    }

    /**
     * Mail the generated password
     * to the newly created user
     *
     * @param $to
     * @param $subject
     * @param $data
     */
    public function mailPassword($to, $subject, $data)
    {
        $this->mail->send('blogify::mails.password', ['data' => $data], function($message) use ($to, $subject)
        {
            $message->to($to)->subject($subject);
        });
    }

    /**
     * Send a notification mail to
     * the assigned reviewer
     *
     * @param $to
     * @param $subject
     * @param $data
     */
    public function mailReviewer($to, $subject, $data)
    {
        $this->mail->send('blogify::mails.notifyReviewer', ['data' => $data], function($message) use ($to, $subject)
        {
            $message->to($to)->subject($subject);
        });
    }

}
