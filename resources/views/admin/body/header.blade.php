 <?php if(Session::has('id')){

}else{
	
	header("Location: https://trx.apponrent.com/");
            die; 

} 
      
?>
<div id="content">
    <!-- topbar -->
    <div class="topbar">
       <nav class="navbar navbar-expand-lg navbar-light">
          <div class="full">
             <button type="button" id="sidebarCollapse" class="sidebar_toggle"><i class="fa fa-bars"></i></button>
             <div class="logo_section">
                <h3 class="img-responsive text-white mt-2 ml-2" ><?php echo data($conn,4)?></h3>
                {{-- <a href="index.html"><img class="img-responsive" src="https://trx.apponrent.com/public/images/layout_img/user_img.jpg" alt="#" /></a> --}}
             </div>
             <div class="right_topbar">
                <div class="icon_info">
                   {{-- <ul>
                      <li><a href="#"><i class="fa fa-bell-o"></i><span class="badge">2</span></a></li>
                      <li><a href="#"><i class="fa fa-question-circle"></i></a></li>
                      <li><a href="#"><i class="fa fa-envelope-o"></i><span class="badge">3</span></a></li>
                   </ul> --}}
                   <ul class="user_profile_dd">
                      <li>
                         <a class="dropdown-toggle" data-toggle="dropdown"><img class="img-responsive rounded-circle" src="https://trx.apponrent.com/public/images/layout_img/user_img.jpg" alt="#" /><span class="name_user">Admin</span></a>
                         <div class="dropdown-menu">
                            <!--<a class="dropdown-item" href="#">My Profile</a>-->
                            {{-- <a class="dropdown-item" href="settings.html">Settings</a>
                            <a class="dropdown-item" href="help.html">Help</a> --}}
                            <a class="dropdown-item" href="{{route('auth.logout')}}"><span>Log Out</span> <i class="fa fa-sign-out"></i></a>
                         </div>
                      </li>
                   </ul>
                </div>
             </div>
          </div>
       </nav>
    </div>
    <!-- end topbar -->
<!-- jQuery + Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>


 