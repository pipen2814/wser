<?

namespace crm\orm;

use php\orm\BaseORM;
/**
 * Class TiposEventos
 *
 * ORM para la tabla tipos_eventos
 *
 * @package crm
 * @subpackage orm
 */
class TiposEventos extends \crm\orm\defs\TiposEventos {

	/**
	 * Metodo que obtiene todos los tipos de eventos disponibles
	 *
	 * @return $rs Todos los tipos de eventos disponibles
	 */
	public function getAll(){
		$query = "select * from $this->tableName";

		$this->db->query($query);

		$rs = $this->db->getRs();

		return $rs;
	}

	/**
	 * Recupera los tipos de eventos buscando en tipo_evento y color_rgb
	 *
	 * @param string $search Palabra a buscar
	 * @return ResultSet Coincidencias
	 */
	public function searchLike($search) {
		$search = "'%$search%'";
		$query = sprintf("select * from %s where tipo_evento like %s or color_rgb like %s order by id_tipo_evento",$this->tableName,$search,$search);
		$this->db->query($query);
		return $this->db->getRs();
	}

}
?>
