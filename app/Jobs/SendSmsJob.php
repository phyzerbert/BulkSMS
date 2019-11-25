<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


use Twilio\Rest\Client;
use Mockery\Exception;
use App\Activity;

class SendSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $phone_number;
    protected $message;
    protected $type;
    protected $reference_no;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($phone_number, $message, $type, $reference_no)
    {
        $this->phone_number = $phone_number;
        $this->message = $message;
        $this->type = $type;
        $this->reference_no = $reference_no;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sid    = env( 'TWILIO_SID' );
        $token  = env( 'TWILIO_TOKEN' );
        $client = new Client( $sid, $token );
        
        $phone_number = $this->phone_number;
        $message = $this->message;
        $type = $this->type;
        $reference_no = $this->reference_no;
        try {
            $result_validate = $client->lookups->v1->phoneNumbers($phone_number)->fetch();
            // Send SMS

            if($type == 'whatsapp') {
                $client->messages->create(
                    "whatsapp:".$phone_number,
                    [
                        'from' => "whatsapp:".env('WHATSAPP_FROM'),
                        'body' => $message,
                    ]
                );
            } else {
                $client->messages->create(
                    $phone_number,
                    [
                        'from' => env('TWILIO_FROM'),
                        'body' => $message,
                    ]
                );
            }

            // Save Database
            Activity::create([
                'reference_no' => $reference_no,
                'phone_number' => $phone_number,
                'message' => $message,
                'type' => $type,
            ]);
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
