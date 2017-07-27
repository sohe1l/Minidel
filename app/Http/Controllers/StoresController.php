<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use GuzzleHttp\Client;

use Carbon\Carbon;



class StoresController extends Controller
{


    public function mmm(){

        echo("<h1>TALABAT</h1>");


        //Guzzle
        $client = new Client();

        $jar = new \GuzzleHttp\Cookie\CookieJar;


        $response = $client->request('GET', "https://wla.talabat.com/Account/UserLogin", ['cookies' => $jar]);
        // $body = $response->getBody();
        // $file = (string) $body;
        // dd($jar);


        $response = $client->request('POST', 'https://wla.talabat.com/Account/UserLogin', [
            'form_params' => [
                'UserName' => 'menschmg',
                'Password' => 'mmg@123',
            ],
            'cookies' => $jar
        ]);

        // $body = $response->getBody();
        // $file = (string) $body;
        // dd($file);


        $carbon = new Carbon();

        for($i=0; $i<8; $i++){

          $talabat_date = date("m/d/Y",$carbon->timestamp);

          $response = $client->request('GET', 'https://wla.talabat.com/AJAX/GetSearchResult?oId=&mobile=&phone=&restaurant=&fromdate='. $talabat_date .'&uaeamericana=False&twofourh=False&src=&status=', [
                'cookies' => $jar
            ]);

          $talabat = json_decode($response->getBody());

          $talabat_sum = 0;

          if($talabat->success){
            foreach( $talabat->SearchResultOrderModel as $talabat_order){
                if($talabat_order->PaymentMethod == "CREDITCARD")
                    $talabat_sum += $talabat_order->totalamount;
            }
          }else{
            echo("Error reading talabat.");
          }
          
          echo(date("m/d/Y D",$carbon->timestamp) . " : $talabat_sum <br><br>");

          $carbon->subDay();
        }


        //die;


        echo("<h1>ZOMATO</h1>");



        //Guzzle
        $client = new Client();


        $jar = new \GuzzleHttp\Cookie\CookieJar;
        $headers = [
                        'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.115 Safari/537.36',
                        'accept'     => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
                        'accept-encoding' =>  'gzip, deflate, br',
                        'max-age' => 0 ,

                        'accept-language' => 'en-US,en;q=0.8',

                    ];


        $response = $client->request('GET', "https://www.zomato.com/dubai", 
                [
                    'cookies' => $jar,
                    'headers' => $headers,
                ]);

        $body = $response->getBody();
        $file = (string) $body;
        dd($file);

        $response = $client->request('POST', 'https://www.zomato.com/php/asyncLogin.php?', [
            'form_params' => [
                'login' => 'info@menschcafe.com',
                'password' => 'happyhappy',
                'rememberFlag', 'checked',
            ],
            'cookies' => $jar,
            'headers' => $headers,
        ]);

        // $body = $response->getBody();
        // $file = (string) $body;
        // dd($file);




        $response = $client->request('POST', 'https://www.zomato.com/php/delivery_orders_dashboard_handler.php', [
            'form_params' => [
                'res_id' => '206305',
                'offset' => '0',
                'limit' => '50',
                'action' => 'fetch'
            ],
            'cookies' => $jar,
            'headers' => $headers,
        ]);

        $body = $response->getBody();
        $zomato = json_decode($body);

        echo("<table>". $zomato->data->html ."</table>");













    } // function mmmm

    
}
