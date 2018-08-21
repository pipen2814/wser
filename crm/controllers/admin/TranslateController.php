<?

namespace crm\controllers\admin;

use php\Hashtable;
use crm\CRMController;
use crm\orm\Trads;
use php\XMLModel;
use php\util\Paginator;

/**
 * Gestiona el admin de traducciones
 *
 * @package crm
 * @subpackage controllers
 * @subpackage admin
 */
class TranslateController extends CRMController  {

	/**
	 * Muestra todas las traducciones de la aplicacion
	 *
	 * @app
	 * @param XMLModel $appNode Modelo
	 * @param Hastable $args Parametros 
	 * @return string Vista del controlador
	 */
	public function defaultAction( XMLModel $appNode, Hashtable $args) {

		$orm = new Trads();
		$trads = $orm->getAll();

		$p = new Paginator( $appNode, $trads );
		$p->addHiddenFields(array('fecha_hora'));
		$p->modifiedField('txt',null,array('class' => 'dInput','onclick'=>"updateTxt('\$dominio','\$clave');"));
		$p->run();

	}

	/**
	 * Busca traducciones segun parametros
	 *
	 * @app
	 * @param XMLModel $appNode Modelo
	 * @param Hastable $args Parametros 
	 * @return string Vista del controlador
	 */
	public function searchAction(XMLModel $appNode, Hashtable $args) {

		$orm = new Trads();
		$trads = $orm->searchLike($args->busqueda);
		$req = $appNode->addChild('request');
		$req['string'] = $args->busqueda;

		$p = new Paginator( $appNode, $trads );
		$p->addHiddenFields(array('fecha_hora'));
		$p->run();

	}

	/**
	 * Crea una traduccion
	 *
	 * @app
	 * @param XMLModel $appNode Modelo
	 * @param Hastable $args Parametros 
	 * @return string Vista del controlador
	 */
	public function createAction(XMLModel $appNode, Hashtable $args) {

		$orm = new Trads();
		$orm = $orm->getByPK($args->domain,$args->key);

		if(!is_null($orm)) {
			$this->addError($appNode,"No se ha podido crear la traducción ya que existe otra con ese dominio y clave");
		} else {
			// Crear traduccion
			$orm = new Trads();
			$orm->dominio = $args->domain;
			$orm->clave = $args->key;
			$orm->txt = $this->decode($args->txt);
			$orm->save();
			$this->addSuccess($appNode,"La traducción ha sido creada correctamente");
		}
		
		$this->defaultAction($appNode, $args);
	}

	/**
	 * Actualiza el texto de una traduccion
	 *
	 * @app
	 * @param XMLModel $appNode Modelo
	 * @param Hastable $args Parametros 
	 * @return string Vista del controlador
	 */
	public function updateAction(XMLModel $appNode, Hashtable $args) {


	}
	

}
?>
