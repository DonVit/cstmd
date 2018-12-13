<?php
class Contact extends DBManager {

	function __construct(){
		$this->tipcontact_id=1;
		$this->data=System::getCurentDateTime();
	}
	public $id;
	public $tipcontact_id;
	public $tipcompanie_id;
	public $contactname;
	public $phone;
	public $mobile;
	public $email;
	public $skypeid;
	public $contacturl;
	public $fax;
	public $notecontact;
	public $data;

}
?>
