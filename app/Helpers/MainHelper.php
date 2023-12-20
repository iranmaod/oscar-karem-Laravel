<?php

use App\Models\CommissionPercentage;
use App\Models\Agent;
use App\Models\InstallmentSchedule;
use App\Models\Transaction;
use Illuminate\Support\Facades\Cache;
use function _\find as LodashFind;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;

if (!function_exists('array_to_object')) {

    /**
     * Convert Array into Object in deep
     *
     * @param array $array
     * @return
     */
    function array_to_object($array)
    {
        return json_decode(json_encode($array));
    }
}

if (!function_exists('empty_fallback')) {

    /**
     * Empty data or null data fallback to string -
     *
     * @return string
     */
    function empty_fallback ($data)
    {
        return ($data) ? $data : "-";
    }
}

if (!function_exists('create_button')) {

    function create_button ($action, $model)
    {
        $action = str_replace($model, "", $action);

        return [
            'submit_text' => ($action == "update") ? "Update" : "Submit",
            'submit_response' => ($action == "update") ? "Updated." : "Submited.",
            'submit_response_notyf' => ($action == "update") ? "Data ".$model." updated successfully" : "Data ".$model." added successfully"
        ];
    }
}


/*** Calculate the deal amount ***/
if (!function_exists('calculate_commission')) {

    function calculate_commission ($amount, $lead_index, $employee_type, $payment_type, $lead)
    {
      $amount = floatval($amount);
      $commissionPercentages = Cache::rememberForever('commission_percentage', function () {
                                                return CommissionPercentage::all();
                                });


      $array = array(
        'amount' => $amount,
        'lead_index' => $lead_index,
        'employee_type' => $employee_type,
        'payment_type'=> $payment_type,
        'lead' => $lead
      );

      //echo "<pre>", var_dump($array), "</pre>";
      $commissionPercentage = LodashFind($commissionPercentages, [
        'commission_employee_type' => $employee_type,
        'commission_lead' => $lead,
        'commission_payment_type' => $payment_type
      ]);


      if($commissionPercentage && $amount > 0){
          $percentage = 1;

          switch ($lead_index) {
            case 1:
                $percentage = $commissionPercentage->first_lead;
            break;
            case 2:
                $percentage = $commissionPercentage->second_lead;
            break;
            case 3:
                $percentage = $commissionPercentage->third_lead;
            break;
            case 4:
                $percentage = $commissionPercentage->fourth_lead;
            break;
            case 5:
                $percentage = $commissionPercentage->fifth_lead;
            break;
            default:
                $percentage = $commissionPercentage->onward_lead;
          }

        //echo "<pre>",var_dump($commissionPercentage),"</pre>";
        $calculatedAmount  = $amount > 0 && $percentage > 0 ? $amount * $percentage/100 : 0;
        return $calculatedAmount;

      } else {
        return 0;
      }

    }
}


if (!function_exists('detect_lead_type')) {

    function detect_lead_type($lead_source)
    {

      switch ($lead_source) {
        case "FollowUp Lead oder Paid Lead":
            $lead_type = 'paid_leads';
        break;
        case "Cold Lead":
            $lead_type = 'cold_leads';
        break;
        default:
            $lead_type = 'customer_leads';
      }

      return $lead_type;
    }
}


if (!function_exists('agent_type')) {

    function agent_type($owner, $setter, $agent_id, $deal_stage)
    {
        if($deal_stage == "6606107"){
            return array( 'name' => 'Setter/Closer', 'slug' => 'setter_closer');
        }

        if($owner == $agent_id){
            return array( 'name' => 'Closer', 'slug' => 'closer');
        }

        if($agent_id == $setter){
            return array( 'name' => 'Setter', 'slug' => 'setter');
        }
    }
}

if (!function_exists('agent_data')) {

    function agent_data($agent_id)
    {
      $agents = Cache::rememberForever('agents-data', function () {return Agent::all();});
      $agent = LodashFind($agents, ['hs_vid' => $agent_id]);

      if($agent){
        return $agent['first_name'].' '.$agent['last_name'];
      }
      return $agent_id;
    }
}

if (!function_exists('agent_deal_number')) {

    function agent_deal_number($owner, $setter, $setter_deal_number, $closer_deal_number, $agent_id)
    {
        if($owner == $agent_id && $agent_id == $setter){
            return $closer_deal_number;
        }

        if($owner == $agent_id){
            return $closer_deal_number;
        }

        if($agent_id == $setter){
            return $setter_deal_number;
        }
    }
}


if (!function_exists('hs_ok_money_format')) {

    function hs_ok_money_format($amount = 0 )
    {
      $amount = is_numeric($amount)? $amount : 0;
      return '€' . number_format($amount, 2);
    }
}

if (!function_exists('hs_ok_time_format')) {

    function hs_ok_time_format($time, $fromFormat = 'Y-m-d H:i:s', $toFormat = 'F d, Y H:i:s')
    {
      if($time){
        $time = Carbon::createFromFormat($fromFormat, $time)->format($toFormat);
      }
      return $time;
    }
}


