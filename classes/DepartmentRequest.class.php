<?php

class DepartmentRequest {
    public $id;
    public $seira;
    public $number;
    public $patient;
    public $comp_id;
    public $admin_year;
    public $department;
    public $lines;

    public function loadMaster($id='') {
        global $db;
        $sql= "SELECT * from CS.WH_SUPPLY_REQS where SREQ_ID={$id}";
        if (!$dr = $db->get_row($sql,ARRAY_A)) {
            Generic::httpError('HTTP/1.1 500 Internal Server Error','500','Πρόβλημα στη ΒΔ');
        }
        $this->seira=$dr['SREQ_SEIRA'];
        $this->number=$dr['SREQ_NR'];
        $this->admin_year=$dr['SREQ_ADMIN_YEAR'];
        $this->department=$dr['SREQ_WDPT_ID'];
        
    }

    public function loadLines() {
        global $db;
        $sql="SELECT c.kind_id, c.kind_code, c.kind_name, c.kind_unit_id, d.unit_name, b.srek_qty FROM wh_supply_req_kinds b, wh_kinds c, wh_units d WHERE b.srek_kind_id = c.kind_id AND c.kind_unit_id = d.unit_id AND b.srek_sreq_id = {$this->id}";
        return $db->get_results($sql,ARRAY_A);
    }
}




