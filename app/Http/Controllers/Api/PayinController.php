<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Validator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class PayinController extends Controller
{
// 	public function payzaaar(Request $request)
// {
//     $validator = Validator::make($request->all(), [
//     'user_id' => 'required|exists:users,id',
//     'cash'    => 'required',
//     'type'    => 'required|in:0',
// ], [
//     'type.in' => 'The selected payment type is invalid. Only type 0 is allowed.',
// ]);


//     if ($validator->fails()) {
//         return response()->json([
//             'status'  => 400,
//             'message' => $validator->errors()->first()
//         ]);
//     }

//     $cash     = $request->cash;
//     $userid   = $request->user_id;
//     $orderid  = 'ORD'.rand(111111,999999);
// 	$username = DB::table('users')->where('id', $userid)->value('username');
    
    
//     $merchantId = "USER000105";
//     $apiToken   = "627ca1a4881e1204cb65d698f21c7525";
//     $username   = "$username";
//     $email      = "johndoe@gmail.com"; 
//     $phone      = "9876543210";
//     $orderId    = "$orderid";
//     $amount     = "$cash"; 
//     $remark     = "Payment for order #$orderId";
    
    
    
    
//     $payload = [
//     'data' => [
//         'merchantid' => $merchantId,
//         'apitoken'   => $apiToken,
//         'username'   => $username,
//         'email'      => $email,
//         'phone'      => $phone,
//         'orderid'    => $orderId,
//         'remark'     => $remark,
//         'amount'     => $amount
//     ],
//     'apiToken' => $apiToken
// ];
    
    
    
//     $curl = curl_init();
//     curl_setopt_array($curl, array(
//       CURLOPT_URL => 'https://payzaaar.com/dashboard/api/encodeData',
//       CURLOPT_RETURNTRANSFER => true,
//       CURLOPT_ENCODING => '',
//       CURLOPT_MAXREDIRS => 10,
//       CURLOPT_TIMEOUT => 0,
//       CURLOPT_FOLLOWLOCATION => true,
//       CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//       CURLOPT_CUSTOMREQUEST => 'POST',
//       CURLOPT_POSTFIELDS =>json_encode($payload),
//       CURLOPT_HTTPHEADER => array(
//         'Content-Type: application/json',
//         'Cookie: ci_session=8l74oama3etp16m9o9mv819p7m1ebufk'
//       ),
//     ));
//     $response = curl_exec($curl);
//     //dd($response);
//     curl_close($curl);
//     $encoded = json_decode($response, true);
//     $encryptedData = $encoded['data'];
    
    
//     $payloadforpayin=[
//         "data"=>"$encryptedData",
//         "apitoken"=>"627ca1a4881e1204cb65d698f21c7525"
//         ];
    
    
    
//     $curl = curl_init();
//     curl_setopt_array($curl, array(
//       CURLOPT_URL => 'https://payzaaar.com/dashboard/api/paynow',
//       CURLOPT_RETURNTRANSFER => true,
//       CURLOPT_ENCODING => '',
//       CURLOPT_MAXREDIRS => 10,
//       CURLOPT_TIMEOUT => 0,
//       CURLOPT_FOLLOWLOCATION => true,
//       CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//       CURLOPT_CUSTOMREQUEST => 'POST',
//       CURLOPT_POSTFIELDS =>json_encode($payloadforpayin),
//       CURLOPT_HTTPHEADER => array(
//         'X-Merchant-ID: USER000105',
//         'X-Api-Key: 627ca1a4881e1204cb65d698f21c7525',
//         'Content-Type: application/json',
//         'Cookie: ci_session=8l74oama3etp16m9o9mv819p7m1ebufk'
//       ),
//     ));
    
//     $responses = curl_exec($curl);
//     //dd($responses);

// if ($responses === false) {
//     return response()->json([
//         'status' => 500,
//         'message' => 'CURL error in paynow request: ' . curl_error($curl),
//     ]);
// }

// curl_close($curl);

// $encodedd = json_decode($responses, true);

// // Check if decoding failed or data is missing
// if (!isset($encodedd['data'])) {
//     return response()->json([
//         'status' => 500,
//         'message' => 'Invalid paynow API response',
//         'raw_response' => $responses,  // to help debug the response format
//     ]);
// }

// $encryptedData = $encodedd['data'];

     
     
//     $payloadForDecode=[
//         "encodedData"=>"$encryptedData",
//         "apiToken"=>"627ca1a4881e1204cb65d698f21c7525"
        
//         ]; 
     

//     $curl = curl_init();

//     curl_setopt_array($curl, array(
//       CURLOPT_URL => 'https://payzaaar.com/dashboard/api/decodeData',
//       CURLOPT_RETURNTRANSFER => true,
//       CURLOPT_ENCODING => '',
//       CURLOPT_MAXREDIRS => 10,
//       CURLOPT_TIMEOUT => 0,
//       CURLOPT_FOLLOWLOCATION => true,
//       CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//       CURLOPT_CUSTOMREQUEST => 'POST',
//       CURLOPT_POSTFIELDS =>json_encode($payloadForDecode),
//       CURLOPT_HTTPHEADER => array(
//         'Content-Type: application/json',
//         'Cookie: ci_session=8l74oama3etp16m9o9mv819p7m1ebufk'
//       ),
//     ));
    
//     $responsess = curl_exec($curl);
//     //dd($responsess);
    
//     curl_close($curl);
//     //echo $responsess;
    
//   // die;
		
// 		  $responsess = json_decode($responsess, true);
//     if (!isset($responsess['data']['status'])) {
//         return response()->json([
//             'status'       => 500,
//             'message'      => 'Invalid decode response format',
//             'raw_response' => $responsess
//         ]);
//     }
//     dd($responsess);

//     // Step 6: Check transaction status
//     if ($responsess['data']['status'] != 'success') {
//         return response()->json([
//             'status'         => 400,
//             'message'        => $responsess['data']['msg'] ?? 'Transaction failed',
//             'orderid'        => $responsess['data']['orderid'] ?? $orderid,
//             'payment_status' => $responsess['data']['status']
//         ]);
//     }

//     // Step 7: Save transaction
//     DB::table('payins')->insert([
//         'user_id'      => $userid,
//         'cash'         => $cash,
//         'type'         => 0,
//         'order_id'     => $orderid,
//         'redirect_url' => "https://root.skywinner.live/uploads/payment_success.php",
//         'status'       => 1,
//         'typeimage'    => "https://root.skywinner.live/public/uploads/payzaar.jpg",
//         'created_at'   => now(),
//         'updated_at'   => now(),
//     ]);

//     // Step 8: Clean payment link (remove spaces)
//     // if (isset($decodedResponse['data']['paymentlink'])) {
//     //     $decodedResponse['data']['paymentlink'] = preg_replace('/\s+/', '', $decodedResponse['data']['paymentlink']);
//     // }
    
//     $responsess['data']['paymentlink'] = preg_replace('/\s+/', '', $responsess['data']['paymentlink']);
// 		$intent_link=$responsess['data']['paymentlink'];
// 		$qr_code_url = 'https://api.qrserver.com/v1/create-qr-code/?amp;size=200x200&data=' . urlencode($intent_link);
// 		$responsess['data']['Qr_Link'] =$qr_code_url;

//     // Step 9: Return response
//     return response()->json([
//         'status'  => 200,
//         'message' => 'Transaction successful',
//         'data'    => $responsess['data']
//     ]);
// }

    public function payzaaar(Request $request)
{
    // Step 1: Validate Input
    $validator = Validator::make($request->all(), [
        'user_id' => 'required|exists:users,id',
        'cash'    => 'required',
        'type'    => 'required',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status'  => 400,
            'message' => $validator->errors()->first()
        ]);
    }

    $cash     = $request->cash;
    $userid   = $request->user_id;
    $orderid  = 'ORD' . rand(111111, 999999);
	$username = DB::table('users')->where('id', $userid)->value('username');

    $merchantId = "USER000105";
    $apiToken   = "627ca1a4881e1204cb65d698f21c7525";
    $username   = "$username";
    $email      = "johndoe@gmail.com";
    $phone      = "9876543210";
    $orderId    = "$orderid";
    $amount     = "$cash";
    $remark     = "Payment for order #$orderId";

    // Step 2: Encode Data
    $payload = [
        'data' => [
            'merchantid' => $merchantId,
            'apitoken'   => $apiToken,
            'username'   => $username,
            'email'      => $email,
            'phone'      => $phone,
            'orderid'    => $orderId,
            'remark'     => $remark,
            'amount'     => $amount
        ],
        'apiToken' => $apiToken
    ];

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://payzaaar.com/dashboard/api/encodeData',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Cookie: ci_session=8l74oama3etp16m9o9mv819p7m1ebufk'
        ],
    ]);
    $response = curl_exec($curl);
    curl_close($curl);

    $encoded = json_decode($response, true);
    if (!isset($encoded['data'])) {
        return response()->json([
            'status' => 500,
            'message' => 'Invalid response from encodeData API',
            'raw_response' => $encoded
        ]);
    }

    $encryptedData = $encoded['data'];

    // Step 3: Call PayNow
    $payloadForPayin = [
        "data"     => $encryptedData,
        "apitoken" => $apiToken
    ];

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://payzaaar.com/dashboard/api/paynow',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payloadForPayin),
        CURLOPT_HTTPHEADER => [
            'X-Merchant-ID: ' . $merchantId,
            'X-Api-Key: ' . $apiToken,
            'Content-Type: application/json',
            'Cookie: ci_session=8l74oama3etp16m9o9mv819p7m1ebufk'
        ],
    ]);
    $payNowResponse = curl_exec($curl);
    curl_close($curl);
    //dd($payNowResponse);

    $payNowDecoded = json_decode($payNowResponse, true);
    if (!isset($payNowDecoded['data'])) {
        return response()->json([
            'status' => 500,
            'message' => 'Invalid response from paynow API',
            'raw_response' => $payNowDecoded
        ]);
    }

