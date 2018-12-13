<?php
require_once(__DIR__ . '/../main/loader.php');
 
class PropertyWebPage extends MainWebPage {
	function getPropertyContacts(){
		return $this->getGroupBoxH3("Contacte:",$this->getContacts($this->currentcontact));
	}

	function getContacts($contact){
		$contact_date = new DateTime($contact->data);
		$current_date = new DateTime();
		$age = $contact_date->diff($current_date)->days;
		if ($age > 31) {
		    return 'Anuntul e mai vechi de 30 zile. Contactele au fost sterse.';
		} else {
		    return parent::getContacts($this->currentcontact);
		}
	}

}
?>
