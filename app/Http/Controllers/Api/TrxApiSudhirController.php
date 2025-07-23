<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use App\Models\User;
use App\Models\Slider;
use App\Models\BankDetail; // Import your model
use Carbon\Carbon;
use App\Models\Payin;
use App\Models\WalletHistory;
use App\Models\withdraw;
use App\Models\GiftCard;
use App\Models\GiftClaim;
use App\Models\CustomerService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\VirtualGame;
use App\Models\Bet;
use App\Helper\jilli;
//use App\Models\{Bet,Card,AdminWinnerResult,User,Betlog,GameSetting,VirtualGame,BetResult,MineGameBet,PlinkoBet,PlinkoIndexList};



class TrxApiSudhirController extends Controller
{
	
	public function bet_history_trx(Request $request)
{
    // Validate the request
    $validator = Validator::make($request->all(), [
        'userid' => 'required|integer',
        'game_id' => 'required|integer',
        'limit' => 'integer|nullable',
        'offset' => 'integer|nullable',
        'from_date' => 'date|nullable',
        'to_date' => 'date|nullable',
    ]);

    $validator->stopOnFirstFailure();

    if ($validator->fails()) {
        return response()->json(['status' => 400, 'message' => $validator->errors()->first()]);
    }

    // Extract validated data
    $userid = $request->userid;
    $game_id = $request->game_id;
    $limit = $request->limit ?? 10000;
    $offset = $request->offset ?? 0;

    // Build the query
    $query = DB::table('bets')
        //->select('bets.*', 'game_settings.name AS game_name', 'virtual_games.name AS virtual_game_name')
		->select('bets.*', 'game_settings.name AS game_name', DB::raw('COALESCE(virtual_games.number, "N/A") AS win_number'))
        ->leftJoin('game_settings', 'game_settings.id', '=', 'bets.game_id')
        ->leftJoin('virtual_games', function ($join) {
            $join->on('virtual_games.game_id', '=', 'bets.game_id')
                 ->on('virtual_games.number', '=', 'bets.number');
        })
        ->where('bets.userid', $userid)
        ->where('bets.game_id', $game_id);

    // Apply date filters if provided
    if ($request->from_date) {
        $query->where('bets.created_at', '>=', $request->from_date);
    }

    if ($request->to_date) {
        $query->where('bets.created_at', '<=', $request->to_date);
    }
    // Apply pagination
    $results = $query->orderBy('bets.id', 'DESC')
                     ->offset($offset)
                     ->limit($limit)
                     ->distinct()
                     ->get();
    // Get total bets count for the user
   $total_bet = DB::table('bets')
    ->where('userid', $userid)
    ->where('game_id', $game_id)
    ->count(); 
      
    
    // Prepare the response
    if ($results->isNotEmpty()) {
        return response()->json([
            'status' => 200,
            'message' => 'Data found',
            'total_bets' => $total_bet,
            'data' => $results
        ]);
    } else {
        return response()->json([
            'status' => 200,
            'message' => 'No Data found',
            'data' => []
        ]);
    }
}
	
	
	
	public function gameSerialNo()
    {
        $date = now()->format('Ymd');
    		// trx
    		$gamesNo6 = $date . "06" . "0001";
    		$gamesNo7 = $date . "07" . "0001";
    		$gamesNo8 = $date . "08" . "0001";
    		$gamesNo9 = $date . "09" . "0001";
                          
            DB::table('betlogs')->where('game_id', 6)
                          ->update(['games_no' => $gamesNo6]);
    		
    		DB::table('betlogs')->where('game_id', 7)
                          ->update(['games_no' => $gamesNo7]);
    		
    		DB::table('betlogs')->where('game_id', 8)
                          ->update(['games_no' => $gamesNo8]);
    		
    		DB::table('betlogs')->where('game_id', 9)
                          ->update(['games_no' => $gamesNo9]);
    
		 
    }	
	
	
	public function trx_result(Request $request)
{
$utc_time = Carbon::now('UTC')->timestamp * 1000;

    $utc_time = $request->utc_time_mili_sec;
    $apiUrl = 'https://api.gamebridge.co.in/seller/v1/get-block-hash';
    $manager_key = 'FEGISo8cR74cf';
  
    $headers = [
        'Authorization' => 'Bearer ' . $manager_key,
        'ValidateUser' => 'Bearer ' . $utc_time
    ];
    
    $payload = [
        'utc_time_mili_sec' => $utc_time
    ];

    try {

        $response = Http::withHeaders($headers)->post($apiUrl, $payload);
      
        if ($response->successful()) {
            $apiResponse = $response->json(); 
          
            if (isset($apiResponse['data'])) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Jilli bet history retrieved successfully.',
                    'data' => $apiResponse['data'],
                ], 200);
            } else {
                return response()->json([
                    'status' => 200,
                    'message' => 'API response does not contain expected data.',
                    'api_response' => $apiResponse,
                ], 200);
            }
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Failed to retrieve Jilli bet history.',
                'api_response' => $response->body(),
            ], 400);
        }
    } catch (\Exception $e) {
        Log::error('PayIn API Error:', ['error' => $e->getMessage()]);
        return response()->json([
            'status' => 500,
            'message' => 'Internal Server Error',
            'error' => $e->getMessage(),
        ], 500);
    }
}

