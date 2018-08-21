<?

namespace crm\orm;

use php\orm\BaseORM;
/**
 *
 * @package crm
 * @subpackage orm
 */
class Servicios extends \crm\orm\defs\Servicios  {

	/**
	 * Muestra el numero de servicios de un catalogo
	 *
	 * @param int $catalogId Id de Catalogo
	 * @return int Numero de servicios
	 */
	public function getNumServices($catalogId){
		$query = sprintf("select count(id_servicio) as sum from %s where id_catalogo = %s",$this->tableName,$catalogId);
		$this->db->query($query);
		$rs = $this->db->getRs();
		return ($rs->next())?$rs->sum:0;
	}

	/**
	 * Muestra los servicios de un catalogo
	 *
	 * @param int $catalogId Id de Catalogo
	 * @return object servicios
	 */
	public function getServicesByCatalog($catalogId){
		$query = sprintf("select * from %s where id_catalogo = %s",$this->tableName,$catalogId);
		$this->db->query($query);
		$rs = $this->db->getRs();
		return $rs;
	}


}
?>