//dd($payNowDecoded);
    $finalEncryptedData = $payNowDecoded['data'];

    // Step 4: Decode PayNow Data
    $decodePayload = [
        "encodedData" => $finalEncryptedData,
        "apiToken"    => $apiToken
    ];
    //dd($decodePayload);

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://payzaaar.com/dashboard/api/decodeData',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($decodePayload),
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Cookie: ci_session=8l74oama3etp16m9o9mv819p7m1ebufk'
        ],
    ]);
    $decodeResponse = curl_exec($curl);
    curl_close($curl);
    dd($decodeResponse);

    $decodedResponse = json_decode($decodeResponse, true);

    if (!isset($decodedResponse['data']['status'])) {
        return response()->json([
            'status' => 500,
            'message' => 'Invalid decode response format',
            'raw_response' => $decodedResponse
        ]);
    }

    // Step 5: Check actual transaction status
    if ($decodedResponse['data']['status'] != 'success') {
        return response()->json([
            'status' => 400,
            'message' => $decodedResponse['data']['msg'] ?? 'Transaction failed',
            'orderid' => $decodedResponse['data']['orderid'] ?? $orderid,
            'payment_status' => $decodedResponse['data']['status']
        ]);
    }

    // Step 6: Get User Info
    $user = DB::table('users')->where('id', $userid)->first();
    if (!$user) {
        return response()->json(['status' => 400, 'message' => 'User not found.']);
    }

    // Step 7: Save transaction if success
    DB::table('payins')->insert([
        'user_id'      => $userid,
        'cash'         => $cash,
        'type'         => 0,
        'order_id'     => $orderid,
        'redirect_url' => "https://trx.apponrent.com/uploads/payment_success.php",
        'status'       => 1,
        'typeimages'   => "https://trx.apponrent.com/public/uploads/payzaar.jpg",
        'created_at'   => now(),
        'updated_at'   => now(),
    ]);