public function trx_result_old(Request $request)
{
    $utc_time = $request->utc_time_mili_sec;  // Get the UTC time from request

    $apiUrl = 'https://api.gamebridge.co.in/seller/v1/get-block-hash';
    $manager_key = 'FEGISo8cR74cf';
  
    $headers = [
        'Authorization' => 'Bearer ' . $manager_key,
        'ValidateUser' => 'Bearer ' . $utc_time
    ];
    
    $payload = [
        'utc_time_mili_sec' => $utc_time
    ];

    try {
        $response = Http::withHeaders($headers)->post($apiUrl, $payload);
      
        if ($response->successful()) {
            $apiResponse = $response->json(); 
            
            // Check if data and hashes exist in the response
            if (isset($apiResponse['result']['data'])) {
                $numbers = array_map(function($item) {
                    // Find the last digit in the hash
                    preg_match_all('/\d/', $item['hash'], $matches); // Get all digits from the hash
                    return end($matches[0]); // Return the last digit
                }, $apiResponse['result']['data']);

                return response()->json([
                    'status' => 200,
                    'message' => 'Last digits retrieved successfully.',
                    'numbers' => $numbers,
                ], 200);
            } else {
                return response()->json([
                    'status' => 200,
                    'message' => 'API response does not contain expected data.',
                    'api_response' => $apiResponse,
                ], 200);
            }
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Failed to retrieve Jilli bet history.',
                'api_response' => $response->body(),
            ], 400);
        }
    } catch (\Exception $e) {
        Log::error('PayIn API Error:', ['error' => $e->getMessage()]);
        return response()->json([
            'status' => 500,
            'message' => 'Internal Server Error',
            'error' => $e->getMessage(),
        ], 500);
    }
}
	// ========================Nitish Start=======================================
	/*
	public function tronscan_api()
	{
		$curl = curl_init();
		$data=array(
			'sort'=>"-balance",
			'start'=>"0",
			'limit'=>"20",
			'producer'=>"",
			'number'=>"",
			'start_timestamp'=>"",
			'end_timestamp'=>"",
			);
		
		$payload=json_encode($data);
		
		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://apilist.tronscanapi.com/api/block?sort=-balance&start=0&limit=20&producer=&number=&start_timestamp=&end_timestamp=',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 20,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		  CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json'
		  ),
		));

		$response = curl_exec($curl);
		return $response;
	}
	*/
// 	public function tronscan_api() ///nitish sir code comment by akhilesh
// 	{
// 		$curl = curl_init();

// 		curl_setopt_array($curl, array(
// 		  CURLOPT_URL => 'https://apilist.tronscanapi.com/api/block',
// 		  CURLOPT_RETURNTRANSFER => true,
// 		  CURLOPT_ENCODING => '',
// 		  CURLOPT_MAXREDIRS => 10,
// 		  CURLOPT_TIMEOUT => 0,
// 		  CURLOPT_FOLLOWLOCATION => true,
// 		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
// 		  CURLOPT_CUSTOMREQUEST => 'GET',
// 		  CURLOPT_HTTPHEADER => array(
// 			'TRON-PRO-API-KEY: 6a05b2fc-e489-4882-ab15-e4047dc22fa2'
// 		  ),
// 		));

// 		$response = curl_exec($curl);

// 		curl_close($curl);
// 		return $response;
// 	}

// public function tronscan_api() 

// {
// $curl = curl_init();

