<?

namespace crm\controllers;

use php\XMLModel;
use php\Hashtable;

use crm\CRMController;
use crm\factories\Managers;

/**
 *
 * @package crm
 * @subpackage controllers
 */
class IndexController extends CRMController {

	/**
	 * @app
	 *
	 * @param $appNode
	 *
	 * @return string
	 */

	public function defaultAction( XMLModel $appNode, Hashtable $args){
		$appNode->addChild('menus');
		
		$menus = Managers::MainMenuManager()->getMenus();

		foreach($menus as $menuIndex => $menuValue){
			$menuNode = $appNode->menus->addChild('menu');
			$menuNode['name'] = utf8_encode($menuValue['nombre']);
			$menuNode['url'] = utf8_encode($menuValue['url']);
			$menuNode['icono'] = utf8_encode($menuValue['icono']);
			if( is_array($menuValue) && !empty($menuValue['submenus'])  ){ //Opcion con subopciones
				$subMenuNode = $menuNode->addChild('submenu');
				$subMenuNode['nombre'] = utf8_encode($menuValue['nombre']);
				$subMenuNode['url'] = utf8_encode($menuValue['url']);
				$subMenuNode['icono'] = utf8_encode($menuValue['icono']);
				foreach($menuValue['submenus'] as $submenuIndex => $submenuValue){
					$suboptionNode = $subMenuNode->addChild('menu');
					$suboptionNode['name'] = utf8_encode($submenuValue['nombre']);
					$suboptionNode['url'] = utf8_encode($submenuValue['url']);
					$suboptionNode['icono'] = utf8_encode($submenuValue['icono']);
				}
			}
		}

		return "main";
	}
}
?>
