<?

namespace crm\controllers\admin;

use crm\CRMController;
use php\Hashtable;
use php\XMLModel;

/**
 *
 * @package crm
 * @subpackage controllers
 * @subpackage admin
 */
class AdminController extends CRMController  {

	/**
	 * Muestra el admin de la aplicación
	 *
	 * @app
	 *
	 * @param XMLModel $appNode Modelo
	 * @param Hastable $args Parametros 
	 * @return string Vista del controlador
	 */
	public function defaultAction(XMLModel $appNode, Hashtable $args) {

	}

}
?>