// curl_setopt_array($curl, array(
//   CURLOPT_URL => 'https://vitalityherbal.life/api/v1/trx-auto-genrated-result?gameid=2&limit=10',
//   CURLOPT_RETURNTRANSFER => true,
//   CURLOPT_ENCODING => '',
//   CURLOPT_MAXREDIRS => 10,
//   CURLOPT_TIMEOUT => 0,
//   CURLOPT_FOLLOWLOCATION => true,
//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//   CURLOPT_CUSTOMREQUEST => 'GET',
// ));

// $response = curl_exec($curl);

// curl_close($curl);
// echo $response;

// }

// 	public function trx_results(Request $request)
// 	{
// 		date_default_timezone_set("Asia/Kolkata");
// 		$year=date('Y');
// 		$day=date('d');
// 		$month=date('m');
// 		$date=date('Y-m-d h:i:s');
		
		
// 		$trx_result=$this->tronscan_api();
// 			//	print_r("hii");die;
// 			dd("hii");
// 		$response=json_decode($trx_result,true);

// 		$datas=$response['data'];
// 		//print_r($datas);die;
// 		$my_timestamp = strtotime($date);
		
		
// 		date_default_timezone_set('Asia/Kolkata');
// 		$arr=array();
// 		foreach($datas as $response_new)
// 		{
// 			$hash=$response_new['hash'];
		
			
// 			$reversedStr = strrev($hash);

// 			// Find the first integer in the reversed string
// 			preg_match('/\d/', $reversedStr, $matches);

// 			$firstIntegerFromLast = $matches[0] ?? null;
			
// 			$lastFour = substr($hash, -4);
// 			$lastfive = substr($hash, -5);

// 			$number=$response_new['number'];
// 			$timestamp=$response_new['timestamp'];
// 			$timestamp = $timestamp / 1000;
// 			$datetime = date('Y-m-d H:i:s', $timestamp);
			
// 			$one_min_trx=$this->one_min_trx($number,$datetime,$firstIntegerFromLast,$lastFour,$lastfive);
			
// 			$three_min_trx=$this->three_min_trx($number,$datetime,$firstIntegerFromLast,$lastFour,$lastfive);
			
// 			$five_min_trx=$this->five_min_trx($number,$datetime,$firstIntegerFromLast,$lastFour,$lastfive);
			
// 			$ten_min_trx=$this->ten_min_trx($number,$datetime,$firstIntegerFromLast,$lastFour,$lastfive);
			
// 			//$update_trx_result=$this->trx_cron_result_update();
			
// 		}
// 	}

public function tronscan_api($gameid = 1)
{
    $url = 'https://vitalityherbal.life/api/v1/trx-auto-genrated-result?gameid=' . $gameid . '&limit=10';

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $response = curl_exec($curl);
    curl_close($curl);

    return $response;
}

// public function trx_results($game_id)
// {
//     date_default_timezone_set("Asia/Kolkata");

//     $gameId = 1;
//     $apiResponse = $this->tronscan_api($gameId);
//     $response = json_decode($apiResponse, true);

//     if (!isset($response['result']) || !is_array($response['result']) || count($response['result']) === 0) {
//         return response()->json(['status' => 'No data found for game ID 1']);
//     }

//     // ✅ Get only the FIRST record
//     $data = reset($response['result']); // first element of the array

// //   $hash = $data['tr_hashno'] ?? '';
//     $number = $data['tr_number'] ?? '';
//     $slotId = $data['tr41_slot_id'] ?? '';
// $fullHash = $data['tr_digits'] ?? ''; // Always 5 characters (e.g., a23we)
// $reversedStr = strrev($fullHash);
//     // $cleanHash = str_replace('*', '', $hash);
    
//     // $reversedStr = strrev($cleanHash);
   
//     // print_r($reversedStr);die;
//     // Get first digit from the end
//     preg_match('/\d/', $reversedStr, $matches);
//     $firstIntegerFromLast = $matches[0] ?? null;
   

//     $lastFour = substr($reversedStr, -4);
//     $lastFive = substr($reversedStr, -5);
//     //  print_r($lastFive);die;

//     // Get datetime
//     $overallHash = json_decode($data['tr_overall_hash'], true);
//     $timestamp = ($overallHash['updatedTimestamp'] ?? 0) / 1000;
//     $datetime = date('Y-m-d H:i:s', $timestamp);

