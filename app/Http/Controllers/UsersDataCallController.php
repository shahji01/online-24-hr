<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Auth;
use DB;
use Config;
use App\Models\MainMenuTitle;
use App\Models\Menu;
use Input;

class UsersDataCallController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
   	public function viewMainMenuTitleList(){
		$MainMenuTitles = new MainMenuTitle;
		$MainMenuTitles = $MainMenuTitles::where('status', '=', '1')->get();
		$counter = 1;
		foreach($MainMenuTitles as $row){
	?>
		<tr>
			<td class="text-center"><?php echo $counter++;?></td>
			<td><?php echo $row['main_menu_id'];?></td>
			<td><?php echo $row['title'];?></td>
			<td></td>
		</tr>
	<?php
		}
	}
	
	public function viewSubMenuList(){
		$Menus = new Menu;
		$Menus = $Menus::where('status', '=', '1')->get();
		$counter = 1;
		foreach($Menus as $row){
	?>
		<tr>
			<td class="text-center"><?php echo $counter++;?></td>
			<td><?php echo $row['m_main_title'];?></td>
			<td><?php echo $row['name'];?></td>

            <td>
               <ul aria-labelledby="menu1" class="nav">
                  <li role="presentation" class="btn btn-sm btn-info" onclick="showDetailModelFourParamerter('users/editSubMenuDetailForm','<?php echo $row->id;?>','Edit Sub menu Detail',<?php echo Input::get('m') ?>)">
                      <a>
                          <i class="fas fa-edit fa"></i>
                      </a>
                  </li>&nbsp;
                   <li role="presentation" class=" btn btn-sm btn-danger" onclick="deleteRowMasterTable('<?php echo $row->id;?>','Menu')">
                       <a>
                           <i class="fas fa-trash fa"></i>
                       </a>
                   </li>
               </ul>
            </td>
        </tr>
	<?php
		}
	}
}
