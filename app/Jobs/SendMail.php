<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Log;
use Mandrill;

class SendMail extends Job
{
    protected $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $message = array(
            'text' => '<div>123</div>',
            'subject' => $this->data->subject,
            'from_email' => $this->data->from_email,
            'from_name' => $this->data->from_name,
            'to' => array(
                $this->data->to
            )
        );

        try{
            $mandrill = new Mandrill(env('API_KEY_MANDRILL'));
            $mandrill->messages->send($message);

        }catch (\Mandrill_Error $exception)
        {
            Log::error('mandrill_error',[
                'exception' => $exception->getMessage(),
                'code' => $exception->getCode()
            ]);
        }
    }
}
