<?php

class D2A{
	/***** DATA TO AMKA IDIKA IMPLEMENTETION *****/

	private $client;
	private $username;
	private $password;
	private $wsdl;
	private $input;

	public function  __construct($username, $password, $wsdl){
		//Set values to priv attr
		$this->username = $username;
		$this->password = $password;
		$this->wsdl = $wsdl;
		
	}


	public function client() {
		// Set client return 
		try { 
			$this->client = new SoapClient($this->wsdl);
			if (!$this->client) {
				return false;
			} else {
				return true;
			}

		} catch (Exception $e) { 
			Generic::httpError('HTTP/1.1 500 Internal Server Error','500',$e->getMessage());
		} 
	}

	public function call($data){
		$obl_keys = array("dob", "afm", "atm");
		$missing_found = array();
		$res = "";

		if (!array_key_exists("surname", $data)) {
			Generic::httpError('HTTP/1.1 400 Bad Request','400','Το επώνυμο είναι υποχρεωτικό πέδίο');

			$correct = array("aa", "surname", "firstname", "fathername", "mothername","dob", "afm", "atm");

			foreach ($correct as $cor_el) {
				if (!array_key_exists($cor_el, $data)){
					$data[$cor_el] = "";
				}
			}
		}

		$data = array(
			$data["aa"], 
			$data["surname"], 
			$data["firstname"], 
			$data["fathername"], 
			$data["mothername"],
			$data["dob"], 
			$data["afm"], 
			$data["atm"]
			);

		$this->input = implode("|", $data);

		try{
			$request = array(
				"user_ed" => $this->username,
				"password_ed" => $this->password,
				"input_ed" => $this->input
				);
			$soap_result = $this->client->__soapCall("entryPoint",array($request));
			return $this->final_result($soap_result->entryPointResult);

		}
		catch(Exception $e){

			Generic::httpError('HTTP/1.1 500 Internal Server Error','500',$e->getMessage());
		}
		
	}

	private function jsonRemoveUnicodeSequences($struct) {
		return preg_replace("/\\\\u([a-f0-9]{4})/e", "iconv('UCS-4LE','UTF-8',pack('V', hexdec('U$1')))", $struct);
	}

	private function final_result($string){

		$data = explode("|", $string);
		$keyarray=array();

		$keyarray['input_aa'] = $data[0];
		$keyarray['input_surname'] = $data[1];
		$keyarray['input_firstname'] = $data[2];
		$keyarray['input_fathername'] = $data[3];
		$keyarray['input_mothername'] = $data[4];
		$keyarray['input_dob'] = $data[5];
		$keyarray['input_afm'] = $data[6];
		$keyarray['input_atm'] = $data[7];
		$keyarray['amkaval'] = $data[8];
		$keyarray['ldid'] = $data[9];
		$keyarray['atm'] = $data[10];
		$keyarray['country'] = $data[11];
		$keyarray['country'] = $data[12];
		$keyarray['afm'] = $data[13];
		$keyarray['birthsurname'] = $data[14];
		$keyarray['currentsurname'] = $data[15];
		$keyarray['firstname'] = $data[16];
		$keyarray['fathername'] = $data[17];
		$keyarray['mothername'] = $data[18];
		$keyarray['birthsurnamelatin'] = $data[19];
		$keyarray['currentsurnamelatin'] = $data[20];
		$keyarray['firstnamelatin'] = $data[21];
		$keyarray['fathernamelatin'] = $data[22];
		$keyarray['mothernamelatin'] = $data[23];
		$keyarray['dob'] = $data[24];
		$keyarray['fakedob'] = $data[25];
		$keyarray['deathind'] = $data[26];
		$keyarray['dateofdeath'] = $data[27];

		return $keyarray;
		//return $this->jsonRemoveUnicodeSequences(json_encode($keyarray));
	}


}

?>