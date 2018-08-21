<?

namespace crm\controllers\invoice;

use php\XMLModel;
use php\Hashtable;

use crm\CRMController;
use crm\orm\Catalogos;
use crm\orm\Servicios;
use php\util\Paginator;

/**
 * Gestion de los servicios pertenecientes a los catalogos
 *
 * @package crm
 * @subpackage controllers
 * @subpackage invoice
 */
class ServicesController extends CRMController{

	/**
	 * Muestra los servicios incluidos en un catalogo 
	 *
	 * @app
	 * @param XMLModel $appNode Modelo
	 * @param Hashtable $args Parametros
	 *
	 * @return string Nombre de la vista
	 */
	public function defaultAction($appNode, $args){

		$service = new Servicios();
		$services = $service->getServicesByCatalog($args->catalog);

		$p = new Paginator( $appNode, $services );
		$p->addHiddenFields(array('id_catalogo','id_servicio','fecha_hora'));
		$mod = array('value' => "<i class='fa fa-question-circle' aria-hidden='true' data-toggle='tooltip' data-html='true' data-placement='bottom' title='\$detalle'> \$id_servicio.");
		$p->modifiedField('servicio',null,$mod);
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

		$orm = new Servicios();
		$catalogos = $orm->searchLikeField('servicio',$this->decode($args->busqueda));
		$req = $appNode->addChild('request');
		$req['string'] = $args->busqueda;

		$p = new Paginator( $appNode, $catalogos );
		$p->addHiddenFields(array('id_catalogo','id_servicio','fecha_hora'));
		$p->run();

	}

}
?>
