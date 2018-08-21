<?

namespace crm\orm;

use php\orm\BaseORM;
/**
 * Class ContactosModosContacto
 *
 * ORM para la tabla contactos_modos_contacto
 *
 * @package crm
 * @subpackage orm
 */
class ContactosModosContacto extends \crm\orm\defs\ContactosModosContacto {

	/**
	 * Metodo para recuperar todos los modos de contacto de un contacto
	 *
	 * @param $contactId ID de contacto
	 *
	 * @return $rs ResultSet con los modos de contacto
	 */
	public function getAllByContactId($contactId){

		$query = "select * from contactos_modos_contacto cmc where cmc.id_contacto = $contactId";
                $this->db->query($query);
                $rs = $this->db->getRs();
                return $rs;

	}
}
?>