//dd($decodedResponse['data']);
		
		$decodedResponse['data']['paymentlink'] = preg_replace('/\s+/', '', $decodedResponse['data']['paymentlink']);
		$intent_link=$decodedResponse['data']['paymentlink'];
		$qr_code_url = 'https://api.qrserver.com/v1/create-qr-code/?amp;size=200x200&data=' . urlencode($intent_link);
		$decodedResponse['data']['Qr_Link'] =$qr_code_url;

    return response()->json([
        'status'  => 200,
        'message' => 'Transaction successful',
        'data'    => $decodedResponse['data']
		
    ]);
}

public function payzaaarCallback(Request $request)
{
    $data = $request->all(); // Get all POST data

    // Log callback for reference/debugging
    DB::table('payzaar_callback')->insert([
        'data'     => json_encode($data),
        'datetime' => now()
    ]);

    // Validate required fields
    $orderId = $data['orderid'] ?? null;
    $amount  = $data['amount'] ?? null;
    $status  = $data['status'] ?? null;
    $utr     = $data['utr'] ?? null;

    if (!$orderId || !$amount || !$status) {
        return response()->json(['status' => 400, 'message' => 'Missing required callback data']);
    }

    if (strtolower($status) === 'success') {

        $payin = DB::table('payins')->where('order_id', $orderId)->first();

        if ($payin && $payin->status != 2) {
            // Mark payin as successful
            DB::table('payins')->where('order_id', $orderId)->update(['status' => 2, 'updated_at' => now()]);

            $userId = $payin->user_id;
            $cash   = $payin->cash;

            $user = DB::table('users')->where('id', $userId)->first();

            if ($user) {
                // Update user wallet balances
                DB::table('users')->where('id', $userId)->update([
                    'wallet'          => DB::raw("wallet + $cash"),
                    'recharge'        => DB::raw("recharge + $cash"),
                    'total_payin'     => DB::raw("total_payin + $cash"),
                    'no_of_payin'     => DB::raw("no_of_payin + 1"),
                    'deposit_balance' => DB::raw("deposit_balance + $cash")
                ]);

                // First recharge check
                if ($user->first_recharge == 0) {
                    DB::table('users')->where('id', $userId)->update([
                        'first_recharge'        => $cash,
                        'first_recharge_amount' => $cash
                    ]);

                    // Referral update
                    if ($user->referral_user_id) {
                        DB::table('users')->where('id', $user->referral_user_id)->update([
                            'yesterday_payin'         => DB::raw("yesterday_payin + $cash"),
                            'yesterday_no_of_payin'   => DB::raw("yesterday_no_of_payin + 1"),
                            'yesterday_first_deposit' => DB::raw("yesterday_first_deposit + $cash")
                        ]);
                    }
                }
            }
        }
    }

    return response()->json(['status' => 200, 'message' => 'Callback processed']);
}


