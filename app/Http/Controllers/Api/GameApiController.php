<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{Bet,Card,AdminWinnerResult,User,Betlog,GameSetting,VirtualGame,BetResult,MineGameBet,PlinkoBet,PlinkoIndexList};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Helper\jilli;

use Illuminate\Support\Facades\DB;

class GameApiController extends Controller
{
	public function rebateDetails(Request $request)
    {
        // Get the user ID from the search input
        $u_id = $request->input('userid');

        // Fetch bets based on the user ID
        $bets = DB::table('bets')
            ->where('userid', $u_id)
            ->get();

        // Initialize the game names and bet times mapping
        $gameMapping = [
            1 => ['game_id' => 'Wingo 30 Sec', 'bet_time' => 30],
            2 => ['game_id' => 'Wingo 1 Min', 'bet_time' => 60],
            3 => ['game_id' => 'Wingo 3 Min', 'bet_time' => 180],
            4 => ['game_id' => 'Wingo 5 Min', 'bet_time' => 300],
            6 => ['game_id' => 'TRX 1 Min', 'bet_time' => 60],
            7 => ['game_id' => 'TRX 3 Min', 'bet_time' => 180],
            8 => ['game_id' => 'TRX 5 Min', 'bet_time' => 300],
            9 => ['game_id' => 'TRX 10 Min', 'bet_time' => 600],
        ];

        // Initialize an array to track bets on 40 and 50 for each game_no
        $userBetsOnGame = [];
        $illegalCount = 0;

        // Loop through each bet and check for illegal betting conditions
        foreach ($bets as $bet) {
            // Skip if bet_number is not 40 or 50
            if (!in_array($bet->bet_number, [40, 50])) {
                continue;
            }

            // Track the bets for 40 and 50 on the same game_no
            $key = $bet->userid . '_' . $bet->game_id . '_' . $bet->games_no;

            // Initialize the bet array for this game_no if not set
            if (!isset($userBetsOnGame[$key])) {
                $userBetsOnGame[$key] = [];
            }

            // Store the bet number (40 or 50)
            $userBetsOnGame[$key][] = $bet->bet_number;

            // Check if both 40 and 50 have been placed for this game_no
            if (in_array(40, $userBetsOnGame[$key]) && in_array(50, $userBetsOnGame[$key])) {
                $illegalCount++;
            }
        }
		
		if ($illegalCount === 1) {
            DB::table('users')
                ->where('id', $u_id)
                ->increment('recharge', DB::raw('recharge * 5'));
        }

        // If there are 2 or more illegal bets, block the user
        if ($illegalCount >= 2) {
            DB::table('users')
                ->where('id', $u_id)
                ->update(['status' => 0]); // Status 0 means blocked
        }

        // Return the view with the necessary data  
        return view('rebatesystem.index', [
            'bets' => $bets,
            'illegalCount' => $illegalCount,
            'gameMapping' => $gameMapping,
            'userid' => $u_id,
        ]);
    }
	
