<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Log;
use Mandrill;

class SendMail extends Job
{
    protected $mandrill,$data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->mandrill = new Mandrill(env('API_KEY_MANDRILL'));
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $message = new \stdClass();
        $message->html = $this->data->html ?? '';
        $message->text = $this->data->text ?? '';
        $message->subject = $this->data->subject;
        $message->from_email = $this->data->from_email;
        $message->from_name  = $this->data->from_name;
        $message->to = $this->data->to;
        $message->track_opens = true;

        try{
            $this->mandrill->messages->send($message);

        }catch (\Mandrill_Error $exception)
        {
            Log::error('mandrill_error',[
                'exception' => $exception->getMessage(),
                'code' => $exception->getCode()
            ]);
        }
    }
}