if (!function_exists('checksum_validate')) {

    function checksum_validate($checksum, $params, $key )
    {
      $query = http_build_query($params, NULL, "&", PHP_QUERY_RFC1738);
      $generated_checksum = sha1($query . $key);
      return ($generated_checksum == $checksum ) ? true : false;
    }
}

if (!function_exists('calculate_installment_amount')) {

    function calculate_installment_amount($orderAmount, $billing_threshold, $include_vat = true, $vatPercentage = 0 )
    {

      $vatAmount = calculate_vat_amount($orderAmount, $vatPercentage);
      $amount = $include_vat ? $orderAmount + $vatAmount : $orderAmount;
      $installmentAmount = $amount/$billing_threshold;

      return $installmentAmount;
    }
}


if (!function_exists('calculate_vat_amount')) {

    function calculate_vat_amount($orderAmount,  $vatPercentage = 0 )
    {
      $vatAmount = $vatPercentage ? $orderAmount * $vatPercentage/100 : 0;
      return $vatAmount;
    }
}

if (!function_exists('get_order_address')) {

    function get_order_address($order)
    {
      $address = array();
      if($order->firstname){
        array_push($address, $order->firstname.' '.$order->lastname);
      }
      if($order->address){
        array_push($address, $order->address);
      }
      if($order->city){
        array_push($address, $order->city);
      }
      if(@$order->country->name_en){
        array_push($address, $order->country->name_en);
      }
      if($order->plz){
        array_push($address, $order->plz);
      }

      if($order->email){
        array_push($address, ' Email: '.$order->email);
      }

      if($order->phone){
        array_push($address, ' Phone: '.$order->phone);
      }

      return join(", ", $address);
    }
}


if (!function_exists('encrypt_hs_string')) {

    function encrypt_hs_string($value)
    {
      $txtValue = Crypt::encryptString($value);
      return $txtValue;
    }
}


if (!function_exists('decrypt_hs_string')) {

    function decrypt_hs_string($value)
    {
      try {
      $decrypted = Crypt::decryptString($value);
      } catch (DecryptException $e) {
      $decrypted = $value;
      }

      return $decrypted;
    }
}

if (!function_exists('partial_decrypt_hs_string')) {

    function partial_decrypt_hs_string($value, $digits)
    {
      try {
      $decrypted = Crypt::decryptString($value);
      } catch (DecryptException $e) {
      $decrypted = $value;
      }

      return substr_replace($decrypted,"********", $digits, -4);
    }
}

if (!function_exists('generate_random_digit')) {

    function generate_random_digit($digit)
    {
      return str_pad(rand(0, pow(10, $digit)-1), $digit, '0', STR_PAD_LEFT);
    }
}


if(!function_exists('generate_installment_schedule')) {
   function generate_installment_schedule($order, $isGenerate = false, $isReset = false)
   {
     $orderId = $order->id;
     $totalAmount = $order->amount + calculate_vat_amount($order->amount, $order->vat_percentage->percentage);
     if($isGenerate){
       InstallmentSchedule::where('order_id', $orderId)->whereNull('paid_date')->delete();
     }

     $installmentCount = InstallmentSchedule::where('order_id', $orderId)->count();
     $remainingAmount = $totalAmount - $order->downpayment_amount - $order->paid_amount;
     $installmentFrequency = $order->installment_frequency;
     $installmentThreshold = $order->installment->billing_threshold ;

     $installmentAmount = round($remainingAmount / $installmentThreshold,2);



     if($isReset){
       $installmentAmount = $order->installment_amount;
       $installmentThreshold = $installmentThreshold;
       $installmentStartDate = Carbon::now();
     } else {
       $installmentStartDate = Carbon::createFromFormat('Y-m-d', $order->installment_start_date);
     }



     $dueDate =  $installmentStartDate->format('Y-m-d');

     $installments = array();
     $orderTransaction = Transaction::where('order_id', $orderId)->latest()->first();

     for($i = $installmentCount; $i < $installmentThreshold; $i++ ){
       $data = array(
         'order_id'  => $orderId,
         'installment' => $i+1,
         'amount' =>  $installmentAmount,
         'due_date' =>  $dueDate

       );

       if($isGenerate){
         $data['payment_id'] = $order->payment->id;
         $data['paid_date'] = ($isReset && $installmentCount == $i) ? $orderTransaction->date : null;
         $data['transaction_id'] = ($isReset && $installmentCount == $i) ? $orderTransaction->transaction_id : null;
       }



     /** Skip if isReset and first Installment record
       * Required to continue pending Payment
       */

         array_push($installments, $data);

    /** Check if installment frequency is weekly or monthly and add equal
      * Interval into the same
      */

       if($installmentFrequency == 'weekly'){
         $dueDate =  $installmentStartDate->addWeeks(1)->format('Y-m-d');
       } else {
         $dueDate =  $installmentStartDate->addMonths(1)->format('Y-m-d');
       }

     }
      return $installments;
   }
}
