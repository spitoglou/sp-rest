<?php

class A2D{
	/***** AMKA TO DATA IDIKA IMPLEMENTETION *****/

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
		
		$res = "";

		if (!array_key_exists("amka", $data)) {
			Generic::httpError('HTTP/1.1 400 Bad Request','400','Το A.M.K.A είναι υποχρεωτικό πέδίο');

		}

		
		$this->input = $data["amka"];

		try{
			$request = array(
				"user_ed" => $this->username,
				"password_ed" => $this->password,
				"input_ed" => "123|{$this->input}"
				);
			$soap_result = $this->client->__soapCall("entryPoint",array($request));
			//var_dump($soap_result);
			return $this->final_result($soap_result->entryPointResult);


		}
		catch(Exception $e){

			Generic::httpError('HTTP/1.1 500 Internal Server Error','500',$e->getMessage());
		}
		
	}

	
	private function final_result($string){
		
		$data = explode("|", $string);

		if (count($data)<4) {
			Generic::httpError('HTTP/1.1 400 Bad Request','400',$string);
		}
		$keyarray=array();





		$keyarray['input_aa'] = $data[0];
		$keyarray['input_amka'] = $data[1];
		$keyarray['idika_amka'] = $data[2];
		$keyarray['date_last_update'] = $data[3];
		$keyarray['id_type'] = $data[4];
		$keyarray['id_number'] = $data[5];
		$keyarray['id_year'] = $data[6];
		$keyarray['country'] = $data[7];
		$keyarray['country_code'] = $data[8];
		$keyarray['sex'] = $data[9];
		$keyarray['afm'] = $data[10];
		$keyarray['birth_surname_gr'] = $data[11];
		$keyarray['surname_gr'] = $data[12];
		$keyarray['name_gr'] = $data[13];
		$keyarray['father_name_gr'] = $data[14];
		$keyarray['mother_name_gr'] = $data[15];
		$keyarray['birth_surname_latin'] = $data[16];
		$keyarray['surname_latin'] = $data[17];
		$keyarray['name_latin'] = $data[18];
		$keyarray['father_name_latin'] = $data[19];
		$keyarray['mother_name_latin'] = $data[20];
		$keyarray['dob'] = $data[21];
		$keyarray['fake_dob_sw'] = $data[22];
		$keyarray['country_of_birth'] = $data[23];
		$keyarray['country_of_birth_code'] = $data[24];
		$keyarray['city_of_birth'] = $data[25];
		$keyarray['perfecture_of_birth'] = $data[26];
		$keyarray['address_street_number'] = $data[27];
		$keyarray['address_city'] = $data[28];
		$keyarray['address_perf_code'] = $data[29];
		$keyarray['address_zip_code'] = $data[30];
		$keyarray['address_country'] = $data[31];
		$keyarray['address_country_code'] = $data[32];
		$keyarray['phone_1'] = $data[33];
		$keyarray['phone_2'] = $data[34];
		$keyarray['death_sw'] = $data[35];
		$keyarray['death_date'] = $data[36];


		return $keyarray;
		
	}


}

?>