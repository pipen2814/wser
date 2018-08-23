<?

namespace crm\controllers\invoice;

use php\XMLModel;
use php\Hashtable;
use php\util\Paginator;

use crm\CRMController;
use crm\orm\Catalogos;
use crm\orm\Servicios;

/**
 *
 * @package crm
 * @subpackage controllers
 * @subpackage invoice
 */
class CatalogController extends CRMController {

	/**
	 * Visualizacion de todos los catalogos activos
	 *
	 * @app
	 * @param XMLModel $appNode Modelo
	 * @param Hashtable $args Parametros 
	 *
	 * @return string Nombre de la vista
	 */
	public function defaultAction( XMLModel $appNode, Hashtable $args){

		$orm = new Catalogos();
		$catalogoORM = $orm->getAll();

		$p = new Paginator( $appNode, $catalogoORM );
		$p->addHiddenFields(array('id_catalogo','fecha_hora'));
		$p->modifiedField('catalogo','a',array('href'=>'/app/invoice/services?catalog=$id_catalogo'));
		$p->run();
	}


	/**
	 * Busca un catalogos segun parametros
	 *
	 * @app
	 * @param XMLModel $appNode Modelo
	 * @param Hashtable $args Parametros 
	 * @return string Vista del controlador
	 */
	public function searchAction( XMLModel $appNode, Hashtable $args) {

		$orm = new Catalogos();
		$catalogos = $orm->searchLikeField('catalogo',$this->decode($args->busqueda));
		$req = $appNode->addChild('request');
		$req['string'] = $args->busqueda;

		$p = new Paginator( $appNode, $catalogos );
		$p->addHiddenFields(array('id_catalogo','fecha_hora'));
		$p->modifiedField('catalogo','a',array('href'=>'/app/invoice/services?catalog=$id_catalogo'));
		$p->run();

	}

	/**
	 * Crea un catalogo nuevo
	 *
	 * @app
	 * @param XMLModel $appNode Modelo
	 * @param Hashtable $args Parametros 
	 * @return string Vista del controlador
	 */
	public function createAction( XMLModel $appNode, Hashtable $args) {

		$orm = new Catalogos();
		$orm->catalogo = $this->decode($args->catalog);
		$orm->desde = $args->from;
		$orm->hasta = $args->to;
		$orm->save();

		$this->addSuccess($appNode,"El catálogo ha sido creado correctamente");

		$this->defaultAction($appNode, $args);
	}


}
?>
