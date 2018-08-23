<?php

namespace crm\managers;

use php\Hashtable;
use php\managers\BaseManager;
use php\sql\DB;
use php\session;

/**
 * Class ContactManager
 *
 * Manager de contactos.
 *
 * @package crm
 * @subpackage managers
 */
class MainMenuManager extends BaseManager {

	/**
	 * Metodo para pintar el menu en funcion del perfil y permisos del usuario conectado.
	 *
	 */
	public static function getMenus(){
		$db = DB::getInstance();
		$db->query("select * from menus order by id_menu_padre, orden");

		$rs = $db->getRs();
		$menus = array();
		while($menu = $rs->next()){
			if($menu->id_menu_padre == 0){
				$menus[$menu->id_menu] = array('nombre' => $menu->menu, 'url' => $menu->url, 'icono'=> $menu->icono, 'submenus' => array());
			}else{
				if(!isset($menus[$menu->id_menu_padre]['submenus'][$menu->id_menu])){
					$menus[$menu->id_menu_padre]['submenus'][$menu->id_menu] = array();
				}
				$menus[$menu->id_menu_padre]['submenus'][$menu->id_menu] = array('nombre' => $menu->menu, 'url' => $menu->url, 'icono'=> $menu->icono);
			}
		}

		return $menus;
	}
}
?>