//     // ✅ Only insert the first record
//     // $this->one_min_trx($number, $datetime, $firstIntegerFromLast, $lastFour, $lastFive);
//     // $this->three_min_trx($number, $datetime, $firstIntegerFromLast, $lastFour, $lastFive);
//     //  $this->five_min_trx($number, $datetime, $firstIntegerFromLast, $lastFour, $lastFive);
//     //   $this->ten_min_trx($number, $datetime, $firstIntegerFromLast, $lastFour, $lastFive);
    
//      switch ((int)$game_id) {
//         case 6:
//             $this->one_min_trx($number, $datetime, $firstIntegerFromLast, $lastFour, $lastFive);
//             break;
//         case 7:
//             $this->three_min_trx($number, $datetime, $firstIntegerFromLast, $lastFour, $lastFive);
//             break;
//         case 8:
//             $this->five_min_trx($number, $datetime, $firstIntegerFromLast, $lastFour, $lastFive);
//             break;
//         case 9:
//             $this->ten_min_trx($number, $datetime, $firstIntegerFromLast, $lastFour, $lastFive);
//             break;
//         default:
//             return response()->json(['status' => 'Invalid game_id']);
//     }

//     return response()->json(['status' => 'Only first record processed']);
// }
public function trx_results($game_id)
{
    date_default_timezone_set("Asia/Kolkata");

    $apiResponse = $this->tronscan_api(1); // GameID for API is always 1
    $response = json_decode($apiResponse, true);

    if (!isset($response['result']) || !is_array($response['result']) || count($response['result']) === 0) {
        return response()->json(['status' => 'No data found for game ID 1']);
    }

    // ✅ Only first result
    $data = reset($response['result']);

    $number = $data['tr_number'] ?? '';
    $slotId = $data['tr41_slot_id'] ?? '';
    $fullHash = $data['tr_digits'] ?? '';

    $reversedStr = strrev($fullHash);

    // Find first digit from end
    preg_match('/\d/', $reversedStr, $matches);
    $firstIntegerFromLast = $matches[0] ?? null;

    $lastFour = substr($fullHash, -4);
    $lastFive = $fullHash;

    // Get datetime
    $overallHash = json_decode($data['tr_overall_hash'], true);
    $timestamp = ($overallHash['updatedTimestamp'] ?? 0) / 1000;
    $datetime = date('Y-m-d H:i:s', $timestamp);

    // ✅ Conditional method call based on $game_id
    switch ((int)$game_id) {
        case 6:
            $this->one_min_trx($number, $datetime, $firstIntegerFromLast, $lastFour, $lastFive);
            break;
        case 7:
            $this->three_min_trx($number, $datetime, $firstIntegerFromLast, $lastFour, $lastFive);
            break;
        case 8:
            $this->five_min_trx($number, $datetime, $firstIntegerFromLast, $lastFour, $lastFive);
            break;
        case 9:
            $this->ten_min_trx($number, $datetime, $firstIntegerFromLast, $lastFour, $lastFive);
            break;
        default:
            return response()->json(['status' => 'Invalid game_id']);
    }

    return response()->json(['status' => 'Processed first record for game_id ' . $game_id]);
}

private function one_min_trx($number, $datetime, $firstIntegerFromLast, $lastFour, $lastFive)
{
    $year = date('Y');
    $day = date('d');
    $month = date('m');

    $oneminexists = DB::table('trx_one_min_result')->where('blocknumber', $number)->exists();

    // Check if seconds are '54'
    $seconds = date('s', strtotime($datetime));
    if ($seconds === "54") {
        $data = DB::table('trx_one_min_result')
            ->whereRaw('SECOND(blocktime) = 54')
            ->orderBy('blocktime', 'desc')
            ->limit(1)
            ->get();

        if ($data->isNotEmpty()) {
            $period = $data->first()->period;
            $blocktime = $data->first()->blocktime;
            $timestamp = strtotime($blocktime);
            $formattedDate = date('y-m-d', $timestamp);
            $today = date('y-m-d');

            $periods = ($formattedDate == $today)
                ? $period + 1
                : "$year$month$day" . "103010100";
        } else {
            $periods = "$year$month$day" . "103010409";
        }

        if (!$oneminexists) {
            DB::table('trx_one_min_result')->insert([
                'blocktime'        => $datetime,
                'result'           => $firstIntegerFromLast,
                'hash_value'       => "**$lastFour",
                'blocknumber'      => $number,
                'five_digit_value' => $lastFive,
                'period'           => $periods,
                'gameid'           => '6'
            ]);
        }
    }
}


