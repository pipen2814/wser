<?

namespace crm\orm;

use php\orm\BaseORM;

/**
 * @todo hacerlo multiidioma
 *
 * @package crm
 * @subpackage orm
 */
class Trads extends \crm\orm\defs\Trads  {

	/**
	 * Recupera traduciones que coincidan (%like%) en dominio, clave o txt
	 *
	 * @param string $search Palabra a buscar
	 * @return ResultSet Coincidencias
	 */
	public function searchLike($search) {
		$search = "'%$search%'";
		$query = sprintf("select * from %s where clave like %s or txt like %s or dominio like %s order by clave",$this->tableName,$search,$search,$search);
		$this->db->query($query);
		return $this->db->getRs();
	}

}
?>
