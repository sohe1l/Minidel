<?php


    function jsonOut($error, $msg){
        $returnData = array(
            'error' => $error,
            'message' => $msg
        );
        return response()->json($returnData);
    }



    function saveOrder($store, $user, $cart, $totalPrice, $fee, $instructions, $type, $addressId=0, $isSchedule="false", $day="", $time=""){
        
        if($store->status_listing != 'published')
            return "Store is not confirmed. Please try another store.";

        if($store->status_working != 'open')
            return 'Store is currently ' . $store->status_working  .', please try again later!';

        if(! (count($cart) > 0)) return "No items in your order";

        $order = new \App\Order;
        $order->user_id = $user->id;
        $order->store_id = $store->id;
        $order->status = 'pending';
        $order->instructions = $instructions;
        $order->cart = json_encode($cart);
        $order->user_address_id = $addressId;

        if(preg_match("/(delivery|pickup)/", $type) != 1) return "Please either choose delivery or pickup";
        $order->type = $type;


        $workmode = ""; // set the work mode for time function
        if($type == 'delivery'){
            $address = \App\UserAddress::find($addressId);
            if(!$address) return 'Invalid delivery address';
            

            if($store->coverageBuildings->contains('id', $address->building_id)){
                $coverage = $store->coverageBuildings->where('id',$address->building_id)->first();
                $workmode = "Building Delivery";
            }else if($store->coverageAreas->contains('id', $address->area_id)){
                $coverage = $store->coverageAreas->where('id',$address->area_id)->first();
                $workmode = "Area Delivery";
            }

            if($workmode=="") return 'Invalid delivery address';


            // get delivery fee  ($workmode, min,fee,feebelowmin,price)
            //check for minimum delivery and charges
            if($coverage->pivot->min > $totalPrice){
                if($coverage->pivot->feebelowmin == 0) return 'Cannot place order below minimum delivery ('.$coverage->pivot->min.' dhs)';
                $order->fee = $coverage->pivot->feebelowmin;
            }else{
                $order->fee = $coverage->pivot->fee;
            }

            if($fee!=$order->fee) return 'Delivery fee is changed please order again';

        }else{
            $workmode = "Normal Openning";
        }

        if($isSchedule == "true"){ //make sure time is in the future
            if(preg_match("/(sun|mon|tue|wed|thu|fri|sat)/", $day) != 1) return 'Invalid delivery day';
            if(preg_match("/(2[0-4]|[01][1-9]|10):([0-5][0-9]:00)/", $time) != 1) return 'Invalid delivery time';
            $schedule = strtotime($day . " " .  $time);
            if($schedule < time()) return jsonOut(1,'Scheduled delivery cannot be in the past.');
            if($store->isOpenAtTimestamp($workmode, $schedule) != "true") return 'Store is not open for '. $type . ' at the specified time';
            $order->schedule = date("Y-m-d H:i:s", $schedule);
        }else{
            if($store->isOpenNow($workmode) != "true") return 'Store is not open for '. $type;
        }

        if(!($totalPrice > 0))
            return 'Total price cannot be zero';
        if($totalPrice != calculateTotalPrice($cart))
            return 'Total price is not matching system price. Items must have been changed';

        $order->price = $totalPrice;

        $order->save();

        return 'ok';
    }



        





    function calculateTotalPrice($cart){
        //calculate price per product idis
        // get products array
        $array_products = [];
        $array_options = [];

        foreach($cart as $item){

            $array_products[] = $item->id;
            if(isset($item->options) && is_array($item->options)){
                foreach($item->options as $option){
                    if(is_array($option->selects)){
                        foreach($option->selects as $select){
                            $array_options[] = $select->id;
                        }
                    }
                }
            }
        }
        //get price of each used product & option
        $priceProduct = priceArray( \App\MenuItem::class , $array_products);
        $priceOption = priceArray( \App\MenuOptionOption::class , $array_options);

        //loop again and sum price
        $total_price = 0;
        foreach($cart as $item){
            $total_price += $priceProduct[$item->id] * $item->quan;
            if(isset($item->options) && is_array($item->options)){
                foreach($item->options as $option){
                    if(is_array($option->selects)){
                        foreach($option->selects as $select){
                            $total_price += $priceOption[$select->id] * $item->quan;
                        }
                    }
                }
            }
        }
        return($total_price);

    }


    function priceArray($model, $idArr){
        $out = [];
        $products = $model::whereIn('id', $idArr)->select('id', 'price')->get();
        foreach($products as $p){
            $out[$p->id] = $p->price;
        }
        return $out;
    }


    function returnRating($rating){
        $output = "";
        for ($i = 1; $i <6; $i++){
            if($rating >= $i)
                $output .= '<span class="glyphicon glyphicon-star"></span>';
            else
                $output .= '<span class="glyphicon glyphicon-star-empty"></span>';
        }
        return $output;
    }
?>