private function three_min_trx($number, $datetime, $firstIntegerFromLast, $lastFour, $lastFive)
{
    $year = date('Y');
    $day = date('d');
    $month = date('m');

    $oneminexists = DB::table('trx_three_min_result')->where('blocknumber', $number)->exists();

    // Check if seconds are '54'
    $seconds = date('s', strtotime($datetime));
    if ($seconds === "54") {
        $data = DB::table('trx_three_min_result')
            ->whereRaw('SECOND(blocktime) = 54')
            ->orderBy('blocktime', 'desc')
            ->limit(1)
            ->get();

        if ($data->isNotEmpty()) {
            $period = $data->first()->period;
            $blocktime = $data->first()->blocktime;
            $timestamp = strtotime($blocktime);
            $formattedDate = date('y-m-d', $timestamp);
            $today = date('y-m-d');

            $periods = ($formattedDate == $today)
                ? $period + 1
                : "$year$month$day" . "103010100";
        } else {
            $periods = "$year$month$day" . "103010409";
        }

        if (!$oneminexists) {
            DB::table('trx_three_min_result')->insert([
                'blocktime'        => $datetime,
                'result'           => $firstIntegerFromLast,
                'hash_value'       => "**$lastFour",
                'blocknumber'      => $number,
                'five_digit_value' => $lastFive,
                'period'           => $periods,
                'gameid'           => '7'
            ]);
        }
    }
}
private function five_min_trx($number, $datetime, $firstIntegerFromLast, $lastFour, $lastFive)
{
    $year = date('Y');
    $day = date('d');
    $month = date('m');

    $oneminexists = DB::table('trx_five_min_result')->where('blocknumber', $number)->exists();

    // Check if seconds are '54'
    $seconds = date('s', strtotime($datetime));
    if ($seconds === "54") {
        $data = DB::table('trx_five_min_result')
            ->whereRaw('SECOND(blocktime) = 54')
            ->orderBy('blocktime', 'desc')
            ->limit(1)
            ->get();

        if ($data->isNotEmpty()) {
            $period = $data->first()->period;
            $blocktime = $data->first()->blocktime;
            $timestamp = strtotime($blocktime);
            $formattedDate = date('y-m-d', $timestamp);
            $today = date('y-m-d');

            $periods = ($formattedDate == $today)
                ? $period + 1
                : "$year$month$day" . "103010100";
        } else {
            $periods = "$year$month$day" . "103010409";
        }

        if (!$oneminexists) {
            DB::table('trx_five_min_result')->insert([
                'blocktime'        => $datetime,
                'result'           => $firstIntegerFromLast,
                'hash_value'       => "**$lastFour",
                'blocknumber'      => $number,
                'five_digit_value' => $lastFive,
                'period'           => $periods,
                'gameid'           => '8'
            ]);
        }
    }
}

