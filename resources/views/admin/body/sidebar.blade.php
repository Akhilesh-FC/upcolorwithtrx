
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    /* Sidebar UI improvements */
    #sidebar .components li > a {
      display: flex;
      align-items: center;
      padding: 10px 15px;
       color: #fff;
      text-decoration: none;
    }
    #sidebar .components li > a i {
      margin-right: 10px;
      min-width: 20px;
    }
    #sidebar .components ul {
      padding-left: 30px;
      background: #f9f9f9;
    }
    .collapse:not(.show) { display: none; }
    .collapse.show { display: block; }
  </style>

<div class="full_container">
  <div class="inner_container">
    <!-- Sidebar -->
    <nav id="sidebar">
      <div class="sidebar_blog_1">
        <div class="sidebar-header">
          <div class="logo_section">
            <a href="index.html"><img class="logo_icon img-responsive" src="images/logo/logo_icon.png" alt="Logo" /></a>
          </div>
        </div>
        <div class="sidebar_user_info">
          <div class="icon_setting"></div>
          <div class="user_profle_side">
            <div class="user_img">
              <img class="img-responsive" src="https://trx.apponrent.com/public/images/layout_img/user_img.jpg" alt="User">
            </div>
            <div class="user_info">
              <h6>Admin</h6>
              <p><span class="online_animation"></span> Online</p>
            </div>
          </div>
        </div>
      </div>
      <div class="sidebar_blog_2">
        <h4>General</h4>
        <ul class="list-unstyled components">
          <!-- Dashboard -->
          <li><a href="{{route('dashboard')}}"><i class="fa fa-dashboard yellow_color"></i> <span>Dashboard</span></a></li>
          <!-- Attendance -->
          <li><a href="{{route('attendance.index')}}"><i class="fa fa-clock-o purple_color2"></i> <span>Attendance</span></a></li>
          <!-- Players -->
          <li><a href="{{route('users')}}"><i class="fa fa-user orange_color"></i> <span>Players</span></a></li>
          
          <!--<li><a href="{{route('admin.illegalUsers')}}"><i class="fa fa-user orange_color"></i> <span>Illegal User Bet</span></a></li>-->
          
          
          <!--<li><a href="{{route('register.create')}}"><i class="fa fa-user orange_color"></i> <span>System User</span></a></li>-->
        
          <!-- MLM Levels -->
          <li><a href="{{route('mlmlevel')}}"><i class="fa fa-list red_color"></i> <span>MLM Levels</span></a></li>
  
            
         @php
    $firstPart = DB::select("SELECT * FROM `game_settings` LIMIT 4");
    // id = 1 waale record ko find karo
    $recordWithId1 = collect($firstPart)->firstWhere('id', 1);
@endphp

@if($recordWithId1)
    <li>
        <a href="{{ route('colour_prediction', $recordWithId1->id) }}">
            <i class="fa fa-list red_color"></i>
            <span>Colour Prediction</span>
        </a>
    </li>
