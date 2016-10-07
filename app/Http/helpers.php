<?php
    function jsonOut($error, $msg){
        $returnData = array(
            'error' => $error,
            'error_message' => $msg
        );
        return response()->json($returnData);
    }

    function saveOrder($store, $user, $cart, $payment, $totalPrice, $fee, $instructions, $type, $addressId=0, $scheduleObj=null){
        
        //$returnData = ['error' => 0,'error_message' => '','error_type' => ''];


        if($payment == '')
            return 'Please select a payment method.';

        if($store->status_listing != 'published')
            return "Store is not confirmed. Please try another store.";

        if(!$store->accept_orders)
            return "Store is not accepting online orders.";

        if($store->status_working != 'open'){
            return 'Store is currently ' . $store->status_working  .', please try again later!';
        }

        if(! (count($cart) > 0)) return "No items in your order";

        $order = new \App\Order;
        $order->user_id = $user->id;
        $order->store_id = $store->id;
        $order->status = 'pending';
        $order->instructions = $instructions;
        $order->cart = json_encode($cart);
        $order->user_address_id = $addressId;
        $order->payment_type_id = $payment;

        if(preg_match("/(delivery|pickup)/", $type) != 1) return "Please either choose delivery or pickup";
        $order->type = $type;

        //get available mode first
        if($scheduleObj && $scheduleObj->status == "true"){ //make sure time is in the future
            if(preg_match("/(sun|mon|tue|wed|thu|fri|sat)/", $scheduleObj->day) != 1) return 'Invalid delivery day';
            if(preg_match("/(2[0-4]|[01][0-9]):([0-5][0-9])/", "$scheduleObj->hour:$scheduleObj->min") != 1) return 'Invalid delivery time';
            $schedule_timestamp = strtotime("$scheduleObj->day $scheduleObj->hour:$scheduleObj->min");
            
            if($schedule_timestamp < time()) return jsonOut(1,'Scheduled delivery cannot be in the past.');
        }else{
            $schedule_timestamp = time();
        }


        //check if pickup / bldg / area ?
        if($type == 'delivery'){
            $address = \App\UserAddress::find($addressId);
            if(!$address) return 'Invalid delivery address';

            if( $store->coverageBuildings->contains('id', $address->building_id)
                && $store->isOpenAtTimestamp('Building Delivery', $schedule_timestamp) != "false" ){
                $coverage = $store->coverageBuildings->where('id',$address->building_id)->first();
                $workmode = "Building Delivery";

            }else if( $store->coverageAreas->contains('id', $address->area_id)
                    && $store->isOpenAtTimestamp('Area Delivery', $schedule_timestamp) != "false" ){
                $coverage = $store->coverageAreas->where('id',$address->area_id)->first();
                $workmode = "Area Delivery";
            }

            if(!isset($workmode))  return ['error' => 1,'error_message' => 'The store does not currenly deliver to your address. You can schedule your order.','error_type' => 'NO_WORKMODE', 'store_url'=>"/$store->slug/order"];

            // get delivery fee  ($workmode, min,fee,feebelowmin,price)
            //check for minimum delivery and charges
            if($coverage->pivot->min > $totalPrice){
                if($coverage->pivot->feebelowmin == 0) return 'Cannot place order below minimum delivery ('.$coverage->pivot->min.' dhs)';
                $order->fee = $coverage->pivot->feebelowmin;
            }else{
                $order->fee = $coverage->pivot->fee;
            }

            $order->discount =  $coverage->pivot->discount;

            if($fee!=$order->fee) return 'Delivery fee is changed please order again';

        }else{
            $workmode = "Normal Openning";
        }


        if($scheduleObj && $scheduleObj->status == "true"){ //make sure time is in the future
            if(preg_match("/(sun|mon|tue|wed|thu|fri|sat)/", $scheduleObj->day) != 1) return 'Invalid delivery day';
            if(preg_match("/(2[0-4]|[01][0-9]):([0-5][0-9])/", "$scheduleObj->hour:$scheduleObj->min") != 1) return 'Invalid delivery time';
            $schedule = strtotime("$scheduleObj->day $scheduleObj->hour:$scheduleObj->min");
            
            if($schedule < time()) return jsonOut(1,'Scheduled delivery cannot be in the past.');
            if($store->isOpenAtTimestamp($workmode, $schedule) != "true") return 'Store is not open for '. $type . ' at the specified time';
            $order->schedule = date("Y-m-d H:i:s", $schedule);
        }else{
            if($store->isOpenNow($workmode) != "true") return 'Store is not open for '. $type;
        }

        if(!($totalPrice > 0))
            return 'Total price cannot be zero';

        $cpt = calculateTotalPrice($cart);
        if($cpt == 'ERR_NOT_AVAILABLE')
            return 'One of the items are not available in the store. Please try again.';

        if($totalPrice != $cpt)
            return 'Total price is not matching system price. Items must have been changed';

        $order->price = $totalPrice;

        $active_promo = $store->promos()->active()->first();
        if($active_promo){
            $order->discount = $active_promo->value;
        }

        $order->save();

        //save order time
        $orderTime = new \App\OrderTime;
        $orderTime->user_id = $user->id;
        $orderTime->order_id = $order->id;
        $orderTime->store_id = $store->id;
        $orderTime->status = 'pending';
        $orderTime->timestamp = time();
        $orderTime->save();
    
        return ['error' => 0,'error_message' => '','error_type' => ''];
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

        if($priceProduct == 'ERR_NOT_AVAILABLE' || $priceOption == 'ERR_NOT_AVAILABLE'){
            return 'ERR_NOT_AVAILABLE';
        }
        
        

        //loop again and sum price
        $total_price = 0;
        foreach($cart as $item){
            $total_price += $priceProduct[$item->id] * $item->quan;
            if(isset($item->options) && is_array($item->options)){
                foreach($item->options as $option){
                    if(is_array($option->selects)){
                        foreach($option->selects as $select){
                            if(!isset($priceOption[$select->id])) return 'ERR_NOT_AVAILABLE';
                            $total_price += $priceOption[$select->id] * $item->quan;
                        }
                    }
                }
            }
        }
        return($total_price);

    }


    // if item is not available it will return error!
    function priceArray($model, $idArr){
        $out = [];
        $products = $model::whereIn('id', $idArr)->select('id', 'price' ,'available');

        //dd($idArr);

        if($model == "App\MenuItem") $products = $products->addSelect('menu_section_id'); //menu section is is required for menu items
        
        $products = $products->get();

        //if($model == "App\MenuOptionOption")
          //  dd($products);

        foreach($products as $p){
            $out[$p->id] = $p->price;
            
            if($p->available==0) return 'ERR_NOT_AVAILABLE';
            if($model == "App\MenuItem"){
                if($p->menuSection->available==0) return 'ERR_NOT_AVAILABLE';
                if($p->menuSection->menuSection != null && $p->menuSection->menuSection->available == 0) return 'ERR_NOT_AVAILABLE';
            }
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




    function getEnumValues($table, $column)
    {
      $type = DB::select( DB::raw("SHOW COLUMNS FROM $table WHERE Field = '$column'") )[0]->Type;
      preg_match('/^enum\((.*)\)$/', $type, $matches);
      $enum = array();
      foreach( explode(',', $matches[1]) as $value )
      {
        $v = trim( $value, "'" );
        $enum = array_add($enum, $v, $v);
      }
      return $enum;
    }
?>