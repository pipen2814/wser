<?

namespace crm\orm;

use php\orm\BaseORM;
/**
 * Class ContactosDirecciones
 *
 * ORM para la tabla contactos_direcciones
 *
 * @package crm
 * @subpackage orm
 */
class ContactosDirecciones extends \crm\orm\defs\ContactosDirecciones {

	/**
	 * Metodo para obtener todas las direcciones de un contacto
	 *
	 * @param $contactId ID de contacto a buscar
	 *
	 * @return $rs ResultSet con el contacto buscado
	 */
	public function getAllByContactId($contactId){

		$query = "select * from contactos_direcciones cd where cd.id_contacto = $contactId";
		$this->db->query($query);
		$rs = $this->db->getRs();
		return $rs;

	}
}
?>