@endif

          <!-- Colour Prediction -->
         

          <!-- Chicken Road Game -->
          <!--<li>-->
          <!--  <a href="#apps1" data-bs-toggle="collapse" data-bs-target="#apps1" aria-expanded="false" class="dropdown-toggle">-->
          <!--    <i class="fa fa-gamepad dark_color"></i><span>Chicken Road Game</span>-->
          <!--  </a>-->
          <!--  <ul class="collapse list-unstyled" id="apps1">-->
          <!--   <li class="{{ Request::is('multiplier') ? 'active' : '' }}">-->
          <!--    <a href="{{ url('multiplier') }}">-->
          <!--      <i class="fas fa-percentage"></i> <span>Multiplier</span>-->
          <!--    </a>-->
          <!--  </li>-->
          <!--   <li class="{{ Request::is('bet') ? 'active' : '' }}">-->
          <!--        <a href="{{ url('bet') }}">-->
          <!--          <i class="fas fa-dice"></i> <span>Bet History</span>-->
          <!--        </a>-->
          <!--      </li>-->
          <!--     <li class="{{ Request::is('betValues') ? 'active' : '' }}">-->
          <!--        <a href="{{ route('betValues') }}">-->
          <!--          <i class="fas fa-star"></i> <span>Bet Values</span>-->
          <!--        </a>-->
          <!--      </li>-->
          <!--   <li class="{{ Request::routeIs('amountSetup') ? 'active' : '' }}">-->
          <!--        <a href="{{ route('amountSetup') }}">-->
          <!--          <i class="fas fa-rupee-sign"></i> <span>Amount Setup</span>-->
          <!--        </a>-->
          <!--      </li>-->
          <!--  </ul>-->
          <!--</li>-->

          <!--@includeIf('admin.body.aviator_sidebar')-->
           <li><a href="{{route('result' , 5)}}"><i class="fa fa-list red_color"></i> <span>Aviator Game</span></a></li>
  

          @php
            $game_id = DB::select("SELECT * FROM `game_settings` where status=0 LIMIT 5;");
          @endphp

          <!-- Bet History -->
          <li>
            <a href="#apps-xy" data-bs-toggle="collapse" data-bs-target="#apps-xy" aria-expanded="false" class="dropdown-toggle">
              <i class="fa fa-object-group blue2_color"></i><span>Bet History</span>
            </a>
            <ul class="collapse list-unstyled" id="apps-xy">
              @foreach($game_id as $itemm)
                <li><a href="{{route('all_bet_history',$itemm->id)}}">
                  <i class="fa fa-history me-2"></i><span>{{$itemm->name}}</span>
                </a></li>
              @endforeach
            </ul>
          </li>

          <!-- Offer -->
          <li><a href="{{route('offer')}}"><i class="fa fa-bullhorn dark_color"></i> <span>Offer</span></a></li>
          <!-- Gift -->
          <li><a href="{{route('gift')}}"><i class="fa fa-gift dark_color"></i> <span>Gift</span></a></li>
          <!-- Gift Redeemed History -->
          <li><a href="{{route('giftredeemed')}}"><i class="fa fa-credit-card dark_color"></i> <span>Gift Redeemed History</span></a></li>
          <!-- Activity & Banner -->
          <li><a href="{{route('banner')}}"><i class="fa fa-picture-o dark_color"></i> <span>Activity & Banner</span></a></li>
          <!-- Feedback -->
          <li><a href="{{route('feedback')}}"><i class="fa fa-file blue1_color"></i> <span>Feedback</span></a></li>

          <!-- Deposit -->
          <li>
            <a href="#app13" data-bs-toggle="collapse" data-bs-target="#app13" aria-expanded="false" class="dropdown-toggle">
              <i class="fa fa-tasks green_color"></i><span>Deposit</span>
            </a>
            <ul class="collapse list-unstyled" id="app13">
              <li><a href="{{ route('deposit', 1) }}">Pending</a></li>
              <li><a href="{{ route('deposit', 2) }}">Success</a></li>
              <li><a href="{{ route('deposit', 3) }}">Reject</a></li>
            </ul>
          </li>

          <!-- Withdrawal -->
          <li>
            <a href="#app11" data-bs-toggle="collapse" data-bs-target="#app11" aria-expanded="false" class="dropdown-toggle">
              <i class="fa fa-wrench purple_color2"></i><span>Withdrawal</span>
            </a>
            <ul class="collapse list-unstyled" id="app11">
              <li><a href="{{ route('widthdrawl', 1) }}">Pending</a></li>
              <li><a href="{{ route('widthdrawl', 2) }}">Approved</a></li>
              <li><a href="{{ route('widthdrawl', 3) }}">Reject</a></li>
            </ul>
          </li>

          <!-- USDT QR Code -->
          <li><a href="{{route('usdtqr')}}"><i class="fa fa-table purple_color2"></i> <span>USDT QR Code</span></a></li>

          <!-- USDT Deposit -->
          <li>
            <a href="#app20" data-bs-toggle="collapse" data-bs-target="#app20" aria-expanded="false" class="dropdown-toggle">
              <i class="fa fa-tasks green_color"></i><span>USDT Deposit</span>
            </a>
            <ul class="collapse list-unstyled" id="app20">
              <li><a href="{{ route('usdt_deposit', 1) }}">Pending</a></li>
              <li><a href="{{ route('usdt_deposit', 2) }}">Success</a></li>
              <li><a href="{{ route('usdt_deposit', 3) }}">Reject</a></li>
            </ul>
          </li>

          <!-- USDT Withdrawal -->
          <li>
            <a href="#app21" data-bs-toggle="collapse" data-bs-target="#app21" aria-expanded="false" class="dropdown-toggle">
              <i class="fa fa-wrench purple_color2"></i><span>USDT Withdrawal</span>
            </a>
            <ul class="collapse list-unstyled" id="app21">
              <li><a href="{{ route('usdt_widthdrawl', 1) }}">Pending</a></li>
              <li><a href="{{ route('usdt_widthdrawl', 2) }}">Success</a></li>
              <li><a href="{{ route('usdt_widthdrawl', 3) }}">Reject</a></li>
            </ul>
          </li>

          <!-- Notice -->
          <li><a href="{{route('notification')}}"><i class="fa fa-bell yellow_color"></i> <span>Notice</span></a></li>
          <!-- Setting -->
          <li><a href="{{route('setting')}}"><i class="fa fa-cogs dark_color"></i> <span>Setting</span></a></li>
          <!-- Support Setting -->
          <li><a href="{{route('support_setting')}}"><i class="fa fa-info-circle yellow_color"></i> <span>Support Setting</span></a></li>
          <!-- Change Password -->
          <li><a href="{{route('change_password')}}"><i class="fa fa-warning red_color"></i> <span>Change Password</span></a></li>
          <!-- Logout -->
          <li><a href="{{route('auth.logout')}}"><i class="fa fa-sign-out-alt yellow_color"></i> <span>Logout</span></a></li>

        </ul>
      </div>
    </nav>
    <!-- end sidebar -->
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