private function ten_min_trx($number, $datetime, $firstIntegerFromLast, $lastFour, $lastFive)
{
    $year = date('Y');
    $day = date('d');
    $month = date('m');

    $oneminexists = DB::table('trx_ten_min_result')->where('blocknumber', $number)->exists();

    // Check if seconds are '54'
    $seconds = date('s', strtotime($datetime));
    if ($seconds === "54") {
        $data = DB::table('trx_ten_min_result')
            ->whereRaw('SECOND(blocktime) = 54')
            ->orderBy('blocktime', 'desc')
            ->limit(1)
            ->get();

        if ($data->isNotEmpty()) {
            $period = $data->first()->period;
            $blocktime = $data->first()->blocktime;
            $timestamp = strtotime($blocktime);
            $formattedDate = date('y-m-d', $timestamp);
            $today = date('y-m-d');

            $periods = ($formattedDate == $today)
                ? $period + 1
                : "$year$month$day" . "103010100";
        } else {
            $periods = "$year$month$day" . "103010409";
        }

        if (!$oneminexists) {
            DB::table('trx_ten_min_result')->insert([
                'blocktime'        => $datetime,
                'result'           => $firstIntegerFromLast,
                'hash_value'       => "**$lastFour",
                'blocknumber'      => $number,
                'five_digit_value' => $lastFive,
                'period'           => $periods,
                'gameid'           => '9'
            ]);
        }
    }
}
	
	//========================Nitish End=========================================
	public function trx_result_new(Request $request)
	{
		$gameid=$request->gameid;
		$offset=$request->offset;
		$limit=$request->limit;
		if($gameid=='6') // 1 min
		{
			$alldata = DB::table('trx_one_min_result')->whereRaw('SECOND(blocktime) = 54')->where('gameid', $gameid)->orderBy('id', 'desc')->get();
			$data = DB::table('trx_one_min_result')->select(['*',DB::raw('CAST(period AS UNSIGNED) AS period_int')])->whereRaw('SECOND(blocktime) = 54')->where('gameid', $gameid)->orderBy('id', 'desc')->offset($offset)->take($limit)->get();
			
			$period=$alldata[0]->period;
			$period=$period+1;
		}
		if($gameid=='7') // 3 min
		{
			$alldata = DB::table('trx_three_min_result')->whereRaw('SECOND(blocktime) = 54')->whereRaw('period !=""')->where('gameid', $gameid)->orderBy('id', 'desc')->get();
			$data = DB::table('trx_three_min_result')->select(['*',DB::raw('CAST(period AS UNSIGNED) AS period_int')])->whereRaw('SECOND(blocktime) = 54')->whereRaw('period !=""')->where('gameid', $gameid)->orderBy('id', 'desc')->offset($offset)->take($limit)->get();
			$period=$alldata[0]->period;
			$period=$period+1;
		}
		if($gameid=='8') // 5 min
		{
			$alldata = DB::table('trx_five_min_result')->whereRaw('SECOND(blocktime) = 54')->whereRaw('period !=""')->where('gameid', $gameid)->orderBy('id', 'desc')->get();
			$data = DB::table('trx_five_min_result')->select(['*',DB::raw('CAST(period AS UNSIGNED) AS period_int')])->whereRaw('SECOND(blocktime) = 54')->whereRaw('period !=""')->where('gameid', $gameid)->orderBy('id', 'desc')->offset($offset)->take($limit)->get();
			$period=$alldata[0]->period;
			$period=$period+1;
		}
		if($gameid=='9') // 10 min
		{
			$alldata = DB::table('trx_ten_min_result')->whereRaw('SECOND(blocktime) = 54')->whereRaw('period !=""')->where('gameid', $gameid)->orderBy('id', 'desc')->get();
			$data = DB::table('trx_ten_min_result')->select(['*',DB::raw('CAST(period AS UNSIGNED) AS period_int')])->whereRaw('SECOND(blocktime) = 54')->whereRaw('period !=""')->where('gameid', $gameid)->orderBy('id', 'desc')->offset($offset)->take($limit)->get();
			$period=$alldata[0]->period;
			$period=$period+1;
		}
		
		
		if($data){
			$status="200";
		}
		else{
			$status="400";
		}
		$arr=array(
			'nextPeriod'=>$period,
			'totalCount'=>count($alldata),
			'status'=>$status,
			'data'=>$data
			);
		
		echo json_encode($arr);

	}
	
	public function get_result_by_periodno(Request $request)
	{
		$gameid=$request->gameid;
		$period_no=$request->period_no;
		$number="";
		if($gameid=='6') // 1 min
		{
			$alldata = DB::table('trx_one_min_result')->whereRaw('SECOND(blocktime) = 54')->where('gameid', $gameid)->where('period', $period_no)->orderBy('id', 'desc')->get();
			
			
			if(count($alldata)>0)
			{
				$number=$alldata[0]->result;
			}
		}
		if($gameid=='7') // 3 min
		{
			$alldata = DB::table('trx_three_min_result')->whereRaw('SECOND(blocktime) = 54')->where('gameid', $gameid)->where('period', $period_no)->orderBy('id', 'desc')->get();
			
			
			if(count($alldata)>0)
			{
				$number=$alldata[0]->result;
			}
		}
		if($gameid=='8') // 5 min
		{
			$alldata = DB::table('trx_five_min_result')->whereRaw('SECOND(blocktime) = 54')->where('gameid', $gameid)->where('period', $period_no)->orderBy('id', 'desc')->get();
			
			
			if(count($alldata)>0)
			{
				$number=$alldata[0]->result;
			}
		}
		if($gameid=='9') // 10 min
		{
			$alldata = DB::table('trx_ten_min_result')->whereRaw('SECOND(blocktime) = 54')->where('gameid', $gameid)->where('period', $period_no)->orderBy('id', 'desc')->get();
			
			if(count($alldata)>0)
			{
				$number=$alldata[0]->result;
			}
		}
		
		
		if($alldata){
			$status="200";
		}
		else{
			$status="400";
		}
		$arr=array(
			'win_number'=>$number,
			'status'=>$status,
			);
		
		echo json_encode($arr);

	}
	
	public function trx_cron_result_update()
	{
		//date_default_timezone_set("Asia/kolkata");
		$endDate=date('Y-m-d');
		$startdate = date('Y-m-d H:i:s', strtotime($endDate . ' - 1 minutes'));
		$gameid_input=$_GET['gameid'];
		//echo $gameid;die;
		
		$bets = DB::table('bets')->where('status', 0)->whereIn('game_id',[6,7,8,9])->whereDate('created_at',"$endDate")->get();
		//print_r($bets);die;
		foreach($bets as $bet)
		{
			
			$game_no=$bet->games_no;
			$game_id=$bet->game_id;
			$betid=$bet->id;
			//echo $period_no;
			if($game_id=='6' || $game_id=='7' || $game_id=='8' || $game_id=='9')  // TRX
			{
				
				if($gameid_input==$game_id)
				{
					$url = "https://root.usawin.vip/api/trx/results_by_periodno?period_no=$game_no&gameid=$game_id";
					echo $url;

					 // Example API URL

					$curl = curl_init();

					// Set cURL options
					curl_setopt_array($curl, [
						CURLOPT_RETURNTRANSFER => true,  // Return response as a string
						CURLOPT_URL => $url,             // Target URL
						CURLOPT_HTTPGET => true,         // Use GET request
					]);

					// Execute the request
					$response = curl_exec($curl);

					// Check for errors
					if (curl_errno($curl)) {
						///echo "cURL Error: " . curl_error($curl);
					} else {
						// Print the response
						//echo "Response: " . $response;
						echo $response; 
						$res=json_decode($response,true);
						$win_number=$res['win_number'];
						//$win_number=8;
						if($win_number!="")
						{
							$this->amountdistributioncolors($game_id, $game_no, $win_number);
							/*
							$query = "UPDATE users JOIN (
								SELECT 
									userid, 
									SUM(win_amount) - (SUM(win_amount) * 0.04) AS total_win 
								FROM bets 
								WHERE bets.id = $betid 
								GROUP BY userid
							) AS bet_wins ON users.id = bet_wins.userid 
							SET 
								users.wallet = users.wallet + bet_wins.total_win, 
								users.winning_wallet = users.winning_wallet + bet_wins.total_win, 
								users.updated_at = '2025-02-14 16:41:37' 
							WHERE users.id IN (
								SELECT userid FROM bets WHERE bets.id = $betid
							);
							";
														echo $query;
														DB::statement($query);
					*/
							//DB::statement("UPDATE `users` JOIN (SELECT `userid`,SUM(win_amount) - (SUM(win_amount) * 0.04) AS total_win         FROM `bets` WHERE `win_number` >= 0 AND `games_no` = ? AND `game_id` = ? AND `status` = 1 GROUP BY `userid` ) AS bet_wins ON users.id = bet_wins.userid SET users.wallet = users.wallet + bet_wins.total_win,users.winning_wallet = users.winning_wallet + bet_wins.total_win,        users.updated_at = ? WHERE `users`.`id` IN (SELECT userid FROM `bets` WHERE `win_number` >= 0 AND `games_no` = ? AND `game_id` = ? AND `status` = 1 GROUP BY `userid`)", [$game_no, $game_id, '2025-02-14 16:41:37',$game_no, $game_id]);


							/*
							$winningBets = DB::table('bets')
								->selectRaw('userid, SUM(win_amount) as total_win_amount')
								->where('win_number', '>=', 0)
								->where('games_no', $game_no)
								->where('game_id', $game_id)
								->where('status', 1)
								->groupBy('userid')
								->get();

									//print_r($winningBets);die;
									foreach ($winningBets as $bet) {
										$amount = $bet->total_win_amount;
										$userId = $bet->userid;

									  $amount = (float) $amount;
										 // Calculate 4% tax deduction
									$taxDeduction = $amount * 0.04;  // 4% tax
									$finalAmount = $amount - $taxDeduction;  // Final amount after tax deduction

								User::where('i', $userId)
									->update([
										'wallet' => DB::raw("wallet + {$finalAmount}"),
											'winning_wallet' => DB::raw("winning_wallet + {$finalAmount}"),
											'updated_at' => now()
									]); 

										Bet::where('games_no', $game_no)->where('status','1')->update(['win_status' => '1']); 

									}
									*/
							curl_close($curl);
						}
					}
					
					/*
					$query = "UPDATE `users` JOIN (SELECT `userid`, SUM(win_amount) - (SUM(win_amount) * 0.04) AS total_win FROM `bets`    WHERE `win_number` >= 0 AND `games_no` = {$game_no} AND `game_id` = {$game_id} AND `status` = 1 AND win_status=0 GROUP BY `userid`) AS bet_wins ON users.id = bet_wins.userid SET users.wallet = users.wallet + bet_wins.total_win, users.winning_wallet = users.winning_wallet + bet_wins.total_win,     users.updated_at = '2025-02-14 16:41:37' WHERE `users`.`id` IN (SELECT userid FROM `bets` WHERE `win_number` >= 0 AND `games_no` = {$game_no} AND `game_id` = {$game_id} AND `status` = 1 AND win_status=0 GROUP BY `userid`)";
							echo $query;
							DB::statement($query);
							*/
					
					
					
					$query2 = "UPDATE `bets` SET `win_status`='1' WHERE id='$betid'";
							echo $query2;
						//	DB::statement($query2);

				}

				// Close the cURL session
				
			}

		}
		
		
	}

	
	private function amountdistributioncolors($game_id, $period, $result)
	{
		//echo"$game_id,$period,$res";
		// Fetch the virtual games based on criteria
		$virtualGames = VirtualGame::where('actual_number', $result)->where('game_id', $game_id)
			->where(function ($query) {
				$query->where('type', '!=', 1)->where('multiplier', '!=', '2') //1.5
					  ->orWhere(function ($query) {
						  $query->where('type', 1)->where('multiplier', '2');// 1.5
					  });
			})
			->get();
		//dd($virtualGames);
		foreach ($virtualGames as $winAmount) {
			$multiple = $winAmount->multiplier;
			$number = $winAmount->number;

			if (!empty($number)) {
				// Update bet for result '0'
				//dd($number);
				$win_number=$result;
				
				DB::table('bets')->where('games_no', $period)->update([
        'status' => DB::raw("
            CASE 
                WHEN number='40' THEN 
                    CASE WHEN $win_number IN (SELECT actual_number FROM virtual_games WHERE game_id = $game_id AND name='Big') THEN '1' ELSE '2' END
                WHEN number='50' THEN 
                    CASE WHEN $win_number IN (SELECT actual_number FROM virtual_games WHERE game_id = $game_id AND name='SMALL') THEN '1' ELSE '2' END
                WHEN number='30' THEN 
                    CASE WHEN $win_number IN (SELECT actual_number FROM virtual_games WHERE game_id = $game_id AND name='Red') THEN '1' ELSE '2' END
                WHEN number='10' THEN 
                    CASE WHEN $win_number IN (SELECT actual_number FROM virtual_games WHERE game_id = $game_id AND name='Green') THEN '1' ELSE '2' END
                WHEN number='20' THEN 
                    CASE WHEN $win_number IN (0, 5) THEN '1' ELSE '2' END
                WHEN number='$win_number' THEN '1' 
                ELSE '2' 
            END
        "),
        'win_number' => $win_number,
        'win_amount' => DB::raw("
            CASE 
                WHEN number='40' THEN 
                    CASE WHEN $win_number IN (SELECT actual_number FROM virtual_games WHERE game_id = $game_id AND name='Big') THEN (trade_amount * $multiple) ELSE 0.00 END
                WHEN number='50' THEN 
                    CASE WHEN $win_number IN (SELECT actual_number FROM virtual_games WHERE game_id = $game_id AND name='SMALL') THEN (trade_amount * $multiple) ELSE 0.00 END
                WHEN number='30' THEN 
                    CASE WHEN $win_number IN (SELECT actual_number FROM virtual_games WHERE game_id = $game_id AND name='Red') THEN (trade_amount * $multiple) ELSE 0.00 END
                WHEN number='10' THEN 
                    CASE WHEN $win_number IN (SELECT actual_number FROM virtual_games WHERE game_id = $game_id AND name='Green') THEN (trade_amount * $multiple) ELSE 0.00 END
                WHEN number='20' THEN 
                    CASE WHEN $win_number IN (0, 5) THEN (trade_amount * $multiple) ELSE 0.00 END
				WHEN number='$win_number' THEN (trade_amount * $multiple) 
                ELSE 0.00
            END
        ")
    ]);
			}
		}

	}


	
}