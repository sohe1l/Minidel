<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeCalls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'minidel:makecalls';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make calls for the store that have orders waiting for 5 minutes or more.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $date = new \DateTime;
        $date->modify('-3 minutes');
        $timeLimit = $date->format('Y-m-d H:i:s');

        $order = \App\Order::where('status','pending')->where('created_at','<=',$timeLimit)->where('call_last','<=',$timeLimit)->first();

        if($order){
            if($order->call_count == 2){ // already two attemps... cancel order
                $order->status = 'rejected';
                $order->reason = 'Sorry for the inconvenience. We have canceled the order automatically because the store is not responding to the order.';
                $order->save();
                return "Order cancled";
            }

/*
            //make call
            require_once('/path/to/twilio-php/Services/Twilio.php'); // Loads the library
             
            // Your Account Sid and Auth Token from twilio.com/user/account
            $sid = "AC04dabc566d94fe6a72690ece9f732d07";
            $token = "89bf616926b44ae2836da342a3dac6a3";
            $client = new Services_Twilio($sid, $token);

            $url = "http://twimlets.com/echo?Twiml=%3CResponse%3E%3CSay%20voice=%22alice%22%3EHi.%20You%20have%20an%20order%20on%20Mini%20Del%3C%2FSay%3E%3C%2FResponse%3E";
                         
             
            $call = $client->account->calls->create('+17077776046', '+97143615376', $url, array( 
                'Method' => 'GET',  
                "StatusCallback" => "https://www.myapp.com/events",
                'FallbackMethod' => 'GET',  
                'StatusCallbackMethod' => 'POST',
                "StatusCallbackEvent" => array("initiated", "ringing", "answered", "completed"),
                'Record' => 'false', 
            ));

            echo $call->sid;
*/

            
            $order->call_last = date('Y-m-d H:i:s');
            $order->call_count += 1;
            $order->save();

            dd($order);
            $name = $this->ask($order->id);
            // http://twimlets.com/message?Message%5B0%5D=Order Pending on Minidel
        }
        
    }
}