	 public function gameSerialNo()
    {
        $date = now()->format('Ymd');
            // wingo
            $gamesNo1 = $date . "01" . "0001";
    		$gamesNo2 = $date . "02" . "0001";
    		$gamesNo3 = $date . "03" . "0001";
    		$gamesNo4 = $date . "04" . "0001";
    		// trx
    		$gamesNo6 = $date . "06" . "0001";
    		$gamesNo7 = $date . "07" . "0001";
    		$gamesNo8 = $date . "08" . "0001";
    		$gamesNo9 = $date . "09" . "0001";
    		// D & T
    		$gamesNo10 = $date . "10" . "0001";
		 	$gamesNo11 = $date . "11" . "0001";
		 	$gamesNo12 = $date . "12" . "0001";
		 	$gamesNo13 = $date . "13" . "0001";
    		
       	    DB::table('betlogs')->where('game_id', 1)
                          ->update(['games_no' => $gamesNo1]);
    		
    		DB::table('betlogs')->where('game_id', 2)
                          ->update(['games_no' => $gamesNo2]);
    		
    		DB::table('betlogs')->where('game_id', 3)
                          ->update(['games_no' => $gamesNo3]);
    		
    		DB::table('betlogs')->where('game_id', 4)
                          ->update(['games_no' => $gamesNo4]);
                          
            DB::table('betlogs')->where('game_id', 6)
                          ->update(['games_no' => $gamesNo6]);
    		
    		DB::table('betlogs')->where('game_id', 7)
                          ->update(['games_no' => $gamesNo7]);
    		
    		DB::table('betlogs')->where('game_id', 8)
                          ->update(['games_no' => $gamesNo8]);
    		
    		DB::table('betlogs')->where('game_id', 9)
                          ->update(['games_no' => $gamesNo9]);
    
            DB::table('betlogs')->where('game_id', 10)
                          ->update(['games_no' => $gamesNo10]);
		 
		 	DB::table('betlogs')->where('game_id', 11)
                          ->update(['games_no' => $gamesNo11]);
		 
		 	DB::table('betlogs')->where('game_id', 12)
                          ->update(['games_no' => $gamesNo12]);
		 
		 	DB::table('betlogs')->where('game_id', 13)
                          ->update(['games_no' => $gamesNo13]);
		 
    }	
	
    

public function dragon_bet(Request $request)
{
    $validator = Validator::make($request->all(), [
        'userid' => 'required',
    
        'game_id' => 'required',
      
        'json'=>'required'
    ]);

    $validator->stopOnFirstFailure();

    if ($validator->fails()) {
        return response()->json(['status' => 400, 'message' => $validator->errors()->first()],400);
    }
    
    $datetime=date('Y-m-d H:i:s');
    
     $testData = $request->json;
    $userid = $request->userid;
    $gameid = $request->game_id;
  // $gameno = $request->game_no;
 
  $orderid = date('YmdHis') . rand(11111, 99999);
    
    $gamesrno=DB::select("SELECT games_no FROM `betlogs` WHERE `game_id`=$gameid  LIMIT 1");
    $gamesno=$gamesrno[0]->games_no;
 
   //dd($gamesno);
    
    foreach ($testData as $item) {
        $user_wallet = DB::table('users')->select('wallet')->where('id', $userid)->first();
            $userwallet = $user_wallet->wallet;
   
        $number = $item['number'];
        $amount = $item['amount'];
        
        $commission = $amount * 0.05; // Calculate commission   
    $betAmount = $amount - $commission; // Bet amount after commission deduction

        if($userwallet >= $amount){
      if ($amount>=1) {
        DB::insert("INSERT INTO `bets`(`amount`,`trade_amount`,`commission`, `number`, `games_no`, `game_id`, `userid`, `status`,`order_id`,`created_at`,`updated_at`) 
            VALUES ('$amount','$betAmount','$commission', '$number', '$gamesno', '$gameid', '$userid', '0','$orderid','$datetime','$datetime')");

        $data1 = DB::table('virtual_games')->where('game_id',$gameid)->where('number',$number)->first();
        $multiplier = $data1->multiplier;
        $num = $data1->actual_number;
       $multiply_amt = $multiplier*$amount;
       $bet_amt = DB::table('betlogs')->where('game_id',$gameid)->where('number',$num)->update([
           'amount'=>DB::raw("amount + $multiply_amt")
           ]);
       DB::table('users')->where('id', $userid)->update(['wallet' => DB::raw("wallet - $amount")]);
      }
      }
      else {
                $response['msg'] = "Insufficient balance";
                $response['status'] = "400";
                return response()->json($response);
            }

    }

     return response()->json([
        'status' => 200,
        'message' => 'Bet Successfully',
    ]);   
    
}
	

	
private function rebateCheck($userid,$gameid)
{
		$query = "
    WITH illegal_check AS (
        SELECT games_no, 
               userid,  -- Make sure userid is selected and properly grouped
               CASE 
			   		WHEN SUM(number = 40) > 0 AND SUM(number = 50) > 0 
                    AND SUM(number = 10) > 0 AND SUM(number = 30) > 0 THEN 2
                   WHEN SUM(number = 40) > 0 AND SUM(number = 50) > 0 THEN 1
                   WHEN SUM(number = 10) > 0 AND SUM(number = 30) > 0 THEN 1
                   ELSE 0 
               END AS illegal
        FROM bets
        WHERE userid = ?
        GROUP BY games_no, userid  -- Group by both games_no and userid
    )
    SELECT userid, SUM(illegal) AS final_illegal_status
    FROM illegal_check WHERE userid=?
    GROUP BY userid;
";

$results = DB::select($query, [$userid,$userid]);

		
		$illegle_count=$results[0]->final_illegal_status;

		if($illegle_count=='1')
		{
			//echo "Ram";
			DB::select("UPDATE `users` SET recharge=5*recharge,illegal_count=$illegle_count WHERE id='$userid' AND illegal_count=1");
		}
		if($illegle_count>='2')
		{
			//echo "shyam";
			DB::select("UPDATE `users` SET status=1 WHERE id='$userid'");
		}
		
		/*
		$bets = DB::table('bets')->where('userid', $userid)->where('game_id',$gameid)->get();

		$userBetsOnGame = [];
		$illegalCount = 0;

		foreach ($bets as $bet) {
			if (!in_array($bet->number, [40, 50])) {
				continue;
			}

			$key = $bet->userid . '_' . $bet->game_id . '_' . $bet->games_no;
			echo $key;
			echo "</br>";
			print_r($userBetsOnGame);

			if (!isset($userBetsOnGame[$key])) {
				$userBetsOnGame[$key] = [];
			}

			$userBetsOnGame[$key][] = $bet->number;
			print("=====================");
			print_r($userBetsOnGame);
			// Check if both 40 and 50 are in the same bet
			if (in_array(40, $userBetsOnGame[$key]) && in_array(50, $userBetsOnGame[$key])) {
				$illegalCount++;
			}
		}
		echo $illegalCount;die;

		// Get current user details
		$user = DB::table('users')->where('id', $userid)->first();

		// Pehli baar illegal bet pe recharge 5x karna hai (only recharge, no block)
		if ($illegalCount == 1 && $user) {
			DB::table('users')
				->where('id', $userid)
				->update(['recharge' => $user->recharge * 5]);
		}

		// Dusri baar illegal bet kare tabhi block hoga (status update)
		if ($illegalCount >= 2) {
			DB::table('users')
				->where('id', $userid)
				->update(['status' => 0]);
		}
		*/
	}

public function bet(Request $request)
{
    // Validate the request
    $validator = Validator::make($request->all(), [
        'userid' => 'required|exists:users,id',
        'game_id' => 'required|exists:virtual_games,game_id',
        'number' => 'required',
        'amount' => 'required|numeric|min:1',
		'games_no' => 'required',
    ]);
	$ip=$_SERVER["REMOTE_ADDR"];

    if ($validator->fails()) {
        return response()->json(['status' => 400, 'message' => $validator->errors()->first()]);
    }
    // $amt=$request->amount;
    // dd($amt);
	$uid=$request->userid;
	//dd($uid);
	//$update_wallet = jilli::update_user_wallet($uid);
    $user = User::findOrFail($request->userid);
    
	$amount=$request->amount;
    // Check user wallet balance
    if ($user->wallet < $request->amount) {
        return response()->json(['status' => 400, 'message' => 'Insufficient balance']);
    }

    $commission = $request->amount * 0.02; // Calculate commission 0.05
    //dd($commission); 
    $betAmount = $request->amount - $commission; // Net bet amount
    //dd($betAmount);
    // Get virtual games data
    $virtualGames = VirtualGame::where('number', $request->number)
        ->where('game_id', $request->game_id)
        ->get(['multiplier', 'actual_number']);

    // Create a new bet
	if($request->game_id=='6'){
		$data = DB::table('trx_one_min_result')->whereRaw('SECOND(blocktime) = 54')->orderBy('blocktime', 'desc')->limit(1)->get();
		//dd($data);

		// Check if data is not empty
		if ($data->isNotEmpty()) {
			// Access the first row of data
			$period = $data->first()->period;

			// Increment the period

			$games_no = $period + 1;
			//$periods=20250207103010663;

		} else {
			// Handle the case where no data is found
			$games_no = "" ;// Or any default value you want
			//$periods=20250207103010731;
		}
	}
	else{
		//$games_no=Betlog::where('game_id', $request->game_id)->value('games_no');
		$games_no=$request->games_no;
	}
    $bet = Bet::create([
        'amount' => $request->amount,
        'trade_amount' => $betAmount,
        'commission' => $commission,
        'number' => $request->number,
        'games_no' => $games_no,
        'game_id' => $request->game_id,
        'userid' => $user->id,
        'order_id' => now()->format('YmdHis') . rand(11111, 99999),
        'created_at' => now(),
        'updated_at' => now(),
        'status' => 0,
		'ip'=>$ip
    ]);
    //dd($bet);

    // Update bet logs
    foreach ($virtualGames as $game) {
        Betlog::where('game_id', $request->game_id)
            ->where('number', $game->actual_number)
            ->increment('amount', $betAmount * $game->multiplier);
    }

    // Update user's wallet and recharge
    $user->decrement('wallet', $request->amount);
	$user->decrement('recharge', $request->amount);
    
    $user->increment('today_turnover', $request->amount);
	
	///rebate check start  //
	
	 		$this->rebateCheck($user->id,$request->game_id);
	
	//rebate check end ///
	
	//$deduct_jili = jilli::deduct_from_wallet($uid,$amount);'jili'=>$deduct_jili

    return response()->json(['status' => 200, 'message' => 'Bet Successfully']);
}

public function win_amount(Request $request)
{
    $validator = Validator::make($request->all(), [ 
        'userid' => 'required|integer',
        'game_id' => 'required|integer',
        'games_no' => 'required|integer',
    ]);

    if ($validator->fails()) {
        return response()->json(['status' => 400, 'message' => $validator->errors()->first()], 200);
    }

    $game_id = $request->game_id;
    $userid = $request->userid;
    $game_no = $request->games_no;
    
    // echo "$game_id,$userid,$game_no";
    // die;
	
	// $win_amount = Bet::selectRaw('SUM(win_amount) AS total_amount, games_no, game_id AS gameid, win_number AS number, 
      //  CASE WHEN SUM(win_amount) = 0 THEN "lose" ELSE "win" END AS result')
       // ->where('games_no', $game_no)
       // ->where('game_id', $game_id)
      //  ->where('userid', $userid)
      //  ->groupBy('games_no', 'game_id', 'win_number')
      //  ->first();
	
   
    $win_amount = Bet::selectRaw('SUM(win_amount) AS total_amount, games_no, game_id AS gameid, COALESCE(win_number, 0) AS number, 
    CASE WHEN SUM(win_amount) = 0 THEN "lose" ELSE "win" END AS result')
    ->where('games_no', $game_no)
    ->where('game_id', $game_id)
    ->where('userid', $userid)
    ->groupBy('games_no', 'game_id', 'win_number')
    ->first();

       
    if ($win_amount) {
         $win = [
    'win' => $win_amount->total_amount,
    'games_no' => $win_amount->games_no,
    'result' => $win_amount->result,
    'gameid' => $win_amount->gameid,
    'number' => $win_amount->number
];
        
        return response()->json([
            'message' => 'Successfully',
            'status' => 200,
            'data' => $win,
            
        ], 200);
    } else {
        return response()->json(['msg' => 'No record found', 'status' => 400], 200);
    }
}
	
	//=========================================================Nitish Start================================
	
	public function trx_cron_result_update()
	{
		date_default_timezone_set("Asia/kolkata");
		$endDate=date('Y-m-d');
		$startdate = date('Y-m-d H:i:s', strtotime($endDate . ' - 1 minutes'));
		
		
		
		$bets = DB::table('bets')->where('status', 0)->whereDate('created_at',"$endDate")->get();
		foreach($bets as $bet)
		{
			$game_no=$bet->games_no;
			$game_id=$bet->game_id;
			//echo $period_no;
			if($game_id=='6' || $game_id=='7' || $game_id=='8' || $game_id=='9')  // TRX
			{
				$url = "https://root.usawin.vip/api/trx/results_by_periodno?period_no=$game_no&gameid=$game_id"; // Example API URL
				echo $url;
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
						DB::table('bets')->where('games_no', "$game_no")->update(['status' => DB::raw("(CASE WHEN number='40' THEN (CASE WHEN '$win_number' IN(SELECT actual_number FROM virtual_games WHERE game_id = $game_id AND name='Big') then '1' else '2' END) 
WHEN number='50' THEN (CASE WHEN '$win_number' IN(SELECT actual_number FROM virtual_games WHERE game_id = $game_id AND name='SMALL') then '1' else '2' END)
WHEN number='30' THEN (CASE WHEN '$win_number' IN(SELECT actual_number FROM virtual_games WHERE game_id = $game_id AND name='Red') then '1' else '2' END) 
WHEN number='10' THEN (CASE WHEN '$win_number' IN(SELECT actual_number FROM virtual_games WHERE game_id = $game_id AND name='Green') then '1' else '2' END)
WHEN number='20' THEN (CASE WHEN '$win_number' IN(0,5) then '1' else '2' END)
WHEN number='$win_number' THEN '1' ELSE '2' END)"),'win_number' => "$win_number"]);
					}

				}

				// Close the cURL session
				curl_close($curl);
			}

		}
		
	}
	//=========================================================Nitish End================================


// public function results(Request $request)
// {
//     // Validate incoming request data
//     $validator = Validator::make($request->all(), [
//         'game_id' => 'required',
//         'limit' => 'required|integer|min:1', // Ensure limit is a positive integer
//         'offset' => 'integer|min:0', // Ensure offset is a non-negative integer
//         'created_at' => 'array', // Expect created_at as an array
//         'created_at.from' => 'date|nullable', // Validate from date
//         'created_at.to' => 'date|nullable', // Validate to date
//         'status' => 'string|nullable', // Optional status validation
//     ]);

//     $validator->stopOnFirstFailure();

//     // Return error response if validation fails
//     if ($validator->fails()) {
//         return response()->json(['status' => 400, 'message' => $validator->errors()->first()]);
//     }

//     // Extract validated parameters
//     $gameId = $request->game_id;
//     $limit = $request->limit;
//     $offset = $request->offset ?? 0;
//     $fromDate = $request->created_at['from'] ?? null;
//     $toDate = $request->created_at['to'] ?? null;

//     // Build the query using Eloquent
//     $query = BetResult::with(['virtualGame', 'gameSetting'])
//         ->where('game_id', $gameId);

//     // Add date range filter if both dates are provided
//     if ($fromDate && $toDate) {
//         $query->whereBetween('created_at', [$fromDate, $toDate]);
//     }

//     // Execute the query with ordering, offset, and limit
//     $results = $query->orderBy('id', 'desc')
//                       ->offset($offset)
//                       ->limit($limit)
//                       ->get();

//     // Return the results in a JSON response
//     return response()->json([
//         'status' => 200,
//         'message' => 'Data found',
//         'data' => $results
//     ]);
// }

 public function results(Request $request)
{
    $validator = Validator::make($request->all(), [
        'game_id' => 'required',
        'limit' => 'required|integer',
    ]);

    $validator->stopOnFirstFailure();

    if ($validator->fails()) {
        return response()->json(['status' => 400, 'message' => $validator->errors()->first()]);
    }

    $game_id = $request->game_id;
    $limit = $request->limit;
    $offset = $request->offset ?? 0;
    $from_date = $request->from_date;
    $to_date = $request->to_date;
    $status = $request->status;

    // Build the query
    $query = BetResult::where('game_id', $game_id);

    if (!empty($from_date) && !empty($to_date)) {
        $query->whereBetween('created_at', [$from_date, $to_date]);
    }

    if (!empty($status)) {
        $query->where('status', $status);
    }

    // Retrieve the results with limit and offset
    $results = $query->orderBy('id', 'desc')
                     ->offset($offset)
                     ->limit($limit)
                     ->get();

    // Get the total count of bet_results for the game_id
    $total_result = BetResult::where('game_id', $game_id)->count();

    return response()->json([
        'status' => 200,
        'message' => 'Data found',
        'total_result' => $total_result,
        'data' => $results,
    ]);
}

//// last
public function lastFiveResults(Request $request)
{
    $validator = Validator::make($request->all(), [
        'game_id' => 'required',
        'limit' => 'required|integer'
    ]);

    $validator->stopOnFirstFailure();

    if ($validator->fails()) {
        return response()->json(['status' => 400, 'message' => $validator->errors()->first()]);
    }
    
    $game_id = $request->game_id;
    $limit = (int) $request->limit;
    $offset = (int) ($request->offset ?? 0);
    $from_date = $request->from_date;
    $to_date = $request->to_date;

    $query = BetResult::where('game_id', $game_id);

    // Apply date range filter if provided
    if ($from_date && $to_date) {
        $query->whereBetween('created_at', [$from_date, $to_date]);
    }

    // Fetch the results with limit and offset
    $results = $query
        ->orderBy('id', 'desc')
        ->offset($offset)
        ->limit($limit)
        ->get();

    return response()->json([
        'status' => 200,
        'message' => 'Data found',
        'data' => $results
    ]);
}

// last result ///
public function lastResults(Request $request)
{
    $validator = Validator::make($request->all(), [
        'game_id' => 'required',
    ]);

    $validator->stopOnFirstFailure();

    if ($validator->fails()) {
        return response()->json(['status' => 400, 'message' => $validator->errors()->first()]);
    }
    
    
    $game_id = $request->game_id;
    // $offset = (int) ($request->offset ?? 0);
    // $from_date = $request->from_date;
    // $to_date = $request->to_date;
    
    
    $results= BetResult::where('game_id', $game_id)->latest()->first();

    // $query = BetResult::where('game_id', $game_id);

    // // Apply date range filter if provided
    // if ($from_date && $to_date) {
    //     $query->whereBetween('created_at', [$from_date, $to_date]);
    // }

    // // Fetch the results with limit and offset
    // $results = $query
    //     ->orderBy('id', 'desc')
    //     ->offset($offset)
    //     ->limit(1)
    //     ->get();

    return response()->json([
        'status' => 200,
        'message' => 'Data found',
        'data' => $results
    ]);
}


public function bet_history(Request $request)
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

	public function cron_trycatcah($game_id)
{
    try {
        $per = DB::select("SELECT game_settings.winning_percentage as winning_percentage FROM game_settings WHERE game_settings.id = ?", [$game_id]);

        if (empty($per)) {
            return response()->json([
                'status' => 400,
                'message' => "Game settings not found for game_id: $game_id"
            ]);
        }

        $percentage = $per[0]->winning_percentage;

        $gameno = DB::select("SELECT * FROM betlogs WHERE game_id = ? LIMIT 1", [$game_id]);

        if (empty($gameno)) {
            return response()->json([
                'status' => 400,
                'message' => "No betlogs found for game_id: $game_id"
            ]);
        }

        $game_no = $gameno[0]->games_no;
        $period = $game_no;

        $sumamt = DB::select("SELECT SUM(amount) AS amount FROM bets WHERE game_id = ? AND games_no = ?", [$game_id, $game_no]);
        $totalamount = $sumamt[0]->amount ?? 0;

        $percentageamount = $totalamount * $percentage * 0.01;

        $lessamount = DB::select("SELECT * FROM betlogs WHERE game_id = ? AND games_no = ? AND amount <= ? ORDER BY amount ASC LIMIT 1", [$game_id, $game_no, $percentageamount]);

        if (count($lessamount) == 0) {
            $lessamount = DB::select("SELECT * FROM betlogs WHERE game_id = ? AND games_no = ? AND amount >= ? ORDER BY amount ASC LIMIT 1", [$game_id, $game_no, $percentageamount]);
        }

        $admin_winner = DB::select("SELECT * FROM admin_winner_results WHERE gamesno = ? AND gameId = ? ORDER BY id DESC LIMIT 1", [$game_no, $game_id]);

        $min_max = DB::select("SELECT MIN(number) as mins, MAX(number) as maxs FROM betlogs WHERE game_id = ?", [$game_id]);

        if (!empty($admin_winner)) {
            $number = $admin_winner[0]->number;
            $res = $number;
        } elseif ($totalamount < 450) {
            $res = rand($min_max[0]->mins, $min_max[0]->maxs);
        } elseif ($totalamount > 450 && !empty($lessamount)) {
            $res = $lessamount[0]->number;
        } else {
            return response()->json([
                'status' => 400,
                'message' => "Unable to determine result for game_id: $game_id"
            ]);
        }

        $result = $res;

        // Call game-wise result handlers
        if (in_array($game_id, [1, 2, 3, 4])) {
            $this->colour_prediction_and_bingo($game_id, $period, $result);
        } elseif ($game_id == 10) {
            $this->dragon_tiger($game_id, $period, $result);
        } elseif (in_array($game_id, [6, 7, 8, 9])) {
            $this->trx_new_nitish($game_id, $period, $result);
        } elseif ($game_id == 13) {
            $this->andarbaharpatta($game_id, $period, $result);
        } elseif ($game_id == 14) {
            $this->head_tail($game_id, $period, $result);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Result generated successfully',
            'data' => [
                'game_id' => $game_id,
                'period' => $period,
                'result' => $result
            ]
        ]);

    } catch (\Throwable $e) {
        \Log::error("Error in cron for game_id: $game_id", [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'status' => 500,
            'message' => 'Something went wrong. ' . $e->getMessage()
        ]);
    }
}
	
	 public function cron($game_id)
    {
              $per=DB::select("SELECT game_settings.winning_percentage as winning_percentage FROM game_settings WHERE game_settings.id=$game_id");
        $percentage = $per[0]->winning_percentage;  
		 //echo $percentage;die;

            $gameno=DB::select("SELECT * FROM betlogs WHERE game_id=$game_id LIMIT 1");
		// print_r($gameno);
            //
            
            ///
            $game_no=$gameno[0]->games_no;
             $period=$game_no;
            
				
            $sumamt=DB::select("SELECT SUM(amount) AS amount FROM bets WHERE game_id = '$game_id' && games_no='$game_no'");
//print_r($sumamt);die;

				
            $totalamount=$sumamt[0]->amount;
		
            $percentageamount = $totalamount*$percentage*0.01; 
			
            $lessamount=DB::select(" SELECT * FROM betlogs WHERE game_id = '$game_id'  && games_no='$game_no' && amount <= $percentageamount ORDER BY amount asc LIMIT 1 ");
		 
				if(count($lessamount)==0){
				$lessamount=DB::select(" SELECT * FROM betlogs WHERE game_id = '$game_id'  && games_no='$game_no' && amount >= '$percentageamount' ORDER BY amount asc LIMIT 1 ");
				}
		 
            $zeroamount=DB::select(" SELECT * FROM betlogs WHERE game_id =  '$game_id'  && games_no='$game_no' && amount=0 ORDER BY RAND() LIMIT 1 ");
		 
            $admin_winner=DB::select("SELECT * FROM admin_winner_results WHERE gamesno = '$game_no' AND gameId = '$game_id' ORDER BY id DESC LIMIT 1");
		 //
		 //dd($admin_winner);
            //  dd($admin_winner);
            $min_max=DB::select("SELECT min(number) as mins,max(number) as maxs FROM betlogs WHERE game_id=$game_id;");
		
        if(!empty($admin_winner)){
            echo 'a ';
            $number=$admin_winner[0]->number;
        }
      
        if (!empty($admin_winner)) {
            echo 'b ';
            $res=$number;
        } 
		 
         elseif ( $totalamount< 450) {
             echo 'c ';
            $res= rand($min_max[0]->mins, $min_max[0]->maxs);
        }elseif($totalamount > 450){
            echo 'd ';
            $res=$lessamount[0]->number;
        }
		 
        //$result=$number;
        $result=$res;
    
     
       //  $this->resultannounce($game_id,$period,$result);
				
				//$this->colour_prediction_and_bingo($game_id,$period,$result);
				// $this->trx($game_id,$period,$result);
				if ($game_id == 1 || $game_id == 2 || $game_id == 3 || $game_id == 4) {
					$ip=$_SERVER['REMOTE_ADDR'];
					
    $this->colour_prediction_and_bingo($game_id, $period, $result);
					
} elseif ($game_id == 10 ) {
    $this->dragon_tiger($game_id, $period, $result);
} elseif ($game_id == 6 || $game_id == 7 || $game_id == 8 || $game_id == 9) {
    $this->trx_new_nitish($game_id, $period, $result);
}elseif ($game_id == 13 ) {
    $this->andarbaharpatta($game_id, $period, $result);
}
elseif ($game_id == 14 ) {
    $this-> head_tail($game_id, $period, $result);
}

            
                
    }
	
	public function trx_new_nitish($game_id)
	{
		$gameno=DB::select("SELECT * FROM bets WHERE game_id=$game_id AND status=0 ORDER BY id DESC  LIMIT 1");
// 		 print_r($gameno); die;
            //
            
            ///
            $game_no=$gameno[0]->games_no;
		$url = "https://trx.apponrent.com/api/trx/results_by_periodno?period_no=$game_no&gameid=$game_id";
		//echo $url;

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
				$this->colour_prediction_and_bingo($game_id, $game_no, $win_number);
				//echo $win_number;die;
			}
		}
	}




