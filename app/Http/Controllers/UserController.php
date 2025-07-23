<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use DB;
use Illuminate\Support\Str;
use App\Models\All_image;

use Illuminate\Support\Facades\URL;


class UserController extends Controller
{
    
    // app/Http/Controllers/AdminController.php
public function illegalUsers()
{
    $illegalUsers = User::where('illegal_count', '>', 0)->get();

    return view('user.illegal_bet', compact('illegalUsers'));
}

 public function illegal_user_active(Request $request,$id)
    {
		$value = $request->session()->has('id');
	
        if(!empty($value))
        {
    //   Order::where("id",$id)->update(['status'=>0]);
    DB::update("UPDATE `users` SET `status`='1' WHERE id=$id;");
        
        return redirect()->route('admin.illegalUsers');
			  }
        else
        {
           return redirect()->route('login');  
        }
    }
	
    public function illegal_user_inactive(Request $request,$id)
  {
		$value = $request->session()->has('id');
	
        if(!empty($value))
        {
    //   Order::where("id",$id)->update(['status'=>1]);
      DB::update("UPDATE `users` SET `status`='0' WHERE id=$id;");
        return redirect()->route('admin.illegalUsers');
			  }
        else
        {
           return redirect()->route('login');  
        }
  }

    public function user_create(Request $request)
    {
		$value = $request->session()->has('id');
	
        if(!empty($value))
        {

			$users = DB::select("
    SELECT e.*, m.username AS sname 
    FROM users e 
    LEFT JOIN users m ON e.referral_user_id = m.id 
    WHERE e.account_type = 0
");

		//$users = DB::table('user')->latest()->get();
        
        return view ('user.index', compact('users'));
        }
        else
        {
           return redirect()->route('login');  
        }
        
    }
	
    public function user_details(Request $request,$id)
    {
		$value = $request->session()->has('id');
	
        if(!empty($value))
        {
        $users = DB::select("SELECT * FROM `bets` WHERE `userid`='$id' ");
        $withdrawal = DB::select("SELECT * FROM `withdraw_histories` WHERE `user_id`='$id' ");
        $dipositess = DB::select("SELECT * FROM `payins` WHERE `user_id`='$id' ");
       return view ('user.user_detail',compact('dipositess','users','withdrawal')); 
			  }
        else
        {
           return redirect()->route('login');  
        }
    }

    public function user_active(Request $request,$id)
    {
		$value = $request->session()->has('id');
	
        if(!empty($value))
        {
    //   Order::where("id",$id)->update(['status'=>0]);
    DB::update("UPDATE `users` SET `status`='1' WHERE id=$id;");
        
        return redirect()->route('users');
			  }
        else
        {
           return redirect()->route('login');  
        }
    }
	
    public function user_inactive(Request $request,$id)
  {
		$value = $request->session()->has('id');
	
        if(!empty($value))
        {
    //   Order::where("id",$id)->update(['status'=>1]);
      DB::update("UPDATE `users` SET `status`='0' WHERE id=$id;");
        return redirect()->route('users');
			  }
        else
        {
           return redirect()->route('login');  
        }
  }

	 public function password_update(Request $request, $id)
      {
		 $value = $request->session()->has('id');
	
        if(!empty($value))
        {
        $password=$request->password;
               $data= DB::update("UPDATE `users` SET `password`='$password' WHERE id=$id");
         
             return redirect()->route('users')->with('success', 'Password updated successfully!');
			  }
        else
        {
           return redirect()->route('login');  
        }
          
      }
      	public function refer_id_store(Request $request ,$id)
    {
		date_default_timezone_set('Asia/Kolkata');
		$date=date('Y-m-d H:i:s');
		$value = $request->session()->has('id');
	
        if(!empty($value))
        {
      $refer=$request->referral_user_id;
     //dd($wallet);
         $data = DB::update("UPDATE `users` SET `referral_user_id` = $refer WHERE id = $id;");
			
             return redirect()->route('users');
			  }
        else
        {
           return redirect()->route('login');  
        }
      }
	
	public function wallet_store(Request $request ,$id)
    {
		date_default_timezone_set('Asia/Kolkata');
		$date=date('Y-m-d H:i:s');
		$value = $request->session()->has('id');
	
        if(!empty($value))
        {
      $wallet=$request->wallet;
     //dd($wallet);
         $data = DB::update("UPDATE `users` SET `wallet` = `wallet` + $wallet,`deposit_balance`=`deposit_balance`+$wallet,`total_payin`=`total_payin`+$wallet WHERE id = $id;");
			$insert=DB::insert("INSERT INTO `payins`(`user_id`, `cash`, `order_id`, `type`, `status`,`created_at`) VALUES ('$id','$wallet','via Admin','2','2','$date')");
		
             return redirect()->route('users');
			  }
        else
        {
           return redirect()->route('login');  
        }
      }
	public function wallet_subtract(Request $request, $id)
{
    date_default_timezone_set('Asia/Kolkata');
    $ammount = $request->wallet;

    // Check if the request has a wallet amount
    if ($request->has('wallet')) {
        // Retrieve the user using Eloquent
        $user = User::find($id);

        // Check if user exists
        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        // Check if the wallet amount is sufficient
        if ($user->wallet < $ammount) {
            return redirect()->back()->with('error', 'Insufficient wallet balance.');
        }

        // Subtract the amount from the wallet
        $user->wallet -= $ammount;
        $user->save();

        return redirect()->route('users')->with('success', 'Amount subtracted successfully!');
    }

    return redirect()->back()->with('error', 'No amount specified.');
}

		public function user_mlm(Request $request,$id)
    {
			
$value = $request->session()->has('id');
	
        if(!empty($value))
        {

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://mahajong.club/admin/index.php/Mahajongapi/level_getuserbyrefid?id=$id",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Cookie: ci_session=itqv6s6aqactjb49n7ui88vf7o00ccrf'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
$data= json_decode($response);

             return view ('user.mlm_user_view')->with('data', $data);
			
			  }
        else
        {
           return redirect()->route('login');  
        }
      }
      
      
    public function registerwithref($id){
         
         $ref_id = User::where('referral_code',$id)->first();
        //  $country=DB::select("SELECT `phone_code` FROM `country` WHERE 1;");
        $country = DB::table("country")->select("phone_code")->get();
       
         return view('user.newregister')->with('ref_id',$ref_id)->with('country',$country);
         
     }
     
      protected function generateRandomUID() {
					$alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
					$digits = '0123456789';

					$uid = '';

					// Generate first 4 alphabets
					for ($i = 0; $i < 4; $i++) {
						$uid .= $alphabet[rand(0, strlen($alphabet) - 1)];
					}

					// Generate next 4 digits
					for ($i = 0; $i < 4; $i++) {
						$uid .= $digits[rand(0, strlen($digits) - 1)];
					}

					return $this->check_exist_memid($uid);
					
				}

	  protected function check_exist_memid($uid){
					$check = DB::table('users')->where('u_id',$uid)->first();
					if($check){
						return $this->generateRandomUID(); // Call the function using $this->
					} else {
						return $uid;
					}
				}
      
        public function register_store(Request $request,$referral_code)
      {
          $validatedData = $request->validate([
            'mobile' => 'required|unique:users,mobile|regex:/^\d{10}$/',
            'password' => 'required',
            'email' => 'required|unique:users,email',
        ]);
          //dd($ref_id);

       $refer = DB::table('users')->where('referral_code', $referral_code)->first();
	 	if ($refer !== null) {
			$referral_user_id = $refer->id;

    // $username = Str::upper(Str::random(6, 'alpha'));
    $username = Str::random(6, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ');
	    $u_id = $this->generateRandomUID();
	     
	     $referral_code = Str::upper(Str::random(6, 'alpha'));
	     
	      $rrand = rand(1,20);
          $all_image = All_image::find($rrand);
          
    $image = $all_image->image;
	
    $userId = DB::table('users')->insertGetId([
        'mobile' => $request->mobile,
        'email' => $request->email,
        'country_code' => '+91',
        'username' => $username,
        'password' =>$request->password,
        'referral_user_id' =>$referral_user_id,
        'referral_code' => $referral_code,
		'u_id' => $u_id,
		'status' => 1,
		'userimage' => $image,
    ]);
  // $refid= isset($referral_user_id)? $referral_user_id : '8';
     DB::select("UPDATE `users` SET `yesterday_register`=yesterday_register+1 WHERE `id`=$referral_user_id");
	
return redirect(str_replace('https://admin.', 'http://', "https://upcolor.live/"));

		
}
}

     public function updatereferral(Request $request, $id){
         //dd($request->all(), $id );
        $request->validate([
            'referral_user_id' => 'required|string|max:255',
        ]);

        DB::table('users')
            ->where('id', $id)
            ->update(['referral_user_id' => $request->input('referral_user_id')]);
        return redirect()->back()->with('success', 'Sponser ID updated successfully!');
    }
    
    
    ///// Demo User //////
    // public function create()
    // {
    //     return view('user.demo_user');
    // }
    public function demoUser(Request $request)
{
    if ($request->session()->has('id')) {
        $demo_users = DB::select(" SELECT * FROM `users` WHERE `account_type`=1");

        return view('user.demo_user', compact('demo_users'));
    } else {
        return redirect()->route('login');
    }
}



private function generateNumericCode($length = 13) {
    $code = '';
    for ($i = 0; $i < $length; $i++) {
        $code .= random_int(0, 9);
    }
    return $code;
}

private function NumericCode($length = 8) {
    $code = '';
    for ($i = 0; $i < $length; $i++) {
        $code .= random_int(0, 9);
    }
    return $code;
}

   
public function store(Request $request)
{
    // Step 1: Validate Request Data
    $request->validate([
        'email' => 'required|email|unique:users,email',
        'mobile' => 'required',
        'password' => 'required|min:6',
    ]);
    
    // Step 2: Generate Required Data
    $randomName = 'User_' . strtoupper(Str::random(5));
    $randomReferralCode = $this->generateNumericCode(13);
    $baseUrl = URL::to('/');
    $uid = $this->NumericCode(8);
    $randomNumber = rand(1, 20);

    // Step 3: Prepare User Data
    $data = [
        'username' => $randomName,
        'u_id' => $uid,
        'mobile' => $request->mobile,
        'email' => $request->email,
        'password' => $request->password,
        'userimage' => $baseUrl . "/uploads/profileimage/" . $randomNumber . ".png",
        'status' => 1,
        'referral_code' => $randomReferralCode,
        'wallet' => 0,
        'account_type'=>1,
        'country_code' => $request->country_code ?? '', // Default to empty if not provided
        'created_at' => now(),
        'updated_at' => now(),
    ];

    // Step 4: Add Referrer
    if ($request->filled('referral_code')) {
        $referrer = DB::table('users')->where('referral_code', $request->referral_code)->first();
        $data['referral_user_id'] = $referrer ? $referrer->id : null;
    } else {
        $data['referral_user_id'] = 1;
    }

    // Step 5: Store User Data
    DB::table('users')->insert($data);

    // Step 6: Redirect with Success Message
    return redirect()->route('register.create')->with('success', 'User registered successfully!');
}

 
      
}