<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;
use Validator;
use Mockery\Exception;
use App\Activity;

use App\Exports\ActivityExport;
use Excel;

class BulkSmsController extends Controller
{
    public function __construct() {
        ini_set('max_execution_time', 900000000000);
    }

    public function sendSms( Request $request ) {
        $sid    = env( 'TWILIO_SID' );
        $token  = env( 'TWILIO_TOKEN' );
        $client = new Client( $sid, $token );

        $reference_no = uniqid();

        $validator = Validator::make($request->all(), [
            'start_number' => 'required',
            'end_number' => 'required',
            'message' => 'required',
            'type' => 'required',
        ]);

        if ( $validator->passes() ) {

            $start_number = $request->get('start_number');
            $end_number = $request->get('end_number');
            $message = $request->get('message');
            $type = $request->get('type');

            $prefix = substr($start_number, 0, 3);
            $s_number = substr($start_number, 3);
            $e_number = substr($end_number, 3);

            if($s_number > $e_number){
                $temp_number = $s_number;
                $s_number = $e_number;
                $e_number = $temp_number;
            }
            
            for ($number=$s_number; $number <= $e_number; $number++) { 

                $phone_number = $prefix.$number;

                try {
                    $result_validate = $client->lookups->v1->phoneNumbers($phone_number)->fetch();
                    
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
                    $count++;
                } catch (\Exception $e) {
                    // dd($e);
                }
            }

            return back()->with( 'success', $count . " messages sent!" );
              
        } else {
            return back()->withErrors( $validator );
        }
    }

    public function whatsAppMsg(Request $request) {

    }

    public function export(){
        return Excel::download(new ActivityExport, 'phone_numbers.xlsx');
    } 

    public function get_numbers(Request $request) {
        $phone_numbers = Activity::distinct('phone_number')->pluck('phone_number')->toArray();
        dump($phone_numbers);
    }

}
