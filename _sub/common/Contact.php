<?php
/*
 * Created on 27 Feb 2009
 *
 */
class Contact extends DBManager {

	function __construct(){
		$this->tipcontact_id=1;	
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