public function checkPayzaaarPayment(Request $request)
{
    $orderid = $request->input('order_id');

    if (empty($orderid)) {
        return response()->json(['status' => 400, 'message' => 'Order ID is required']);
    }

    $match_order = DB::table('payins')->where('order_id', $orderid)->where('status', 1)->first();

    if (!$match_order) {
        return response()->json(['status' => 400, 'message' => 'Order ID not found or already processed']);
    }

    $uid      = $match_order->user_id;
    $cash     = $match_order->cash;
    $type     = $match_order->type;
    $datetime = now();

    $update_payin = DB::table('payins')
        ->where('order_id', $orderid)
        ->where('status', 1)
        ->where('user_id', $uid)
        ->update(['status' => 2]);

    if (!$update_payin) {
        return response()->json(['status' => 400, 'message' => 'Failed to update payment status']);
    }

    // Check if it's user's first recharge
    $referData = DB::table('users')->select('referral_user_id', 'first_recharge')->where('id', $uid)->first();
    $referuserid     = $referData->referral_user_id;
    $first_recharge  = $referData->first_recharge;

    if ($first_recharge == 0) {
        $extra = DB::table('extra_first_deposit_bonus')
            ->where('first_deposit_ammount', '<=', $cash)
            ->where('max_amount', '>=', $cash)
            ->first();

        if ($extra) {
            $bonus  = $extra->bonus;
            $amount = $cash + $bonus;

            DB::table('extra_first_deposit_bonus_claim')->insert([
                'userid'         => $uid,
                'extra_fdb_id'   => $extra->id,
                'amount'         => $cash,
                'bonus'          => $bonus,
                'status'         => 0,
                'created_at'     => $datetime,
                'updated_at'     => $datetime,
            ]);

            DB::update("UPDATE users 
                SET 
                    wallet = wallet + $amount,
                    first_recharge = 1,
                    first_recharge_amount = first_recharge_amount + $amount,
                    recharge = recharge + $amount,
                    total_payin = total_payin + $amount,
                    no_of_payin = no_of_payin + 1,
                    deposit_balance = deposit_balance + $amount
                WHERE id = ?", [$uid]);

        } else {
            // No extra bonus matched
            DB::update("UPDATE users 
                SET 
                    wallet = wallet + $cash,
                    first_recharge = 1,
                    first_recharge_amount = first_recharge_amount + $cash,
                    recharge = recharge + $cash,
                    total_payin = total_payin + $cash,
                    no_of_payin = no_of_payin + 1,
                    deposit_balance = deposit_balance + $cash
                WHERE id = ?", [$uid]);
        }

        if (!empty($referuserid)) {
            DB::update("UPDATE users 
                SET 
                    yesterday_payin = yesterday_payin + $cash,
                    yesterday_no_of_payin = yesterday_no_of_payin + 1,
                    yesterday_first_deposit = yesterday_first_deposit + $cash,
                    created_at = ?
                WHERE id = ?", [$datetime, $referuserid]);
        }

    } else {
        // Not first recharge
        DB::update("UPDATE users 
            SET 
                wallet = wallet + $cash,
                recharge = recharge + $cash,
                total_payin = total_payin + $cash,
                no_of_payin = no_of_payin + 1,
                deposit_balance = deposit_balance + $cash
            WHERE id = ?", [$uid]);

        if (!empty($referuserid)) {
            DB::update("UPDATE users 
                SET 
                    yesterday_payin = yesterday_payin + $cash,
                    yesterday_no_of_payin = yesterday_no_of_payin + 1
                WHERE id = ?", [$referuserid]);
        }
    }

    // ✅ Redirect to success page
    return redirect()->away('https://root.jupitergames.world/uploads/payment_success.php');
}
	
	
	
    public function withdraw_request(Request $request)
    {
    
    		  $date = date('Ymd');
            $rand = rand(1111111, 9999999);
            $transaction_id = $date . $rand;
    	
    		 $userid=$request->userid;
    		 $amount=$request->amount;
    		   $validator=validator ::make($request->all(),
            [
                'userid'=>'required',
    			'amount'=>'required',
    			
            ]);
            $date=date('Y-m-d h:i:s');
            if($validator ->fails()){
                $response=[
                    'success'=>"400",
                    'message'=>$validator ->errors()
                ];                                                   
                
                return response()->json($response,400);
            }
          
    		 $datetime = date('Y-m-d H:i:s');
    		 
             $user = DB::select("SELECT * FROM `users` where `id` =$userid");
    		 $account_id=$user[0]->accountno_id;
    		 $mobile=$user[0]->mobile;
    		 $wallet=$user[0]->wallet;
    // 		 dd($wallet);
    		 $accountlist=DB::select("SELECT * FROM `bank_details` WHERE `id`=$account_id");
    		 
    		 $insert= DB::table('transaction_history')->insert([
            'userid' => $userid,
            'amount' => $amount,
            'mobile' => $mobile,
    		  'account_id'=>$account_id,
            'status' => 0,
    			 'type'=>1,
            'date' => $datetime,
    		  'transaction_id' => $transaction_id,
        ]);
    		  DB::select("UPDATE `users` SET `wallet`=`wallet`-$amount,`winning_wallet`=`winning_wallet`-$amount  WHERE `id`=$userid");
              if($insert){
              $response =[ 'success'=>"200",'data'=>$insert,'message'=>'Successfully'];return response ()->json ($response,200);
          }
          else{
           $response =[ 'success'=>"400",'data'=>[],'message'=>'Not Found Data'];return response ()->json ($response,400); 
          } 
        }
	

	
	
        public function redirect_success(){
            return view('success');
        }
	
	
	 public function qr_view() 
    {

       $show_qr = DB::select("SELECT* FROM `usdt_qr`");
       //$show_qr = DB::select("SELECT `name`, `qr_code` FROM `usdt_qr`");

        if ($show_qr) {
            $response = [
                'message' => 'Successfully',
                'status' => 200,
                'data' => $show_qr
            ];

            return response()->json($response,200);
        } else {
            return response()->json(['message' => 'No record found','status' => 400,
                'data' => []], 400);
        }
    }
    
   public function usdt_payin(Request $request)
{
    $validator = Validator::make($request->all(), [
        'user_id' => 'required|exists:users,id',
        'cash' => 'required|numeric',
        'type' => 'required|integer',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 400,
            'message' => $validator->errors()->first()
        ]);
    }

    $usdt = $request->cash;
    $image = $request->screenshot;
    $type = $request->type;
    $userid = $request->user_id;
    $inr = $usdt;
    $datetime = now();
    $orderid = date('YmdHis') . rand(11111, 99999);

    // Validate image input
    if (empty($image) || $image === '0' || $image === 'null' || $image === null || $image === '' || $image === 0) {
        return response()->json([
            'status' => 400,
            'message' => 'Please Select Image'
        ]);
    }

    // Handle image saving
    $path = '';
    if (!empty($image)) {
        $imageData = base64_decode($image);
        if ($imageData === false) {
            return response()->json([
                'status' => 400,
                'message' => 'Invalid base64 encoded image'
            ]);
        }

        // Save image to /public/usdt_images directory
        $newName = Str::random(6) . '.png';
        $relativePath = 'usdt_images/' . $newName;

        // Ensure directory exists
        if (!file_exists(public_path('usdt_images'))) {
            mkdir(public_path('usdt_images'), 0775, true);
        }

        // Save the image file
        if (!file_put_contents(public_path($relativePath), $imageData)) {
            return response()->json([
                'status' => 400,
                'message' => 'Failed to save image'
            ]);
        }

        // Generate URL to store in DB
        $path = asset('usdt_images/' . $newName);
    }

    // Handle type == 0 (payin logic)
    if ($type == 1) {
        $insert_usdt = DB::table('payins')->insert([
            'user_id' => $userid,
            'cash' => $usdt * 90,
            'usdt_amount' => $inr,
            'type' => '1',
            'typeimage' => $path,
            'order_id' => $orderid,
            'status' => 1,
            'created_at' => $datetime,
            'updated_at' => $datetime
        ]);

        if ($insert_usdt) {
            return response()->json([
                'status' => 200,
                'message' => 'USDT Payment Request sent successfully. Please wait for admin approval.'
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'Failed to insert USDT Payment'
            ]);
        }
    } else {
        return response()->json([
            'status' => 400,
            'message' => 'Invalid Type'
        ]);
    }
}


}
