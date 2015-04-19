<?php namespace jorenvanhocht\Blogify\Services;

use Illuminate\Support\Facades\Mail;

class BlogifyMailer {

    public function mailPassword( $to, $subject, $data )
    {
        Mail::send('blogify::mails.password', ['data' => $data], function($message) use ($to, $subject)
        {
            $message->to($to)->subject($subject);
        });
    }

}
