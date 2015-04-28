<?php namespace jorenvanhocht\Blogify\Services;

use Illuminate\Support\Facades\Mail;

class BlogifyMailer {

    /**
     * Mail the generated password
     * to the newly created user
     *
     * @param $to
     * @param $subject
     * @param $data
     */
    public function mailPassword( $to, $subject, $data )
    {
        Mail::send('blogify::mails.password', ['data' => $data], function($message) use ($to, $subject)
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
    public function mailReviewer( $to, $subject, $data  )
    {
        Mail::send('blogify::mails.notifyReviewer', ['data' => $data], function($message) use ($to, $subject)
        {
            $message->to($to)->subject($subject);
        });
    }

}