private function colour_prediction_and_bingo($game_id, $period, $result)
{
    //echo"$game_id,$period,$res";
    // Fetch the colours associated with the given game_id and result
    $colours = VirtualGame::where('actual_number', $result)
        ->where('game_id', $game_id)
        ->where('multiplier', '!=', '1.5')
        ->pluck('name');
	//print_r($colours);die;
//dd($colours);
    // Convert the collection to JSON
    $pdata = json_encode($colours);
    //dd($pdata);
    // Insert the bet result
    BetResult::create([
        'number' => $result,
        'games_no' => $period,
        'game_id' => $game_id,
        'status' => 1,
        'json' => $pdata,
        'random_card' => $result
    ]);

    
    
    // Call the amount distribution method
    $this->amountdistributioncolors($game_id, $period, $result);
    // Update bet logs
    Betlog::where('game_id', $game_id)
        ->update(['amount' => 0, 'games_no' => \DB::raw('games_no + 1')]);

    return true;

}

	




private function generateRandomString($length = 4) {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    $maxIndex = strlen($characters) - 1;

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $maxIndex)];
    }
    return $randomString;
}




private function amountdistributioncolors($game_id, $period, $result)
{
    //echo"$game_id,$period,$res";
    // Fetch the virtual games based on criteria
    $virtualGames = VirtualGame::where('actual_number', $result)
        ->where('game_id', $game_id)
        ->where(function ($query) {
            $query->where('type', '!=', 1)->where('multiplier', '!=', '2') //1.5
                  ->orWhere(function ($query) {
                      $query->where('type', 1)->where('multiplier', '2');// 1.5
                  });
        })
        ->get();
	//print_r($virtualGames);die;
   // dd($virtualGames);
    foreach ($virtualGames as $winAmount) {
        $multiple = $winAmount->multiplier;
        $number = $winAmount->number;

        if (!empty($number)) {
            // Update bet for result '0'
            //dd($number);
            if ($result == '0') {
                //dd("hii");
                $test= Bet::where('games_no', $period)
                    ->where('game_id', $game_id)
                    ->where('number', $result)
                    ->update(['win_amount' => DB::raw('trade_amount * 9'), 'win_number' => '0', 'status' => 1]);
                   //dd($test); 
            }
              //dd("hello");
            // Update bets based on multiplier
			/*
           $test1= Bet::where('games_n', $period)
                ->where('game_id', $game_id)
                ->where('number', $number)
                ->update(['win_amount' => DB::raw("trade_amount * $multiple"), 'win_number' => $result, 'status' => 1]);
				*/
			
			$win_number=$result;
			
			/*
			DB::table('bets')
    ->where('games_no', $period)
    ->update([
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
                    CASE WHEN $win_number IN (SELECT actual_number FROM virtual_games WHERE game_id = 1 AND name='Big') THEN (trade_amount * (SELECT `multiplier` FROM `virtual_games` WHERE game_id=1 AND `number` IN(40,50))) ELSE 0.00 END
                WHEN number='50' THEN 
                    CASE WHEN $win_number IN (SELECT actual_number FROM virtual_games WHERE game_id = 1 AND name='SMALL') THEN (trade_amount * (SELECT `multiplier` FROM `virtual_games` WHERE game_id=1 AND `number` IN(40,50))) ELSE 0.00 END
                WHEN number='30' THEN 
                    CASE WHEN $win_number IN (SELECT actual_number FROM virtual_games WHERE game_id = 1 AND name='Red') THEN (trade_amount * $multiple) ELSE 0.00 END
                WHEN number='10' THEN 
                    CASE WHEN $win_number IN (SELECT actual_number FROM virtual_games WHERE game_id = 1 AND name='Green') THEN (trade_amount * $multiple) ELSE 0.00 END
                WHEN number='20' THEN 
                    CASE WHEN $win_number IN (0, 5) THEN (trade_amount * $multiple) ELSE 0.00 END
				WHEN number='$win_number' THEN (trade_amount * $multiple) 
                ELSE 0.00
            END
        ")
    ]);
			*/
		DB::table('bets')
    ->where('games_no', $period)
    ->update([
        'status' => DB::raw("
            CASE 
                WHEN number='40' THEN 
                    CASE WHEN EXISTS (SELECT 1 FROM virtual_games WHERE game_id = $game_id AND name='Big' AND actual_number = $win_number) THEN '1' ELSE '2' END
                WHEN number='50' THEN 
                    CASE WHEN EXISTS (SELECT 1 FROM virtual_games WHERE game_id = $game_id AND name='SMALL' AND actual_number = $win_number) THEN '1' ELSE '2' END
                WHEN number='30' THEN 
                    CASE WHEN EXISTS (SELECT 1 FROM virtual_games WHERE game_id = $game_id AND name='Red' AND actual_number = $win_number) THEN '1' ELSE '2' END
                WHEN number='10' THEN 
                    CASE WHEN EXISTS (SELECT 1 FROM virtual_games WHERE game_id = $game_id AND name='Green' AND actual_number = $win_number) THEN '1' ELSE '2' END
                WHEN number='20' THEN 
                    CASE WHEN $win_number IN (0, 5) THEN '1' ELSE '2' END
                WHEN number=$win_number THEN '1' 
                ELSE '2' 
            END
        "),
        'win_number' => $win_number,
        'win_amount' => DB::raw("
            CASE 
                WHEN number='40' THEN 
                    CASE WHEN EXISTS (SELECT 1 FROM virtual_games WHERE game_id = $game_id AND name='Big' AND actual_number = $win_number) 
                         THEN (trade_amount * (SELECT `multiplier` FROM `virtual_games` WHERE game_id=$game_id AND `number` IN(40,50) LIMIT 1)) 
                         ELSE 0.00 END
                WHEN number='50' THEN 
                    CASE WHEN EXISTS (SELECT 1 FROM virtual_games WHERE game_id = $game_id AND name='SMALL' AND actual_number = $win_number) 
                         THEN (trade_amount * (SELECT `multiplier` FROM `virtual_games` WHERE game_id=$game_id AND `number` IN(40,50) LIMIT 1)) 
                         ELSE 0.00 END
                WHEN number='30' THEN 
                    CASE WHEN EXISTS (SELECT 1 FROM virtual_games WHERE game_id = $game_id AND name='Red' AND actual_number = $win_number) 
                         THEN (trade_amount * (SELECT `multiplier` FROM `virtual_games` WHERE game_id=$game_id AND `number` = 30 LIMIT 1)) ELSE 0.00 END
                WHEN number='10' THEN 
                    CASE WHEN EXISTS (SELECT 1 FROM virtual_games WHERE game_id = $game_id AND name='Green' AND actual_number = $win_number) 
                         THEN (trade_amount * (SELECT `multiplier` FROM `virtual_games` WHERE game_id=$game_id AND `number` =10 LIMIT 1)) ELSE 0.00 END
                WHEN number='20' THEN 
                    CASE WHEN $win_number IN (0, 5) THEN (trade_amount * (SELECT `multiplier` FROM `virtual_games` WHERE game_id=$game_id AND `number` =20 LIMIT 1)) ELSE 0.00 END
                WHEN number=$win_number THEN (trade_amount * (SELECT `multiplier` FROM `virtual_games` WHERE game_id=$game_id AND `number` =$win_number LIMIT 1)) 
                ELSE 0.00
            END
        ")
    ]);

			
	//DB::table('bets')->where('games_no', "$period")->update(['win_amount' => DB::raw("(CASE WHEN status='1' THEN (trade_amount * $multiple) ELSE '0.00' END)")]);
			
			
			//print
			
			
			
			
			
        }
    }

    // Update users' wallets based on the winning amounts
    $winningBets = Bet::where('win_number', '>=', 0)
        ->where('games_no', $period)
        ->where('game_id', $game_id)
        ->get();

    foreach ($winningBets as $bet) {
        $amount = $bet->win_amount;
        $userId = $bet->userid;

      	$amount = (float) $amount;
		 // Calculate 4% tax deduction
    $taxDeduction = $amount * 0.04;  // 4% tax
    $finalAmount = $amount - $taxDeduction;  // Final amount after tax deduction

User::where('id', $userId)
    ->update([
        'wallet' => DB::raw("wallet + {$finalAmount}"),
            'winning_wallet' => DB::raw("winning_wallet + {$finalAmount}"),
            'updated_at' => now()
    ]); 
		///jilli///
		
		//$add_jili = jilli::add_in_jilli_wallet($userId, $finalAmount);

		
		///end jilli////


    }

    // Update bets with no winning amount
	/*
    Bet::where('games_n', $period)
        ->where('game_id', $game_id)
        ->where('status', 0)
        ->where('win_amount', 0)
        ->update(['status' => 2, 'win_number' => $result]);
		*/
	
}



}
