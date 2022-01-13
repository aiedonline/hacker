
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="">
        <div class="sidebar-brand-icon rotate-n-15">
		
		
          		<i class="fas <?php  print($CONFIG->ui->fa);  ?>"></i>
			
        </div>
		 
        		<div class="sidebar-brand-text mx-3"><?php  print($CONFIG->ui->name);  ?></div>
		 
      </a>
      <!-- Divider -->
      <hr class="sidebar-divider my-0">
      <!-- Nav Item - Dashboard -->
    <?php
		if(session_exist('user_cookie')) { 
				$MENU = json_decode(file_get_contents(dirname(dirname(__FILE__)). "/data/menu.json"));
				for($i = 0; $i < count($MENU); $i++){
					if(array_key_exists("perfil", $MENU[$i]) &&  !posso($MENU[$i]->perfil)){
						continue;
					}
					print_r('<li class="nav-item active">        <a class="nav-link" href="'. $MENU[$i]->link .'">          <i class="fas '. $MENU[$i]->fa .'"></i>          <span>'. $MENU[$i]->text .'</span></a> </li>');
				}
				#print_r('<li class="nav-item active">        <a class="nav-link" href="/' . $CONFIG->ui->sigla . '/pages/admin/config.php">          <i class="fas fa-fw fa-users-cog"></i>          <span>Administração</span></a> </li>');
		}
	?> 

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>