<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
  /**
  * Ignited Datatables
  *
  * This is a wrapper class/library based on the native Datatables server-side implementation by Allan Jardine
  * found at http://datatables.net/examples/data_sources/server_side.html for CodeIgniter
  *
  * @package    CodeIgniter
  * @subpackage libraries
  * @category   library
  * @version    0.7
  * @author     Vincent Bambico <metal.conspiracy@gmail.com>
  *             Yusuf Ozdemir <yusuf@ozdemir.be>
  * @link       http://codeigniter.com/forums/viewthread/160896/
  */
  class Datatables
  {
    /**
    * Global container variables for chained argument results
    *
    */
    protected $ci;
    protected $table;
    protected $distinct;
    protected $group_by;
    protected $select         = array();
    protected $joins          = array();
    protected $columns        = array();
    protected $where          = array();
    protected $filter         = array();
    protected $add_columns    = array();
    protected $edit_columns   = array();
    protected $unset_columns  = array();

    /**
    * Copies an instance of CI
    */
    public function __construct()
    {
      $this->ci =& get_instance();
    }

    /**
    * If you establish multiple databases in config/database.php this will allow you to
    * set the database (other than $active_group) - more info: http://codeigniter.com/forums/viewthread/145901/#712942
    */
    public function set_database($db_name)
    {
			$db_data = $this->ci->load->database($db_name, TRUE);
			$this->ci->db = $db_data;
		}

    /**
    * Generates the SELECT portion of the query
    *
    * @param string $columns
    * @param bool $backtick_protect
    * @return mixed
    */
    public function select($columns, $backtick_protect = TRUE)
    {
      foreach($this->explode(',', $columns) as $val)
      {
        $column = trim(preg_replace('/(.*)\s+as\s+(\w*)/i', '$2', $val));
        $this->columns[] =  $column;
        $this->select[$column] =  trim(preg_replace('/(.*)\s+as\s+(\w*)/i', '$1', $val));
      }

      $this->ci->db->select($columns, $backtick_protect);
      return $this;
    }

    /**
    * Generates the DISTINCT portion of the query
    *
    * @param string $column
    * @return mixed
    */
    public function distinct($column)
    {
      $this->distinct = $column;
      $this->ci->db->distinct($column);
      return $this;
    }

    /**
    * Generates the GROUP_BY portion of the query
    *
    * @param string $column
    * @return mixed
    */
    public function group_by($column)
    {
      $this->group_by = $column;
      $this->ci->db->group_by($column);
      return $this;
    }

    /**
    * Generates the FROM portion of the query
    *
    * @param string $table
    * @return mixed
    */
    public function from($table)
    {
      $this->table = $table;
      $this->ci->db->from($table);
      return $this;
    }

    /**
    * Generates the JOIN portion of the query
    *
    * @param string $table
    * @param string $fk
    * @param string $type
    * @return mixed
    */
    public function join($table, $fk, $type = NULL)
    {
      $this->joins[] = array($table, $fk, $type);
      $this->ci->db->join($table, $fk, $type);
      return $this;
    }

    /**
    * Generates the WHERE portion of the query
    *
    * @param mixed $key_condition
    * @param string $val
    * @param bool $backtick_protect
    * @return mixed
    */
    public function where($key_condition, $val = NULL, $backtick_protect = TRUE)
    {
      $this->where[] = array($key_condition, $val, $backtick_protect);
      $this->ci->db->where($key_condition, $val, $backtick_protect);
      return $this;
    }

    /**
    * Generates the WHERE portion of the query
    *
    * @param mixed $key_condition
    * @param string $val
    * @param bool $backtick_protect
    * @return mixed
    */
    public function or_where($key_condition, $val = NULL, $backtick_protect = TRUE)
    {
      $this->where[] = array($key_condition, $val, $backtick_protect);
      $this->ci->db->or_where($key_condition, $val, $backtick_protect);
      return $this;
    }

    /**
    * Generates the WHERE portion of the query
    *
    * @param mixed $key_condition
    * @param string $val
    * @param bool $backtick_protect
    * @return mixed
    */
    public function like($key_condition, $val = NULL, $backtick_protect = TRUE)
    {
      $this->where[] = array($key_condition, $val, $backtick_protect);
      $this->ci->db->like($key_condition, $val, $backtick_protect);
      return $this;
    }

    /**
    * Generates the WHERE portion of the query
    *
    * @param mixed $key_condition
    * @param string $val
    * @param bool $backtick_protect
    * @return mixed
    */
    public function filter($key_condition, $val = NULL, $backtick_protect = TRUE)
    {
      $this->filter[] = array($key_condition, $val, $backtick_protect);
      return $this;
    }

    /**
    * Sets additional column variables for adding custom columns
    *
    * @param string $column
    * @param string $content
    * @param string $match_replacement
    * @return mixed
    */
    public function add_column($column, $content, $match_replacement = NULL)
    {
      $this->add_columns[$column] = array('content' => $content, 'replacement' => $this->explode(',', $match_replacement));
      return $this;
    }

    /**
    * Sets additional column variables for editing columns
    *
    * @param string $column
    * @param string $content
    * @param string $match_replacement
    * @return mixed
    */
    public function edit_column($column, $content, $match_replacement)
    {
      $this->edit_columns[$column][] = array('content' => $content, 'replacement' => $this->explode(',', $match_replacement));
      return $this;
    }

    /**
    * Unset column
    *
    * @param string $column
    * @return mixed
    */
    public function unset_column($column)
    {
      $this->unset_columns[] = $column;
      return $this;
    }

    /**
    * Builds all the necessary query segments and performs the main query based on results set from chained statements
    *
    * @param string charset
    * @return string
    */
    public function generate($charset = 'UTF-8')
    {
      $this->get_paging();
      $this->get_ordering();
      $this->get_filtering();
      return $this->produce_output($charset);
    }

    /**
    * Generates the LIMIT portion of the query
    *
    * @return mixed
    */
    protected function get_paging()
    {
      $iStart = $this->ci->input->post('iDisplayStart');
      $iLength = $this->ci->input->post('iDisplayLength');
      $this->ci->db->limit(($iLength != '' && $iLength != '-1')? $iLength : 100, ($iStart)? $iStart : 0); 
    }

    /**
    * Generates the ORDER BY portion of the query
    *
    * @return mixed
    */
    protected function get_ordering()
    {
      if($this->check_mDataprop())
        $mColArray = $this->get_mDataprop();
      elseif($this->ci->input->post('sColumns'))
        $mColArray = explode(',', $this->ci->input->post('sColumns'));
      else
        $mColArray = $this->columns;

      $mColArray = array_values(array_diff($mColArray, $this->unset_columns));
      $columns = array_values(array_diff($this->columns, $this->unset_columns));
 
      for($i = 0; $i < intval($this->ci->input->post('iSortingCols')); $i++)
        if(isset($mColArray[intval($this->ci->input->post('iSortCol_' . $i))]) && in_array($mColArray[intval($this->ci->input->post('iSortCol_' . $i))], $columns) && $this->ci->input->post('bSortable_'.intval($this->ci->input->post('iSortCol_' . $i))) == 'true')
          $this->ci->db->order_by($mColArray[intval($this->ci->input->post('iSortCol_' . $i))], $this->ci->input->post('sSortDir_' . $i));
    }

    /**
    * Generates the LIKE portion of the query
    *
    * @return mixed
    */
    protected function get_filtering()
    {
      if($this->check_mDataprop())
        $mColArray = $this->get_mDataprop();
      elseif($this->ci->input->post('sColumns'))
        $mColArray = explode(',', $this->ci->input->post('sColumns'));
      else
        $mColArray = $this->columns;
//print_r($mColArray);
      $sWhere = '';
      //$sSearch = mysql_real_escape_string($this->ci->input->post('sSearch'));
      $sSearch = $this->ci->input->post('sSearch'); //Modified by Gopy sankar.R for wamp 2.5 compatablity 10/3/2015
      
      $mColArray = array_values(array_diff($mColArray, $this->unset_columns));
      $columns = array_values(array_diff($this->columns, $this->unset_columns));
    //print_r($mColArray);
    //print_r($columns); exit;
      if($sSearch != '')
        for($i = 0; $i < count($mColArray); $i++)
	{
	    //echo $sSearch; exit();
	   
	   //echo $this->ci->input->post('bSearchable_' . $i);

          if($this->ci->input->post('bSearchable_' . $i) == 'true' && in_array($mColArray[$i], $columns))
	  {
              $sWhere .= "LOWER(".$this->select[$mColArray[$i]] .") LIKE  lower('%" . $sSearch . "%') OR ";
	    //echo $sSearch; exit();
	  }
	  
	}
	  //echo $sSearch;
      $sWhere = substr_replace($sWhere, '', -3);
      
  // echo $sWhere; exit();
   
      if($sWhere != '')
        $this->ci->db->where('(' . $sWhere . ')');

      for($i = 0; $i < intval($this->ci->input->post('iColumns')); $i++)
      {
        if(isset($_POST['sSearch_' . $i]) && $this->ci->input->post('sSearch_' . $i) != '' && in_array($mColArray[$i], $columns))
        {
          $miSearch = explode(',', $this->ci->input->post('sSearch_' . $i));
	  
          foreach($miSearch as $val)
          {
            if(preg_match("/(<=|>=|=|<|>)(\s*)(.+)/i", trim($val), $matches))
              $this->ci->db->where($this->select[$mColArray[$i]].' '.$matches[1], $matches[3]);
            else
	 
              $this->ci->db->where($this->select[$mColArray[$i]].' LIKE', '%'.$val.'%');
          }
        }
      }

      foreach($this->filter as $val)
        $this->ci->db->where($val[0], $val[1], $val[2]);
    }

    /**
    * Compiles the select statement based on the other functions called and runs the query
    *
    * @return mixed
    */
    protected function get_display_result()
    {
      $data = $this->ci->db->get();
     //echo $this->ci->db->last_query(); exit();
      return $data;
    }

    /**
    * Builds a JSON encoded string data
    *
    * @param string charset
    * @return string
    */
    protected function produce_output($charset)
    {
	$aaData = array();
	$rResult = $this->get_display_result();
	$iTotal = $this->get_total_results();
	$iFilteredTotal = $this->get_total_results(TRUE);
	
	 
      
      foreach($rResult->result_array() as $row_key => $row_val)
      {
        $aaData[$row_key] = ($this->check_mDataprop())? $row_val : array_values($row_val);


        foreach($this->add_columns as $field => $val)
          if($this->check_mDataprop())
            $aaData[$row_key][$field] = $this->exec_replace($val, $aaData[$row_key]);
          else
            $aaData[$row_key][] = $this->exec_replace($val, $aaData[$row_key]);

        foreach($this->edit_columns as $modkey => $modval)
          foreach($modval as $val)
            $aaData[$row_key][($this->check_mDataprop())? $modkey : array_search($modkey, $this->columns)] = $this->exec_replace($val, $aaData[$row_key]);

        $aaData[$row_key] = array_diff_key($aaData[$row_key], ($this->check_mDataprop())? $this->unset_columns : array_intersect($this->columns, $this->unset_columns));

        if(!$this->check_mDataprop())
        $aaData[$row_key] = array_values($aaData[$row_key]);
	
	$tableName = $this->table;
	
	
	
	//Application process description length if morethan 15 letters
	//if($tableName=="APPS_COUNTRY"){
	//  if(strlen($aaData[$row_key]['CN_DESC']) > 15){
	//    $a = $aaData[$row_key]['CN_DESC'];
	//    $b = substr($a, 0,15).'..';
	//    $aaData[$row_key]['CN_DESC'] = "<span title='$a' class='$i tool'>".$b."</span>";
	//  }
	//}
	//if(strlen($aaData[$row_key]['CT_DESC']) > 15){
	//  $a = $aaData[$row_key]['CT_DESC'];
	//  $b = substr($a, 0,15).'..';
	//  $aaData[$row_key]['CT_DESC'] = "<span title='$a' class='$i tool'>".$b."</span>";
	//}
	//if(strlen($aaData[$row_key]['MENU_DESC']) > 15){
	//  $a = $aaData[$row_key]['MENU_DESC'];
	//  $b = substr($a, 0,15).'..';
	//  $aaData[$row_key]['MENU_DESC'] = "<span title='$a' class='$i tool'>".$b."</span>";
	//}
	//if(strlen($aaData[$row_key]['RGH_DESC']) > 15){
	//  $a = $aaData[$row_key]['RGH_DESC'];
	//  $b = substr($a, 0,15).'..';
	//  $aaData[$row_key]['RGH_DESC'] = "<span title='$a' class='$i tool'>".$b."</span>";
	//}
	//if(strlen($aaData[$row_key]['RSPH_DESC']) > 15){
	//  $a = $aaData[$row_key]['RSPH_DESC'];
	//  $b = substr($a, 0,15).'..';
	//  $aaData[$row_key]['RSPH_DESC'] = "<span title='$a' class='$i tool'>".$b."</span>";
	//}
	//if(strlen($aaData[$row_key]['ST_DESC']) > 15){
	//  $a = $aaData[$row_key]['ST_DESC'];
	//  $b = substr($a, 0,15).'..';
	//  $aaData[$row_key]['ST_DESC'] = "<span title='$a' class='$i tool'>".$b."</span>";
	//}
	//if(strlen($aaData[$row_key]['TXH_DESC']) > 15){
	//  $a = $aaData[$row_key]['TXH_DESC'];
	//  $b = substr($a, 0,15).'..';
	//  $aaData[$row_key]['TXH_DESC'] = "<span title='$a' class='$i tool'>".$b."</span>";
	//}
	//if(strlen($aaData[$row_key]['TXN_DESC']) > 15){
	//  $a = $aaData[$row_key]['TXN_DESC'];
	//  $b = substr($a, 0,15).'..';
	//  $aaData[$row_key]['TXN_DESC'] = "<span title='$a' class='$i tool'>".$b."</span>";
	//}
	//if(strlen($aaData[$row_key]['USER_DESC']) > 15){
	//  $a = $aaData[$row_key]['USER_DESC'];
	//  $b = substr($a, 0,15).'..';
	//  $aaData[$row_key]['USER_DESC'] = "<span title='$a' class='$i tool'>".$b."</span>";
	//}
	
	// End.....
	
	
	//stock adjustment Transaction
	if($tableName=="INVT_T_STOCK_ADJ_HEAD_VIEW"){
	    $ApproveButton = $_POST['ApproveButton'];	 
	    $AmendedButton = $_POST['AmendedButton'];
	    $ActiveInsert = $_POST['ActiveInsert'];
	    $ActiveUpdate = $_POST['ActiveUpdate'];
	    if($aaData[$row_key]['SAH_STATUS']=="Incomplete"){
		
		//if ($ApproveButton == 'N') {
		if ($ActiveInsert == 'Y' || $ActiveUpdate == 'Y') {
		  $aaData[$row_key]['SAH_STATUS']='<button data-target="#large_view" id="created" class="btn-new btn-primary" data-toggle="modal" systemId = "'.$aaData[$row_key]['SAH_SYS_ID'].'" onclick=javascript:quotation_status('.$aaData[$row_key]['SAH_SYS_ID'].')>Incomplete</button>';
		}else{
		  $aaData[$row_key]['SAH_STATUS']='<button data-target="#large_view" id="created" class="btn-new btn-primary" data-toggle="modal" disabled>Incomplete</button>';
		}
	    
	    }else if($aaData[$row_key]['SAH_STATUS']=="Send for approval"){
		
		if ($ApproveButton == 'N') {
		    $aaData[$row_key]['SAH_STATUS']='<button data-target="#large_view" id="approve" class="btn-new btn-warning" data-toggle="modal" disabled>Approve ?</button>';
		}else{
		    $aaData[$row_key]['SAH_STATUS']='<button data-target="#large_view" id="approve" class="btn-new btn-warning" data-toggle="modal" systemId = "'.$aaData[$row_key]['SAH_SYS_ID'].'" onclick=javascript:quotation_status('.$aaData[$row_key]['SAH_SYS_ID'].')>Approve ?</button>';
		}
		
	    }else if($aaData[$row_key]['SAH_STATUS']=="Approved"){
		
		if ($ApproveButton == 'N') {
		    $aaData[$row_key]['SAH_STATUS']='<button data-target="#large_view" id="approved" class="btn-new btn-success" data-toggle="modal" disabled>Approved</button>';
		}else{
		    $aaData[$row_key]['SAH_STATUS']='<button data-target="#large_view" id="approved" class="btn-new btn-success" data-toggle="modal" systemId = "'.$aaData[$row_key]['SAH_SYS_ID'].'" onclick=javascript:quotation_status('.$aaData[$row_key]['SAH_SYS_ID'].')>Approved</button>';
		}
		
	    }else if($aaData[$row_key]['SAH_STATUS']=="Amended"){
		
		if (($ApproveButton == 'N') || ($AmendedButton == 'N')) {
		    $aaData[$row_key]['SAH_STATUS']='<button data-target="#large_view" id="amend" data-toggle="modal" class="btn-new btn-new-inverse" disabled>Amended</button>';
		}else{
		    $aaData[$row_key]['SAH_STATUS']='<button data-target="#large_view" id="amend" data-toggle="modal" class="btn-new btn-new-inverse" systemId = "'.$aaData[$row_key]['SAH_SYS_ID'].'" onclick=javascript:quotation_status('.$aaData[$row_key]['SAH_SYS_ID'].')>Amended</button>';
		}
	    
	    }if($aaData[$row_key]['SAH_STATUS']=="Rejected"){
		
		if ($ApproveButton == 'N') {
		    $aaData[$row_key]['SAH_STATUS']='<button  data-target="#large_view" id="rejected" data-toggle="modal" class="btn-new btn-danger" disabled>Rejected</button>';
		}else{
		    $aaData[$row_key]['SAH_STATUS']='<button  data-target="#large_view" id="rejected" data-toggle="modal" class="btn-new btn-danger" onclick=javascript:quotation_status('.$aaData[$row_key]['SAH_SYS_ID'].')>Rejected</button>';
		}
	    
	    }
	}
	//End stock adjustment Transaction 
	//Stock transfer incoming
	if($tableName=="INVT_T_STRI_HEAD_VIEW"){
	    $ApproveButton = $_POST['ApproveButton'];
	    $AmendedButton = $_POST['AmendedButton'];
	    $ActiveInsert = $_POST['ActiveInsert'];
	    $ActiveUpdate = $_POST['ActiveUpdate'];
	      
	    if($aaData[$row_key]['STIH_STATUS']=="Incomplete"){
		
		//if ($ApproveButton == 'N') {
		if ($ActiveInsert == 'Y' || $ActiveUpdate == 'Y') {
		  $aaData[$row_key]['STIH_STATUS']='<button data-target="#large_view" id="created" class="btn-new btn-primary" data-toggle="modal" systemId = "'.$aaData[$row_key]['STIH_SYS_ID'].'" onclick=javascript:quotation_status('.$aaData[$row_key]['STIH_SYS_ID'].')>Incomplete</button>';
		}else{
		  $aaData[$row_key]['STIH_STATUS']='<button data-target="#large_view" id="created" class="btn-new btn-primary" data-toggle="modal" disabled>Incomplete</button>';
		}
	    
	    }else if($aaData[$row_key]['STIH_STATUS']=="Send for approval"){
		
		if ($ApproveButton == 'N') {
		    $aaData[$row_key]['STIH_STATUS']='<button data-target="#large_view" id="approve" class="btn-new btn-warning" data-toggle="modal" disabled>Approve ?</button>';
		}else{
		    $aaData[$row_key]['STIH_STATUS']='<button data-target="#large_view" id="approve" class="btn-new btn-warning" data-toggle="modal" systemId = "'.$aaData[$row_key]['STIH_SYS_ID'].'" onclick=javascript:quotation_status('.$aaData[$row_key]['STIH_SYS_ID'].')>Approve ?</button>';
		}
		
	    }else if($aaData[$row_key]['STIH_STATUS']=="Approved"){
		
		if ($ApproveButton == 'N') {
		    $aaData[$row_key]['STIH_STATUS']='<button data-target="#large_view" id="approved" class="btn-new btn-success" data-toggle="modal" disabled>Approved</button>';
		}else{
		    $aaData[$row_key]['STIH_STATUS']='<button data-target="#large_view" id="approved" class="btn-new btn-success" data-toggle="modal" systemId = "'.$aaData[$row_key]['STIH_SYS_ID'].'" onclick=javascript:quotation_status('.$aaData[$row_key]['STIH_SYS_ID'].')>Approved</button>';
		}
		
	    }else if($aaData[$row_key]['STIH_STATUS']=="Amended"){
		
		if (($ApproveButton == 'N') || ($AmendedButton == 'N')) {
		    $aaData[$row_key]['STIH_STATUS']='<button data-target="#large_view" id="amend" data-toggle="modal" class="btn-new btn-new-inverse" disabled>Amended</button>';
		}else{
		    $aaData[$row_key]['STIH_STATUS']='<button data-target="#large_view" id="amend" data-toggle="modal" class="btn-new btn-new-inverse" systemId = "'.$aaData[$row_key]['STIH_SYS_ID'].'" onclick=javascript:quotation_status('.$aaData[$row_key]['STIH_SYS_ID'].')>Amended</button>';
		}
	    
	    }if($aaData[$row_key]['STIH_STATUS']=="Rejected"){
		
		if ($ApproveButton == 'N') {
		    $aaData[$row_key]['STIH_STATUS']='<button  data-target="#large_view" id="rejected" data-toggle="modal" class="btn-new btn-danger" disabled>Rejected</button>';
		}else{
		    $aaData[$row_key]['STIH_STATUS']='<button  data-target="#large_view" id="rejected" data-toggle="modal" class="btn-new btn-danger" onclick=javascript:quotation_status('.$aaData[$row_key]['STIH_SYS_ID'].')>Rejected</button>';
		}
	    
	    }
	} 
	//stock transfer outgoing  
	if($tableName=="INVT_T_STRO_HEAD_VIEW"){
	    $ApproveButton = $_POST['ApproveButton'];
	    $AmendedButton = $_POST['AmendedButton'];
	    $ActiveInsert = $_POST['ActiveInsert'];
	    $ActiveUpdate = $_POST['ActiveUpdate'];
	    
	    if($aaData[$row_key]['STOH_STATUS']=="Incomplete"){
		
		//if ($ApproveButton == 'N') {
		if ($ActiveInsert == 'Y' || $ActiveUpdate == 'Y') {
		  $aaData[$row_key]['STOH_STATUS']='<button data-target="#large_view" id="created" class="btn-new btn-primary" data-toggle="modal" systemId = "'.$aaData[$row_key]['STOH_SYS_ID'].'" onclick=javascript:quotation_status('.$aaData[$row_key]['STOH_SYS_ID'].')>Incomplete</button>';
		}else{
		  $aaData[$row_key]['STOH_STATUS']='<button data-target="#large_view" id="created" class="btn-new btn-primary" data-toggle="modal" disabled>Incomplete</button>';  
		}
	    
	    }else if($aaData[$row_key]['STOH_STATUS']=="Send for approval"){
		
		if ($ApproveButton == 'N') {
		    $aaData[$row_key]['STOH_STATUS']='<button data-target="#large_view" id="approve" class="btn-new btn-warning" data-toggle="modal" disabled>Approve ?</button>';
		}else{
		    $aaData[$row_key]['STOH_STATUS']='<button data-target="#large_view" id="approve" class="btn-new btn-warning" data-toggle="modal" systemId = "'.$aaData[$row_key]['STOH_SYS_ID'].'" onclick=javascript:quotation_status('.$aaData[$row_key]['STOH_SYS_ID'].')>Approve ?</button>';
		}
		
	    }else if($aaData[$row_key]['STOH_STATUS']=="Approved"){
		
		if ($ApproveButton == 'N') {
		    $aaData[$row_key]['STOH_STATUS']='<button data-target="#large_view" id="approved" class="btn-new btn-success" data-toggle="modal" disabled>Approved</button>';
		}else{
		    $aaData[$row_key]['STOH_STATUS']='<button data-target="#large_view" id="approved" class="btn-new btn-success" data-toggle="modal" systemId = "'.$aaData[$row_key]['STOH_SYS_ID'].'" onclick=javascript:quotation_status('.$aaData[$row_key]['STOH_SYS_ID'].')>Approved</button>';
		}
		
	    }else if($aaData[$row_key]['STOH_STATUS']=="Amended"){
		
		if (($ApproveButton == 'N') || ($AmendedButton == 'N')) {
		    $aaData[$row_key]['STOH_STATUS']='<button data-target="#large_view" id="amend" data-toggle="modal" class="btn-new btn-new-inverse" disabled>Amended</button>';
		}else{
		    $aaData[$row_key]['STOH_STATUS']='<button data-target="#large_view" id="amend" data-toggle="modal" class="btn-new btn-new-inverse" systemId = "'.$aaData[$row_key]['STOH_SYS_ID'].'" onclick=javascript:quotation_status('.$aaData[$row_key]['STOH_SYS_ID'].')>Amended</button>';
		}
	    
	    }if($aaData[$row_key]['STOH_STATUS']=="Rejected"){
		
		if ($ApproveButton == 'N') {
		    $aaData[$row_key]['STOH_STATUS']='<button  data-target="#large_view" id="rejected" data-toggle="modal" class="btn-new btn-danger" disabled>Rejected</button>';
		}else{
		    $aaData[$row_key]['STOH_STATUS']='<button  data-target="#large_view" id="rejected" data-toggle="modal" class="btn-new btn-danger" onclick=javascript:quotation_status('.$aaData[$row_key]['STOH_SYS_ID'].')>Rejected</button>';
		}
	    
	    }
	}   
	//stock transfer request
	if($tableName=="INVT_T_SREQ_HEAD_VIEW"){
	    $ApproveButton = $_POST['ApproveButton'];
	    $AmendedButton = $_POST['AmendedButton'];
	    $ActiveInsert = $_POST['ActiveInsert'];
	    $ActiveUpdate = $_POST['ActiveUpdate'];
	      
	      
	    if($aaData[$row_key]['SRQH_STATUS']=="Incomplete"){
		
		//if ($ApproveButton == 'N') {
		if ($ActiveInsert == 'Y' || $ActiveUpdate == 'Y') {
		  $aaData[$row_key]['SRQH_STATUS']='<button data-target="#large_view" id="created" class="btn-new btn-primary" data-toggle="modal" systemId = "'.$aaData[$row_key]['SRQH_SYS_ID'].'" onclick=javascript:quotation_status('.$aaData[$row_key]['SRQH_SYS_ID'].')>Incomplete</button>';
		}else{
		  $aaData[$row_key]['SRQH_STATUS']='<button data-target="#large_view" id="created" class="btn-new btn-primary" data-toggle="modal" disabled>Incomplete</button>'; 
		}
	    
	    }else if($aaData[$row_key]['SRQH_STATUS']=="Send for approval"){
		
		if ($ApproveButton == 'N') {
		    $aaData[$row_key]['SRQH_STATUS']='<button data-target="#large_view" id="approve" class="btn-new btn-warning" data-toggle="modal" disabled>Approve ?</button>';
		}else{
		    $aaData[$row_key]['SRQH_STATUS']='<button data-target="#large_view" id="approve" class="btn-new btn-warning" data-toggle="modal" systemId = "'.$aaData[$row_key]['SRQH_SYS_ID'].'" onclick=javascript:quotation_status('.$aaData[$row_key]['SRQH_SYS_ID'].')>Approve ?</button>';
		}
		
	    }else if($aaData[$row_key]['SRQH_STATUS']=="Approved"){
		
		if ($ApproveButton == 'N') {
		    $aaData[$row_key]['SRQH_STATUS']='<button data-target="#large_view" id="approved" class="btn-new btn-success" data-toggle="modal" disabled>Approved</button>';
		}else{
		    $aaData[$row_key]['SRQH_STATUS']='<button data-target="#large_view" id="approved" class="btn-new btn-success" data-toggle="modal" systemId = "'.$aaData[$row_key]['SRQH_SYS_ID'].'" onclick=javascript:quotation_status('.$aaData[$row_key]['SRQH_SYS_ID'].')>Approved</button>';
		}
		
	    }else if($aaData[$row_key]['SRQH_STATUS']=="Amended"){
		
		if (($ApproveButton == 'N') || ($AmendedButton == 'N')) {
		    $aaData[$row_key]['SRQH_STATUS']='<button data-target="#large_view" id="amend" data-toggle="modal" class="btn-new btn-new-inverse" disabled>Amended</button>';
		}else{
		    $aaData[$row_key]['SRQH_STATUS']='<button data-target="#large_view" id="amend" data-toggle="modal" class="btn-new btn-new-inverse" systemId = "'.$aaData[$row_key]['SRQH_SYS_ID'].'" onclick=javascript:quotation_status('.$aaData[$row_key]['SRQH_SYS_ID'].')>Amended</button>';
		}
	    
	    }if($aaData[$row_key]['SRQH_STATUS']=="Rejected"){
		
		if ($ApproveButton == 'N') {
		    $aaData[$row_key]['SRQH_STATUS']='<button  data-target="#large_view" id="rejected" data-toggle="modal" class="btn-new btn-danger" disabled>Rejected</button>';
		}else{
		    $aaData[$row_key]['SRQH_STATUS']='<button  data-target="#large_view" id="rejected" data-toggle="modal" class="btn-new btn-danger" onclick=javascript:quotation_status('.$aaData[$row_key]['SRQH_SYS_ID'].')>Rejected</button>';
		}
	    
	    }
	}
	//material request
	if($tableName=="INVT_T_REQ_HEAD_VIEW"){
	    $ApproveButton = $_POST['ApproveButton'];
	    $AmendedButton = $_POST['AmendedButton'];
	    $ActiveInsert = $_POST['ActiveInsert'];
	    $ActiveUpdate = $_POST['ActiveUpdate'];
	      
	    if($aaData[$row_key]['RQH_STATUS']=="Incomplete"){
		
		//if ($ApproveButton == 'N') {
		if ($ActiveInsert == 'Y' || $ActiveUpdate == 'Y') {
		  $aaData[$row_key]['RQH_STATUS']='<button data-target="#large_view" id="created" class="btn-new btn-primary" data-toggle="modal" systemId = "'.$aaData[$row_key]['RQH_SYS_ID'].'" onclick=javascript:quotation_status('.$aaData[$row_key]['RQH_SYS_ID'].')>Incomplete</button>';
		}else{
		  $aaData[$row_key]['RQH_STATUS']='<button data-target="#large_view" id="created" class="btn-new btn-primary" data-toggle="modal" disabled>Incomplete</button>';
		}
	    
	    }else if($aaData[$row_key]['RQH_STATUS']=="Send for approval"){
		
		if ($ApproveButton == 'N') {
		    $aaData[$row_key]['RQH_STATUS']='<button data-target="#large_view" id="approve" class="btn-new btn-warning" data-toggle="modal" disabled>Approve ?</button>';
		}else{
		    $aaData[$row_key]['RQH_STATUS']='<button data-target="#large_view" id="approve" class="btn-new btn-warning" data-toggle="modal" systemId = "'.$aaData[$row_key]['RQH_SYS_ID'].'" onclick=javascript:quotation_status('.$aaData[$row_key]['RQH_SYS_ID'].')>Approve ?</button>';
		}
		
	    }else if($aaData[$row_key]['RQH_STATUS']=="Approved"){
		
		if ($ApproveButton == 'N') {
		    $aaData[$row_key]['RQH_STATUS']='<button data-target="#large_view" id="approved" class="btn-new btn-success" data-toggle="modal" disabled>Approved</button>';
		}else{
		    $aaData[$row_key]['RQH_STATUS']='<button data-target="#large_view" id="approved" class="btn-new btn-success" data-toggle="modal" systemId = "'.$aaData[$row_key]['RQH_SYS_ID'].'" onclick=javascript:quotation_status('.$aaData[$row_key]['RQH_SYS_ID'].')>Approved</button>';
		}
		
	    }else if($aaData[$row_key]['RQH_STATUS']=="Amended"){
		
		if (($ApproveButton == 'N') || ($AmendedButton == 'N')) {
		    $aaData[$row_key]['RQH_STATUS']='<button data-target="#large_view" id="amend" data-toggle="modal" class="btn-new btn-new-inverse" disabled>Amended</button>';
		}else{
		    $aaData[$row_key]['RQH_STATUS']='<button data-target="#large_view" id="amend" data-toggle="modal" class="btn-new btn-new-inverse" systemId = "'.$aaData[$row_key]['RQH_SYS_ID'].'" onclick=javascript:quotation_status('.$aaData[$row_key]['RQH_SYS_ID'].')>Amended</button>';
		}
	    
	    }if($aaData[$row_key]['RQH_STATUS']=="Rejected"){
		
		if ($ApproveButton == 'N') {
		    $aaData[$row_key]['RQH_STATUS']='<button  data-target="#large_view" id="rejected" data-toggle="modal" class="btn-new btn-danger" disabled>Rejected</button>';
		}else{
		    $aaData[$row_key]['RQH_STATUS']='<button  data-target="#large_view" id="rejected" data-toggle="modal" class="btn-new btn-danger" onclick=javascript:quotation_status('.$aaData[$row_key]['RQH_SYS_ID'].')>Rejected</button>';
		}
	    }
	} 
	//material issue
	if($tableName=="INVT_T_MI_HEAD_VIEW"){
	    $ApproveButton = $_POST['ApproveButton'];
	    $AmendedButton = $_POST['AmendedButton'];
	    $ActiveInsert = $_POST['ActiveInsert'];
	    $ActiveUpdate = $_POST['ActiveUpdate'];
	      
	    if($aaData[$row_key]['MIH_STATUS']=="Incomplete"){
		
		//if ($ApproveButton == 'N') {
		if ($ActiveInsert == 'Y' || $ActiveUpdate == 'Y') {
		  $aaData[$row_key]['MIH_STATUS']='<button data-target="#large_view" id="created" class="btn-new btn-primary" data-toggle="modal" systemId = "'.$aaData[$row_key]['MIH_SYS_ID'].'" onclick=javascript:quotation_status('.$aaData[$row_key]['MIH_SYS_ID'].')>Incomplete</button>';
		}else{
		  $aaData[$row_key]['MIH_STATUS']='<button data-target="#large_view" id="created" class="btn-new btn-primary" data-toggle="modal" disabled>Incomplete</button>';
		}
	    
	    }else if($aaData[$row_key]['MIH_STATUS']=="Send for approval"){
		
		if ($ApproveButton == 'N') {
		    $aaData[$row_key]['MIH_STATUS']='<button data-target="#large_view" id="approve" class="btn-new btn-warning" data-toggle="modal" disabled>Approve ?</button>';
		}else{
		    $aaData[$row_key]['MIH_STATUS']='<button data-target="#large_view" id="approve" class="btn-new btn-warning" data-toggle="modal" systemId = "'.$aaData[$row_key]['MIH_SYS_ID'].'" onclick=javascript:quotation_status('.$aaData[$row_key]['MIH_SYS_ID'].')>Approve ?</button>';
		}
		
	    }else if($aaData[$row_key]['MIH_STATUS']=="Approved"){
		
		if ($ApproveButton == 'N') {
		    $aaData[$row_key]['MIH_STATUS']='<button data-target="#large_view" id="approved" class="btn-new btn-success" data-toggle="modal" disabled>Approved</button>';
		}else{
		    $aaData[$row_key]['MIH_STATUS']='<button data-target="#large_view" id="approved" class="btn-new btn-success" data-toggle="modal" systemId = "'.$aaData[$row_key]['MIH_SYS_ID'].'" onclick=javascript:quotation_status('.$aaData[$row_key]['MIH_SYS_ID'].')>Approved</button>';
		}
		
	    }else if($aaData[$row_key]['MIH_STATUS']=="Amended"){
		
		if (($ApproveButton == 'N') || ($AmendedButton == 'N')) {
		    $aaData[$row_key]['MIH_STATUS']='<button data-target="#large_view" id="amend" data-toggle="modal" class="btn-new btn-new-inverse" disabled>Amended</button>';
		}else{
		    $aaData[$row_key]['MIH_STATUS']='<button data-target="#large_view" id="amend" data-toggle="modal" class="btn-new btn-new-inverse" systemId = "'.$aaData[$row_key]['MIH_SYS_ID'].'" onclick=javascript:quotation_status('.$aaData[$row_key]['MIH_SYS_ID'].')>Amended</button>';
		}
	    
	    }if($aaData[$row_key]['MIH_STATUS']=="Rejected"){
		
		if ($ApproveButton == 'N') {
		    $aaData[$row_key]['MIH_STATUS']='<button  data-target="#large_view" id="rejected" data-toggle="modal" class="btn-new btn-danger" disabled>Rejected</button>';
		}else{
		    $aaData[$row_key]['MIH_STATUS']='<button  data-target="#large_view" id="rejected" data-toggle="modal" class="btn-new btn-danger" onclick=javascript:quotation_status('.$aaData[$row_key]['MIH_SYS_ID'].')>Rejected</button>';
		}
	    
	    }
	} 
	//material return
	if($tableName=="INVT_T_MR_HEAD_VIEW"){
	    $ApproveButton = $_POST['ApproveButton'];
	    $AmendedButton = $_POST['AmendedButton'];
	    $ActiveInsert = $_POST['ActiveInsert'];
	    $ActiveUpdate = $_POST['ActiveUpdate'];
	    
	    
	    if($aaData[$row_key]['MRH_STATUS']=="Incomplete"){
		
		//if ($ApproveButton == 'N') {
		if ($ActiveInsert == 'Y' || $ActiveUpdate == 'Y') {
		  $aaData[$row_key]['MRH_STATUS']='<button data-target="#large_view" id="created" class="btn-new btn-primary" data-toggle="modal" systemId = "'.$aaData[$row_key]['MRH_SYS_ID'].'" onclick=javascript:quotation_status('.$aaData[$row_key]['MRH_SYS_ID'].')>Incomplete</button>';
		}else{
		  $aaData[$row_key]['MRH_STATUS']='<button data-target="#large_view" id="created" class="btn-new btn-primary" data-toggle="modal" disabled>Incomplete</button>';
		}
	    
	    }else if($aaData[$row_key]['MRH_STATUS']=="Send for approval"){
		
		if ($ApproveButton == 'N') {
		    $aaData[$row_key]['MRH_STATUS']='<button data-target="#large_view" id="approve" class="btn-new btn-warning" data-toggle="modal" disabled>Approve ?</button>';
		}else{
		    $aaData[$row_key]['MRH_STATUS']='<button data-target="#large_view" id="approve" class="btn-new btn-warning" data-toggle="modal" systemId = "'.$aaData[$row_key]['MRH_SYS_ID'].'" onclick=javascript:quotation_status('.$aaData[$row_key]['MRH_SYS_ID'].')>Approve ?</button>';
		}
		
	    }else if($aaData[$row_key]['MRH_STATUS']=="Approved"){
		
		if ($ApproveButton == 'N') {
		    $aaData[$row_key]['MRH_STATUS']='<button data-target="#large_view" id="approved" class="btn-new btn-success" data-toggle="modal" disabled>Approved</button>';
		}else{
		    $aaData[$row_key]['MRH_STATUS']='<button data-target="#large_view" id="approved" class="btn-new btn-success" data-toggle="modal" systemId = "'.$aaData[$row_key]['MRH_SYS_ID'].'" onclick=javascript:quotation_status('.$aaData[$row_key]['MRH_SYS_ID'].')>Approved</button>';
		}
		
	    }else if($aaData[$row_key]['MRH_STATUS']=="Amended"){
		
		if (($ApproveButton == 'N') || ($AmendedButton == 'N')) {
		    $aaData[$row_key]['MRH_STATUS']='<button data-target="#large_view" id="amend" data-toggle="modal" class="btn-new btn-new-inverse" disabled>Amended</button>';
		}else{
		    $aaData[$row_key]['MRH_STATUS']='<button data-target="#large_view" id="amend" data-toggle="modal" class="btn-new btn-new-inverse" systemId = "'.$aaData[$row_key]['MRH_SYS_ID'].'" onclick=javascript:quotation_status('.$aaData[$row_key]['MRH_SYS_ID'].')>Amended</button>';
		}
	    
	    }if($aaData[$row_key]['MRH_STATUS']=="Rejected"){
		
		if ($ApproveButton == 'N') {
		    $aaData[$row_key]['MRH_STATUS']='<button  data-target="#large_view" id="rejected" data-toggle="modal" class="btn-new btn-danger" disabled>Rejected</button>';
		}else{
		    $aaData[$row_key]['MRH_STATUS']='<button  data-target="#large_view" id="rejected" data-toggle="modal" class="btn-new btn-danger" onclick=javascript:quotation_status('.$aaData[$row_key]['MRH_SYS_ID'].')>Rejected</button>';
		}
	    
	    }
	} 
	//Goods receipt transaction
	if($tableName=="INVT_T_GR_HEAD_VIEW"){
	    $ApproveButton = $_POST['ApproveButton'];
	    $AmendedButton = $_POST['AmendedButton'];
	    $ActiveInsert = $_POST['ActiveInsert'];
	    $ActiveUpdate = $_POST['ActiveUpdate']; 
	    
	    
	    if($aaData[$row_key]['GRH_STATUS']=="Incomplete"){
		//echo $ActiveInsert;
		//echo $ActiveUpdate;exit;
		//if ($ApproveButton == 'N') {
		if ($ActiveInsert == 'Y' || $ActiveUpdate == 'Y') {
		  $aaData[$row_key]['GRH_STATUS']='<button data-target="#large_view" id="created" class="btn-new btn-primary" data-toggle="modal" systemId = "'.$aaData[$row_key]['GRH_SYS_ID'].'" onclick=javascript:quotation_status('.$aaData[$row_key]['GRH_SYS_ID'].')>Incomplete</button>';
		}else{
		  $aaData[$row_key]['GRH_STATUS']='<button data-target="#large_view" id="created" class="btn-new btn-primary" data-toggle="modal" disabled>Incomplete</button>';
		}
	    
	    }else if($aaData[$row_key]['GRH_STATUS']=="Send for approval"){
		
		if ($ApproveButton == 'N') {
		    $aaData[$row_key]['GRH_STATUS']='<button data-target="#large_view" id="approve" class="btn-new btn-warning" data-toggle="modal" disabled>Approve ?</button>';
		}else{
		    $aaData[$row_key]['GRH_STATUS']='<button data-target="#large_view" id="approve" class="btn-new btn-warning" data-toggle="modal" systemId = "'.$aaData[$row_key]['GRH_SYS_ID'].'" onclick=javascript:quotation_status('.$aaData[$row_key]['GRH_SYS_ID'].')>Approve ?</button>';
		}
		
	    }else if($aaData[$row_key]['GRH_STATUS']=="Approved"){
		
		if ($ApproveButton == 'N') {
		    $aaData[$row_key]['GRH_STATUS']='<button data-target="#large_view" id="approved" class="btn-new btn-success" data-toggle="modal" disabled>Approved</button>';
		}else{
		    $aaData[$row_key]['GRH_STATUS']='<button data-target="#large_view" id="approved" class="btn-new btn-success" data-toggle="modal" systemId = "'.$aaData[$row_key]['GRH_SYS_ID'].'" onclick=javascript:quotation_status('.$aaData[$row_key]['GRH_SYS_ID'].')>Approved</button>';
		}
		
	    }else if($aaData[$row_key]['GRH_STATUS']=="Amended"){
		
		if (($ApproveButton == 'N') || ($AmendedButton == 'N')) {
		    $aaData[$row_key]['GRH_STATUS']='<button data-target="#large_view" id="amend" data-toggle="modal" class="btn-new btn-new-inverse" disabled>Amended</button>';
		}else{
		    $aaData[$row_key]['GRH_STATUS']='<button data-target="#large_view" id="amend" data-toggle="modal" class="btn-new btn-new-inverse" systemId = "'.$aaData[$row_key]['GRH_SYS_ID'].'" onclick=javascript:quotation_status('.$aaData[$row_key]['GRH_SYS_ID'].')>Amended</button>';
		}
	    
	    }if($aaData[$row_key]['GRH_STATUS']=="Rejected"){
		
		if ($ApproveButton == 'N') {
		    $aaData[$row_key]['GRH_STATUS']='<button  data-target="#large_view" id="rejected" data-toggle="modal" class="btn-new btn-danger" disabled>Rejected</button>';
		}else{
		    $aaData[$row_key]['GRH_STATUS']='<button  data-target="#large_view" id="rejected" data-toggle="modal" class="btn-new btn-danger" onclick=javascript:quotation_status('.$aaData[$row_key]['GRH_SYS_ID'].')>Rejected</button>';
		}
	    
	    }
	}
	
	//Goods receipt costing transaction
	if($tableName=="INVT_T_GR_HEAD_FINC_VIEW"){
	    $ApproveButton = $_POST['ApproveButton'];
	    $AmendedButton = $_POST['AmendedButton'];
	    $ActiveInsert = $_POST['ActiveInsert'];
	    $ActiveUpdate = $_POST['ActiveUpdate'];
	      
	    if($aaData[$row_key]['GRH_F_STATUS']=="Incomplete"){
		
		//if ($ApproveButton == 'N') {
		if ($ActiveInsert == 'Y' || $ActiveUpdate == 'Y') {
		  $aaData[$row_key]['GRH_F_STATUS']='<button data-target="#large_view" id="created" class="btn-new btn-primary" data-toggle="modal" systemId = "'.$aaData[$row_key]['GRH_SYS_ID'].'" onclick=javascript:quotation_status('.$aaData[$row_key]['GRH_SYS_ID'].')>Incomplete</button>';
		}else{
		    $aaData[$row_key]['GRH_F_STATUS']='<button data-target="#large_view" id="created" class="btn-new btn-primary" data-toggle="modal" disabled>Incomplete</button>';
		}
	    
	    }else if($aaData[$row_key]['GRH_F_STATUS']=="Send for approval"){
		
		if ($ApproveButton == 'N') {
		    $aaData[$row_key]['GRH_F_STATUS']='<button data-target="#large_view" id="approve" class="btn-new btn-warning" data-toggle="modal" disabled>Approve ?</button>';
		}else{
		    $aaData[$row_key]['GRH_F_STATUS']='<button data-target="#large_view" id="approve" class="btn-new btn-warning" data-toggle="modal" systemId = "'.$aaData[$row_key]['GRH_SYS_ID'].'" onclick=javascript:quotation_status('.$aaData[$row_key]['GRH_SYS_ID'].')>Approve ?</button>';
		}
		
	    }else if($aaData[$row_key]['GRH_F_STATUS']=="Approved"){
		
		if ($ApproveButton == 'N') {
		    $aaData[$row_key]['GRH_F_STATUS']='<button data-target="#large_view" id="approved" class="btn-new btn-success" data-toggle="modal" disabled>Approved</button>';
		}else{
		    $aaData[$row_key]['GRH_F_STATUS']='<button data-target="#large_view" id="approved" class="btn-new btn-success" data-toggle="modal" systemId = "'.$aaData[$row_key]['GRH_SYS_ID'].'" onclick=javascript:quotation_status('.$aaData[$row_key]['GRH_SYS_ID'].')>Approved</button>';
		}
		
	    }else if($aaData[$row_key]['GRH_F_STATUS']=="Amended"){
		
		if (($ApproveButton == 'N') || ($AmendedButton == 'N')) {
		    $aaData[$row_key]['GRH_F_STATUS']='<button data-target="#large_view" id="amend" data-toggle="modal" class="btn-new btn-new-inverse" disabled>Amended</button>';
		}else{
		    $aaData[$row_key]['GRH_F_STATUS']='<button data-target="#large_view" id="amend" data-toggle="modal" class="btn-new btn-new-inverse" systemId = "'.$aaData[$row_key]['GRH_SYS_ID'].'" onclick=javascript:quotation_status('.$aaData[$row_key]['GRH_SYS_ID'].')>Amended</button>';
		}
	    
	    }if($aaData[$row_key]['GRH_F_STATUS']=="Rejected"){
		
		if ($ApproveButton == 'N') {
		    $aaData[$row_key]['GRH_F_STATUS']='<button  data-target="#large_view" id="rejected" data-toggle="modal" class="btn-new btn-danger" disabled>Rejected</button>';
		}else{
		    $aaData[$row_key]['GRH_F_STATUS']='<button  data-target="#large_view" id="rejected" data-toggle="modal" class="btn-new btn-danger" onclick=javascript:quotation_status('.$aaData[$row_key]['GRH_SYS_ID'].')>Rejected</button>';
		}
	    
	    }
	}
	
	//inspection transaction
	if($tableName=="INVT_T_INSP_HEAD_VIEW"){
	    $ApproveButton = $_POST['ApproveButton'];
	    $AmendedButton = $_POST['AmendedButton'];
	    $ActiveInsert = $_POST['ActiveInsert'];
	    $ActiveUpdate = $_POST['ActiveUpdate'];
	    
	    
	    if($aaData[$row_key]['INH_STATUS']=="Incomplete"){
		
		//if ($ApproveButton == 'N') {
		if ($ActiveInsert == 'Y' || $ActiveUpdate == 'Y') {
		  $aaData[$row_key]['INH_STATUS']='<button data-target="#large_view" id="created" class="btn-new btn-primary" data-toggle="modal" systemId = "'.$aaData[$row_key]['INH_SYS_ID'].'" onclick=javascript:quotation_status('.$aaData[$row_key]['INH_SYS_ID'].')>Incomplete</button>';
		}else{
		  $aaData[$row_key]['INH_STATUS']='<button data-target="#large_view" id="created" class="btn-new btn-primary" data-toggle="modal" disabled>Incomplete</button>';
		}
	    
	    }else if($aaData[$row_key]['INH_STATUS']=="Send for approval"){
		
		if ($ApproveButton == 'N') {
		    $aaData[$row_key]['INH_STATUS']='<button data-target="#large_view" id="approve" class="btn-new btn-warning" data-toggle="modal" disabled>Approve ?</button>';
		}else{
		    $aaData[$row_key]['INH_STATUS']='<button data-target="#large_view" id="approve" class="btn-new btn-warning" data-toggle="modal" systemId = "'.$aaData[$row_key]['INH_SYS_ID'].'" onclick=javascript:quotation_status('.$aaData[$row_key]['INH_SYS_ID'].')>Approve ?</button>';
		}
		
	    }else if($aaData[$row_key]['INH_STATUS']=="Approved"){
		
		if ($ApproveButton == 'N') {
		    $aaData[$row_key]['INH_STATUS']='<button data-target="#large_view" id="approved" class="btn-new btn-success" data-toggle="modal" disabled>Approved</button>';
		}else{
		    $aaData[$row_key]['INH_STATUS']='<button data-target="#large_view" id="approved" class="btn-new btn-success" data-toggle="modal" systemId = "'.$aaData[$row_key]['INH_SYS_ID'].'" onclick=javascript:quotation_status('.$aaData[$row_key]['INH_SYS_ID'].')>Approved</button>';
		}
		
	    }else if($aaData[$row_key]['INH_STATUS']=="Amended"){
		
		if (($ApproveButton == 'N') || ($AmendedButton == 'N')) {
		    $aaData[$row_key]['INH_STATUS']='<button data-target="#large_view" id="amend" data-toggle="modal" class="btn-new btn-new-inverse" disabled>Amended</button>';
		}else{
		    $aaData[$row_key]['INH_STATUS']='<button data-target="#large_view" id="amend" data-toggle="modal" class="btn-new btn-new-inverse" systemId = "'.$aaData[$row_key]['INH_SYS_ID'].'" onclick=javascript:quotation_status('.$aaData[$row_key]['INH_SYS_ID'].')>Amended</button>';
		}
	    
	    }if($aaData[$row_key]['INH_STATUS']=="Rejected"){
		
		if ($ApproveButton == 'N') {
		    $aaData[$row_key]['INH_STATUS']='<button  data-target="#large_view" id="rejected" data-toggle="modal" class="btn-new btn-danger" disabled>Rejected</button>';
		}else{
		    $aaData[$row_key]['INH_STATUS']='<button  data-target="#large_view" id="rejected" data-toggle="modal" class="btn-new btn-danger" onclick=javascript:quotation_status('.$aaData[$row_key]['INH_SYS_ID'].')>Rejected</button>';
		}
	    
	    }
	}
//For Sales Module Begin
//ForCustomer Master Party URL string start
//if($tableName=='SALE_M_CUSTOMER_VIEW'){
//if(strlen($aaData[$row_key]['CUST_PARTY_URL']) > 15){
//   $originalValue = $aaData[$row_key]['CUST_PARTY_URL'];
//   $smallValue = substr($originalValue, 0,15).'..';
//   $aaData[$row_key]['CUST_PARTY_URL'] = "<span title='$originalValue' class='tool'>".$smallValue."</span>";
//   }
//}
//ForCustomer Master Party URL string end   
if($tableName=='SALE_T_OPPORT_HEAD_VIEW' || $tableName=='SALE_T_LEAD_HEAD_VIEW' ||  $tableName=='SALE_T_QUOTE_HEAD_VIEW' || $tableName=='SALE_T_ORDER_HEAD_VIEW' || $tableName=='SALE_T_DN_HEAD_VIEW' || $tableName=='SALE_T_INV_HEAD_VIEW' || $tableName=='SALE_T_REPLACE_HEAD_VIEW')
{
  $ApproveButton = $_POST['ApproveButton'];
  $AmendButton = $_POST['AmendButton'];	  
  $ActiveInsert = $_POST['ActiveInsert'];
  $ActiveUpdate = $_POST['ActiveUpdate'];   
switch ($tableName) {
    case "SALE_T_OPPORT_HEAD_VIEW":
        $checkVar='OPH_STATUS';
	$checkVar1='OPH_SYS_ID';	 
        break;
    case "SALE_T_LEAD_HEAD_VIEW":
        $checkVar='LH_STATUS';
	$checkVar1='LH_SYS_ID';
	break;
    case "SALE_T_QUOTE_HEAD_VIEW":
	  $checkVar='QH_STATUS';
	  $checkVar1='QH_SYS_ID';
	  break;
	   case "SALE_T_REPLACE_HEAD_VIEW":
	  $checkVar='STATUS';
	  $checkVar1='RH_SYS_ID';
	  break;
    case "SALE_T_ORDER_HEAD_VIEW":
	  $checkVar='OH_STATUS';
	  $checkVar1='OH_SYS_ID';
	  break;
    case "SALE_T_DN_HEAD_VIEW":
	  $checkVar='DH_STATUS';
	  $checkVar1='DH_SYS_ID';
	  $OL_OH_SYS_ID='DH_REF_SYS_ID';
	  break;
    case "SALE_T_INV_HEAD_VIEW":
	  $checkVar='IH_STATUS';
	  $checkVar1='IH_SYS_ID';
	  break;
    default:
	
   
}

  if($aaData[$row_key][$checkVar]=="Incomplete"){
		
		if ($ActiveInsert == 'Y' || $ActiveUpdate == 'Y') {
		    $aaData[$row_key][$checkVar]='<center><button data-target="#large_view" id="created" class="btn-new btn-primary statusWidth" data-toggle="modal" systemId = "'.$aaData[$row_key][$checkVar1].'" onclick=javascript:enquiry_status('.$aaData[$row_key][$checkVar1].')>Incomplete</button></center>';
		}else{
		  $aaData[$row_key][$checkVar]='<center><button id="created" class="btn-new btn-primary statusWidth">Incomplete</button></center>';
		}
	    
	    }else if($aaData[$row_key][$checkVar]=="Send for approval"){
		
		if ($ApproveButton == 'N') {
		    $aaData[$row_key][$checkVar]='<center><button data-target="#large_view" id="approve" class="btn-new btn-warning statusWidth" data-toggle="modal" disabled>Approve ?</button></center>';
		}else{
		  if($tableName=='SALE_T_DN_HEAD'){
		    $aaData[$row_key][$checkVar]='<center><button data-target="#large_view" id="approve" class="btn-new btn-warning statusWidth" data-toggle="modal" refsysId = "'.$aaData[$row_key][$OL_OH_SYS_ID].'" systemId = "'.$aaData[$row_key][$checkVar1].'" onclick="enquiry_status($(this));">Approve ?</button></center>';
		  }else{
		    $aaData[$row_key][$checkVar]='<center><button data-target="#large_view" id="approve" class="btn-new btn-warning statusWidth" data-toggle="modal" systemId = "'.$aaData[$row_key][$checkVar1].'" onclick=javascript:enquiry_status('.$aaData[$row_key][$checkVar1].')>Approve ?</button></center>';
		  }
		}
		
	    }else if($aaData[$row_key][$checkVar]=="Approved"){
		
		if ($AmendButton == 'N') {
		//echo"anand"; exit();
		   $aaData[$row_key][$checkVar]='<center><button data-target="#large_view" id="approved" class="btn-new btn-success statusWidth" data-toggle="modal" disabled>Approved</button></center>';
			//exit();
		}
		else{
		    $aaData[$row_key][$checkVar]='<center><button data-target="#large_view" id="approved" class="btn-new btn-success statusWidth" data-toggle="modal" systemId = "'.$aaData[$row_key][$checkVar1].'" onclick=javascript:enquiry_status('.$aaData[$row_key][$checkVar1].')>Approved</button></center>';
		}
		
	    }else if($aaData[$row_key][$checkVar]=="Amended"){
		
		//if ($AmendButton == 'N') {
		//    $aaData[$row_key][$checkVar]='<button data-target="#large_view" id="amend" data-toggle="modal" class="btn-new btn-new-inverse statusWidth" disabled>Amended</button>';
		//}else{
		    $aaData[$row_key][$checkVar]='<center><button data-target="#large_view" id="amend" data-toggle="modal" class="btn-new btn-new-inverse statusWidth" systemId = "'.$aaData[$row_key][$checkVar1].'" onclick=javascript:enquiry_status('.$aaData[$row_key][$checkVar1].')>Amended</button></center>';
		//}
	    
	    }if($aaData[$row_key][$checkVar]=="Rejected"){
		
		if ($ApproveButton == 'N') {
		     $aaData[$row_key][$checkVar]='<center><button  data-target="#large_view" id="rejected" data-toggle="modal" class="btn-new btn-danger statusWidth" disabled>Rejected</button></center>';
		}else{
		    $aaData[$row_key][$checkVar]='<center><button  data-target="#large_view" id="rejected" data-toggle="modal" class="btn-new btn-danger statusWidth" onclick=javascript:enquiry_status('.$aaData[$row_key][$checkVar1].')>Rejected</button></center>';
		}
	    
	    }

}	
//For Sales Module End

    //Logistics Module Start
    if($tableName=='LOGI_T_SCHEDULE_HEAD' || $tableName=='LOGI_T_MEASURE_HEAD_VIEW' || $tableName=='LOGI_T_JOB_REQ_HEAD_VIEW')
    {
	$ApproveButton = $_POST['ApproveButton'];
	$AmendButton = $_POST['AmendButton'];
	$ActiveInsert = $_POST['ActiveInsert'];
	$ActiveUpdate = $_POST['ActiveUpdate'];
	switch ($tableName)
	{
	    case "LOGI_T_SCHEDULE_HEAD":
	    $checkStatus='LSH_STATUS';
	    $checkID='LSH_SYS_ID';  
	    break;
	    case "LOGI_T_MEASURE_HEAD_VIEW":
	    $checkStatus='MH_STATUS';
	    $checkID='MH_SYS_ID';
	    break;
	    case "LOGI_T_JOB_REQ_HEAD_VIEW":
	    $checkStatus='JRH_STATUS';
	    $checkID='JRH_SYS_ID';
	    break;
	    default:
	}
	
	if($aaData[$row_key][$checkStatus]=="Incomplete")
	{
	    //if ($ActiveInsert == 'Y' && $ActiveUpdate == 'Y') {
	    if ($ActiveInsert == 'Y' || $ActiveUpdate == 'Y') {
	      $aaData[$row_key][$checkStatus]='<center><button data-target="#large_view" id="created" class="btn-new btn-primary statusWidth" data-toggle="modal" systemId = "'.$aaData[$row_key][$checkID].'" onclick=javascript:quotation_status('.$aaData[$row_key][$checkID].')>Incomplete</button></center>'; 
	    }else{
	      $aaData[$row_key][$checkStatus]='<center><button data-target="#large_view" id="created" class="btn-new btn-primary statusWidth" disabled >Incomplete</button></center>'; 

	    }
	}
	else if($aaData[$row_key][$checkStatus]=="Send for approval")
	{
	    if ($ApproveButton == 'N')
	    {
		$aaData[$row_key][$checkStatus]='<center><button data-target="#large_view" id="approve" class="btn-new btn-warning statusWidth" data-toggle="modal" disabled>Approve ?</button></center>';
	    }
	    else
	    {
		$aaData[$row_key][$checkStatus]='<center><button data-target="#large_view" id="approve" class="btn-new btn-warning statusWidth" data-toggle="modal" systemId = "'.$aaData[$row_key][$checkID].'" onclick=javascript:quotation_status('.$aaData[$row_key][$checkID].')>Approve ?</button></center>';
	    }
	}
	else if($aaData[$row_key][$checkStatus]=="Approved")
	{
	    if ($AmendButton == 'N')
	    {
		$aaData[$row_key][$checkStatus]='<center><button data-target="#large_view" id="approved" class="btn-new btn-success statusWidth" data-toggle="modal" disabled>Approved</button></center>';
	    }
	    else
	    {
		$aaData[$row_key][$checkStatus]='<center><button data-target="#large_view" id="approved" class="btn-new btn-success statusWidth" data-toggle="modal" systemId = "'.$aaData[$row_key][$checkID].'" onclick=javascript:quotation_status('.$aaData[$row_key][$checkID].')>Approved</button></center>';
	    }
	}
	else if($aaData[$row_key][$checkStatus]=="Amended")
	{
	    //if($ApproveButton == 'N')
	    //{
		//$aaData[$row_key][$checkStatus]='<button data-target="#large_view" id="amend" data-toggle="modal" class="btn-new btn-new-inverse" disabled>Amended</button>';
	    //}else	{
	       $aaData[$row_key][$checkStatus]='<center><button data-target="#large_view" id="amend" data-toggle="modal" class="btn-new btn-new-inverse  statusWidth" systemId = "'.$aaData[$row_key][$checkID].'" onclick=javascript:quotation_status('.$aaData[$row_key][$checkID].')>Amended</button></center>';
	    //}
	}
	if($aaData[$row_key][$checkStatus]=="Rejected")
	{
	    if ($ApproveButton == 'N')
	    {
		$aaData[$row_key][$checkStatus]='<center><button  data-target="#large_view" id="rejected" data-toggle="modal" class="btn-new btn-danger statusWidth" disabled>Rejected</button></center>';
	    }
	    else
	    {
		$aaData[$row_key][$checkStatus]='<center><button  data-target="#large_view" id="rejected" data-toggle="modal" class="btn-new btn-danger statusWidth" onclick=javascript:quotation_status('.$aaData[$row_key][$checkID].')>Rejected</button></center>';
	    }
	}
    }
    //Logistics Module End

//Finanace Module Start


if($tableName=='FINC_T_VOUCHER_HEAD_VIEW')
{
  $ApproveButton = $_POST['ApproveButton'];
  $InsertButton = $_POST['ActiveInsert'];
  $UpdateButton = $_POST['ActiveUpdate'];
    switch ($tableName) {
	case "FINC_T_VOUCHER_HEAD_VIEW":
	    $checkStatus='VH_STATUS';
	    $checkID='VH_SYS_ID';  
	    break;
	default:
 
   
}
  $aaData[$row_key]['VH_AMOUNT_FC']=number_format($aaData[$row_key]['VH_AMOUNT_FC'],2);
  if($aaData[$row_key][$checkStatus]=="Incomplete"){
  
      //if ($ApproveButton == 'N')
      if ($InsertButton == 'Y' || $UpdateButton == 'Y') 
      {
	$aaData[$row_key][$checkStatus]='<button data-target="#large_view" id="created" class="btn-new btn-primary statusWidth" data-toggle="modal" systemId = "'.$aaData[$row_key][$checkID].'" onclick=javascript:voucherentry_status('.$aaData[$row_key][$checkID].')>Incomplete</button>';
      }
      else
      {
	$aaData[$row_key][$checkStatus]='<button data-target="#large_view" id="created" class="btn-new btn-primary statusWidth" data-toggle="modal" disabled>Incomplete</button>';
      }

   }
      else if($aaData[$row_key][$checkStatus]=="Send for approval"){
  
 if ($ApproveButton == 'N') {
     $aaData[$row_key][$checkStatus]='<button data-target="#large_view" id="approve" class="btn-new btn-warning statusWidth" data-toggle="modal" disabled>Approve ?</button>';
 }else{
     $aaData[$row_key][$checkStatus]='<button data-target="#large_view" id="approve" class="btn-new btn-warning statusWidth" data-toggle="modal" systemId = "'.$aaData[$row_key][$checkID].'" onclick=javascript:voucherentry_status('.$aaData[$row_key][$checkID].')>Approve ?</button>';
 }
  
      }else if($aaData[$row_key][$checkStatus]=="Approved"){
   
   if ($ApproveButton == 'N') {
 
      $aaData[$row_key][$checkStatus]='<button data-target="#large_view" id="approved" class="btn-new btn-success statusWidth" data-toggle="modal" disabled>Approved</button>';
  
   }
   else{
       $aaData[$row_key][$checkStatus]='<button data-target="#large_view" id="approved" class="btn-new btn-success statusWidth" data-toggle="modal" systemId = "'.$aaData[$row_key][$checkID].'" onclick=javascript:voucherentry_status('.$aaData[$row_key][$checkID].')>Approved</button>';
   }
   
      }else if($aaData[$row_key][$checkStatus]=="Amended"){
   
   if ($ApproveButton == 'N') {
       $aaData[$row_key][$checkStatus]='<button data-target="#large_view" id="amend" data-toggle="modal" class="btn-new btn-new-inverse statusWidth" disabled>Amended</button>';
   }else{
       $aaData[$row_key][$checkStatus]='<button data-target="#large_view" id="amend" data-toggle="modal" class="btn-new btn-new-inverse statusWidth" systemId = "'.$aaData[$row_key][$checkID].'" onclick=javascript:voucherentry_status('.$aaData[$row_key][$checkID].')>Amended</button>';
   }
      
      }if($aaData[$row_key][$checkStatus]=="Rejected"){
   
   if ($ApproveButton == 'N') {
        $aaData[$row_key][$checkStatus]='<button  data-target="#large_view" id="rejected" data-toggle="modal" class="btn-new btn-danger statusWidth" disabled>Rejected</button>';
   }else{
       $aaData[$row_key][$checkStatus]='<button  data-target="#large_view" id="rejected" data-toggle="modal" class="btn-new btn-danger statusWidth" onclick=javascript:voucherentry_status('.$aaData[$row_key][$checkID].')>Rejected</button>';
   }
      
      }

}

//Finance Module End

//Procurement Start
//Supplier Master Stagging Start
	if($tableName=="PROC_M_SUPL_STAG"){
	    $AmendButton = $_POST['AmendButton'];
	    $ApproveButton = $_POST['ApproveButton'];
	    if($aaData[$row_key]['SUPL_STATUS']==""){
		if ($ApproveButton == 'N') {
		    $aaData[$row_key]['SUPL_STATUS']='<button data-target="#large_view" id="approve" class="btn-new btn-warning statusWidth" data-toggle="modal" disabled>Approve ?</button>';
		}else{
		    $aaData[$row_key]['SUPL_STATUS']='<button data-target="#large_view" id="approve" class="btn-new btn-warning statusWidth" data-toggle="modal" systemId = "'.$aaData[$row_key]['SUPL_SYS_ID'].'" acCode = "'.$aaData[$row_key]['SUPL_AC_CODE'].'" partyDesc = "'.$aaData[$row_key]['SUPL_AC_DESC'].'" onclick="getSupplierApprove($(this));">Approve ?</button>';
		}
	    }
	    if($aaData[$row_key]['SUPL_STATUS']=="APPROVED"){
		    $aaData[$row_key]['SUPL_STATUS']='<button data-target="#large_view" id="approve" class="btn-new btn-success statusWidth" data-toggle="modal" disabled>Approved</button>';
	    }
	    if($aaData[$row_key]['SUPL_STATUS']=="REJECTED"){
		if ($ApproveButton == 'N') {
		    $aaData[$row_key]['SUPL_STATUS']='<button  data-target="#large_view" id="rejected" data-toggle="modal" class="btn-new btn-danger statusWidth" disabled>Rejected</button>';
		}else{
		    $aaData[$row_key]['SUPL_STATUS']='<button  data-target="#large_view" id="rejected" data-toggle="modal" class="btn-new btn-danger statusWidth" systemId = "'.$aaData[$row_key]['SUPL_SYS_ID'].'" acCode = "'.$aaData[$row_key]['SUPL_AC_CODE'].'" partyDesc= "'.$aaData[$row_key]['SUPL_AC_DESC'].'" onclick="getSupplierApprove($(this));">Rejected</button>';
		}
	    }
	}

//Supplier Master Stagging End
//Purchase Request Transaction Start
	if($tableName=="PROC_T_PREQ_HEAD_VIEW"){
	    $ApproveButton = $_POST['ApproveButton'];	 
	
	    if($aaData[$row_key]['PRQH_STATUS']=="Incomplete"){
		
		//if ($ApproveButton == 'N') {
		   // $aaData[$row_key]['PRQH_STATUS']='<button data-target="#large_view" id="created" class="btn-new btn-primary statusWidth" data-toggle="modal" disabled>Incomplete</button>';
		//}else{
		    $aaData[$row_key]['PRQH_STATUS']='<button data-target="#large_view" id="created" class="btn-new btn-primary statusWidth" data-toggle="modal" systemId = "'.$aaData[$row_key]['PRQH_SYS_ID'].'" onclick=javascript:Purch_request_status('.$aaData[$row_key]['PRQH_SYS_ID'].')>Incomplete</button>';
		//}
	    
	    }else if($aaData[$row_key]['PRQH_STATUS']=="Send for approval"){
		
		if ($ApproveButton == 'N') {
		    $aaData[$row_key]['PRQH_STATUS']='<button data-target="#large_view" id="approve" class="btn-new btn-warning statusWidth" data-toggle="modal" disabled>Approve ?</button>';
		}else{
		    $aaData[$row_key]['PRQH_STATUS']='<button data-target="#large_view" id="approve" class="btn-new btn-warning statusWidth" data-toggle="modal" systemId = "'.$aaData[$row_key]['PRQH_SYS_ID'].'" onclick=javascript:Purch_request_status('.$aaData[$row_key]['PRQH_SYS_ID'].')>Approve ?</button>';
		}
		
	    }else if($aaData[$row_key]['PRQH_STATUS']=="Approved"){
		
		if ($ApproveButton == 'N') {
		    $aaData[$row_key]['PRQH_STATUS']='<button data-target="#large_view" id="approved" class="btn-new btn-success statusWidth" data-toggle="modal" disabled>Approved</button>';
		}else{
		    $aaData[$row_key]['PRQH_STATUS']='<button data-target="#large_view" id="approved" class="btn-new btn-success statusWidth" data-toggle="modal" systemId = "'.$aaData[$row_key]['PRQH_SYS_ID'].'" onclick=javascript:Purch_request_status('.$aaData[$row_key]['PRQH_SYS_ID'].')>Approved</button>';
		}
		
	    }else if($aaData[$row_key]['PRQH_STATUS']=="Amended"){
		
		if (($ApproveButton == 'N') || ($AmendedButton == 'N')) {
		    $aaData[$row_key]['PRQH_STATUS']='<button data-target="#large_view" id="amend" data-toggle="modal" class="btn-new btn-new-inverse statusWidth" disabled>Amended</button>';
		}else{
		    $aaData[$row_key]['PRQH_STATUS']='<button data-target="#large_view" id="amend" data-toggle="modal" class="btn-new btn-new-inverse statusWidth" systemId = "'.$aaData[$row_key]['PRQH_SYS_ID'].'" onclick=javascript:Purch_request_status('.$aaData[$row_key]['PRQH_SYS_ID'].')>Amended</button>';
		}
	    
	    }if($aaData[$row_key]['PRQH_STATUS']=="Rejected"){
		
		if ($ApproveButton == 'N') {
		    $aaData[$row_key]['PRQH_STATUS']='<button  data-target="#large_view" id="rejected" data-toggle="modal" class="btn-new btn-danger statusWidth" disabled>Rejected</button>';
		}else{
		    $aaData[$row_key]['PRQH_STATUS']='<button  data-target="#large_view" id="rejected" data-toggle="modal" class="btn-new btn-danger statusWidth" onclick=javascript:Purch_request_status('.$aaData[$row_key]['PRQH_SYS_ID'].')>Rejected</button>';
		}
	    
	    }
	}
	//Purchase Request Transaction End
	
	//Purchase Enquiry Transaction Start
	if($tableName=="PROC_T_ENQ_HEAD_VIEW"){
	  
	    $ApproveButton = $_POST['ApproveButton'];	 
	
	    if($aaData[$row_key]['EQH_STATUS']=="Incomplete"){
		
		//if ($ApproveButton == 'N') {
		    //$aaData[$row_key]['EQH_STATUS']='<button data-target="#large_view" id="created" class="btn-new btn-primary statusWidth" data-toggle="modal" disabled>Incomplete</button>';
		//}else{
		    $aaData[$row_key]['EQH_STATUS']='<button data-target="#large_view" id="created" class="btn-new btn-primary statusWidth" data-toggle="modal" systemId = "'.$aaData[$row_key]['EQH_SYS_ID'].'" onclick=javascript:enquiry_status('.$aaData[$row_key]['EQH_SYS_ID'].')>Incomplete</button>';
		//}
	    
	    }else if($aaData[$row_key]['EQH_STATUS']=="Send for approval"){
		
		if ($ApproveButton == 'N') {
		    $aaData[$row_key]['EQH_STATUS']='<button data-target="#large_view" id="approve" class="btn-new btn-warning statusWidth" data-toggle="modal" disabled>Approve ?</button>';
		}else{
		    $aaData[$row_key]['EQH_STATUS']='<button data-target="#large_view" id="approve" class="btn-new btn-warning statusWidth" data-toggle="modal" systemId = "'.$aaData[$row_key]['EQH_SYS_ID'].'" onclick=javascript:enquiry_status('.$aaData[$row_key]['EQH_SYS_ID'].')>Approve ?</button>';
		}
		
	    }else if($aaData[$row_key]['EQH_STATUS']=="Approved"){
		
		if ($ApproveButton == 'N') {
		    $aaData[$row_key]['EQH_STATUS']='<button data-target="#large_view" id="approved" class="btn-new btn-success statusWidth" data-toggle="modal" disabled>Approved</button>';
		}else{
		    $aaData[$row_key]['EQH_STATUS']='<button data-target="#large_view" id="approved" class="btn-new btn-success statusWidth" data-toggle="modal" systemId = "'.$aaData[$row_key]['EQH_SYS_ID'].'" onclick=javascript:enquiry_status('.$aaData[$row_key]['EQH_SYS_ID'].')>Approved</button>';
		}
		
	    }else if($aaData[$row_key]['EQH_STATUS']=="Amended"){
		
		if (($ApproveButton == 'N') || ($AmendedButton == 'N')) {
		    $aaData[$row_key]['EQH_STATUS']='<button data-target="#large_view" id="amend" data-toggle="modal" class="btn-new btn-new-inverse statusWidth" disabled>Amended</button>';
		}else{
		    $aaData[$row_key]['EQH_STATUS']='<button data-target="#large_view" id="amend" data-toggle="modal" class="btn-new btn-new-inverse statusWidth" systemId = "'.$aaData[$row_key]['EQH_SYS_ID'].'" onclick=javascript:enquiry_status('.$aaData[$row_key]['EQH_SYS_ID'].')>Amended</button>';
		}
	    
	    }if($aaData[$row_key]['EQH_STATUS']=="Rejected"){
		
		if ($ApproveButton == 'N') {
		    $aaData[$row_key]['EQH_STATUS']='<button  data-target="#large_view" id="rejected" data-toggle="modal" class="btn-new btn-danger statusWidth" disabled>Rejected</button>';
		}else{
		    $aaData[$row_key]['EQH_STATUS']='<button  data-target="#large_view" id="rejected" data-toggle="modal" class="btn-new btn-danger statusWidth" onclick=javascript:enquiry_status('.$aaData[$row_key]['EQH_SYS_ID'].')>Rejected</button>';
		}
	    
	    }
	}
	//Purchase Enquiry Transaction End
	
	//Purchase Quotation Transaction start	
	if($tableName=="PROC_T_QUOTE_HEAD_VIEW"){
	    $ApproveButton = $_POST['ApproveButton'];	 
	
	    if($aaData[$row_key]['PQH_STATUS']=="Incomplete"){
		
		//if ($ApproveButton == 'N') {
		   // $aaData[$row_key]['PQH_STATUS']='<button data-target="#large_view" id="created" class="btn-new btn-primary statusWidth" data-toggle="modal" disabled>Incomplete</button>';
		//}else{
		    $aaData[$row_key]['PQH_STATUS']='<button data-target="#large_view" id="created" class="btn-new btn-primary statusWidth" data-toggle="modal" systemId = "'.$aaData[$row_key]['PQH_SYS_ID'].'" onclick=javascript:quotation_status('.$aaData[$row_key]['PQH_SYS_ID'].')>Incomplete</button>';
		//}
	    
	    }else if($aaData[$row_key]['PQH_STATUS']=="Send for approval"){
		
		if ($ApproveButton == 'N') {
		    $aaData[$row_key]['PQH_STATUS']='<button data-target="#large_view" id="approve" class="btn-new btn-warning statusWidth" data-toggle="modal" disabled>Approve ?</button>';
		}else{
		    $aaData[$row_key]['PQH_STATUS']='<button data-target="#large_view" id="approve" class="btn-new btn-warning statusWidth" data-toggle="modal" systemId = "'.$aaData[$row_key]['PQH_SYS_ID'].'" onclick=javascript:quotation_status('.$aaData[$row_key]['PQH_SYS_ID'].')>Approve ?</button>';
		}
		
	    }else if($aaData[$row_key]['PQH_STATUS']=="Approved"){
		
		if ($ApproveButton == 'N') {
		    $aaData[$row_key]['PQH_STATUS']='<button data-target="#large_view" id="approved" class="btn-new btn-success statusWidth" data-toggle="modal" disabled>Approved</button>';
		}else{
		    $aaData[$row_key]['PQH_STATUS']='<button data-target="#large_view" id="approved" class="btn-new btn-success statusWidth" data-toggle="modal" systemId = "'.$aaData[$row_key]['PQH_SYS_ID'].'" onclick=javascript:quotation_status('.$aaData[$row_key]['PQH_SYS_ID'].')>Approved</button>';
		}
		
	    }else if($aaData[$row_key]['PQH_STATUS']=="Amended"){
		
		if (($ApproveButton == 'N') || ($AmendedButton == 'N')) {
		    $aaData[$row_key]['PQH_STATUS']='<button data-target="#large_view" id="amend" data-toggle="modal" class="btn-new btn-new-inverse statusWidth" disabled>Amended</button>';
		}else{
		    $aaData[$row_key]['PQH_STATUS']='<button data-target="#large_view" id="amend" data-toggle="modal" class="btn-new btn-new-inverse statusWidth" systemId = "'.$aaData[$row_key]['PQH_SYS_ID'].'" onclick=javascript:quotation_status('.$aaData[$row_key]['PQH_SYS_ID'].')>Amended</button>';
		}
	    
	    }if($aaData[$row_key]['PQH_STATUS']=="Rejected"){
		
		if ($ApproveButton == 'N') {
		    $aaData[$row_key]['PQH_STATUS']='<button  data-target="#large_view" id="rejected" data-toggle="modal" class="btn-new btn-danger statusWidth" disabled>Rejected</button>';
		}else{
		    $aaData[$row_key]['PQH_STATUS']='<button  data-target="#large_view" id="rejected" data-toggle="modal" class="btn-new btn-danger statusWidth" onclick=javascript:quotation_status('.$aaData[$row_key]['PQH_SYS_ID'].')>Rejected</button>';
		}
	    
	    }
	}
	//Purchase Quotation Transaction End
	
	//Purchase Order Transaction start
	if($tableName=="PROC_T_PO_HEAD_VIEW"){
	    $ApproveButton = $_POST['ApproveButton'];	 
	
	    if($aaData[$row_key]['POH_STATUS']=="Incomplete"){
		
		//if ($ApproveButton == 'N') {
		 //   $aaData[$row_key]['POH_STATUS']='<button data-target="#large_view" id="created" class="btn-new btn-primary statusWidth" data-toggle="modal" disabled>Incomplete</button>';
		//}else{
		    $aaData[$row_key]['POH_STATUS']='<button data-target="#large_view" id="created" class="btn-new btn-primary statusWidth" data-toggle="modal" systemId = "'.$aaData[$row_key]['POH_SYS_ID'].'" onclick=javascript:purchas_order_status('.$aaData[$row_key]['POH_SYS_ID'].')>Incomplete</button>';
		//}
	    
	    }else if($aaData[$row_key]['POH_STATUS']=="Send for approval"){
		
		if ($ApproveButton == 'N') {
		    $aaData[$row_key]['POH_STATUS']='<button data-target="#large_view" id="approve" class="btn-new btn-warning statusWidth" data-toggle="modal" disabled>Approve ?</button>';
		}else{
		    $aaData[$row_key]['POH_STATUS']='<button data-target="#large_view" id="approve" class="btn-new btn-warning statusWidth" data-toggle="modal" systemId = "'.$aaData[$row_key]['POH_SYS_ID'].'" onclick=javascript:purchas_order_status('.$aaData[$row_key]['POH_SYS_ID'].')>Approve ?</button>';
		}
		
	    }else if($aaData[$row_key]['POH_STATUS']=="Approved"){
		
		if ($ApproveButton == 'N') {
		    $aaData[$row_key]['POH_STATUS']='<button data-target="#large_view" id="approved" class="btn-new btn-success statusWidth" data-toggle="modal" disabled>Approved</button>';
		}else{
		    $aaData[$row_key]['POH_STATUS']='<button data-target="#large_view" id="approved" class="btn-new btn-success statusWidth" data-toggle="modal" systemId = "'.$aaData[$row_key]['POH_SYS_ID'].'" onclick=javascript:purchas_order_status('.$aaData[$row_key]['POH_SYS_ID'].')>Approved</button>';
		}
		
	    }else if($aaData[$row_key]['POH_STATUS']=="Amended"){
		
		if (($ApproveButton == 'N') || ($AmendedButton == 'N')) {
		    $aaData[$row_key]['POH_STATUS']='<button data-target="#large_view" id="amend" data-toggle="modal" class="btn-new btn-new-inverse statusWidth" disabled>Amended</button>';
		}else{
		    $aaData[$row_key]['POH_STATUS']='<button data-target="#large_view" id="amend" data-toggle="modal" class="btn-new btn-new-inverse statusWidth" systemId = "'.$aaData[$row_key]['POH_SYS_ID'].'" onclick=javascript:purchas_order_status('.$aaData[$row_key]['POH_SYS_ID'].')>Amended</button>';
		}
	    
	    }if($aaData[$row_key]['POH_STATUS']=="Rejected"){
		
		if ($ApproveButton == 'N') {
		    $aaData[$row_key]['POH_STATUS']='<button  data-target="#large_view" id="rejected" data-toggle="modal" class="btn-new btn-danger statusWidth" disabled>Rejected</button>';
		}else{
		    $aaData[$row_key]['POH_STATUS']='<button  data-target="#large_view" id="rejected" data-toggle="modal" class="btn-new btn-danger statusWidth" onclick=javascript:purchas_order_status('.$aaData[$row_key]['POH_SYS_ID'].')>Rejected</button>';
		}
	    
	    }
	}
	//Purchase Order Transaction End
	
	//Shipment Advice Transaction Start
	if($tableName=="PROC_T_SA_HEAD_VIEW"){
	    $ApproveButton = $_POST['ApproveButton'];	 
	
	    if($aaData[$row_key]['SAH_STATUS']=="Incomplete"){
		
		//if ($ApproveButton == 'N') {
		  //  $aaData[$row_key]['SAH_STATUS']='<button data-target="#large_view" id="created" class="btn-new btn-primary statusWidth" data-toggle="modal" disabled>Incomplete</button>';
		//}else{
		    $aaData[$row_key]['SAH_STATUS']='<button data-target="#large_view" id="created" class="btn-new btn-primary statusWidth" data-toggle="modal" systemId = "'.$aaData[$row_key]['SAH_SYS_ID'].'" onclick=javascript:shipment_advice_status('.$aaData[$row_key]['SAH_SYS_ID'].')>Incomplete</button>';
		//}
	    
	    }else if($aaData[$row_key]['SAH_STATUS']=="Send for approval"){
		
		if ($ApproveButton == 'N') {
		    $aaData[$row_key]['SAH_STATUS']='<button data-target="#large_view" id="approve" class="btn-new btn-warning statusWidth" data-toggle="modal" disabled>Approve ?</button>';
		}else{
		    $aaData[$row_key]['SAH_STATUS']='<button data-target="#large_view" id="approve" class="btn-new btn-warning statusWidth" data-toggle="modal" systemId = "'.$aaData[$row_key]['SAH_SYS_ID'].'" onclick=javascript:shipment_advice_status('.$aaData[$row_key]['SAH_SYS_ID'].')>Approve ?</button>';
		}
		
	    }else if($aaData[$row_key]['SAH_STATUS']=="Approved"){
		
		if (($ApproveButton == 'N') || ($AmendedButton == 'N')) {
		    $aaData[$row_key]['SAH_STATUS']='<button data-target="#large_view" id="approved" class="btn-new btn-success statusWidth" data-toggle="modal" disabled>Approved</button>';
		}else{
		    $aaData[$row_key]['SAH_STATUS']='<button data-target="#large_view" id="approved" class="btn-new btn-success statusWidth" data-toggle="modal" systemId = "'.$aaData[$row_key]['SAH_SYS_ID'].'" onclick=javascript:shipment_advice_status('.$aaData[$row_key]['SAH_SYS_ID'].')>Approved</button>';
		}
		
	    }else if($aaData[$row_key]['SAH_STATUS']=="Amended"){
		
		if ($ApproveButton == 'N')  {		    
		    $aaData[$row_key]['SAH_STATUS']='<button data-target="#large_view" id="amend" data-toggle="modal" class="btn-new btn-new-inverse statusWidth" disabled>Amended</button>';
		}else{
		    $aaData[$row_key]['SAH_STATUS']='<button data-target="#large_view" id="amend" data-toggle="modal" class="btn-new btn-new-inverse statusWidth" systemId = "'.$aaData[$row_key]['SAH_SYS_ID'].'" onclick=javascript:shipment_advice_status('.$aaData[$row_key]['SAH_SYS_ID'].')>Amended</button>';
		}
	    
	    }if($aaData[$row_key]['SAH_STATUS']=="Rejected"){
		
		if ($ApproveButton == 'N') {
		    $aaData[$row_key]['SAH_STATUS']='<button  data-target="#large_view" id="rejected" data-toggle="modal" class="btn-new btn-danger statusWidth" disabled>Rejected</button>';
		}else{
		    $aaData[$row_key]['SAH_STATUS']='<button  data-target="#large_view" id="rejected" data-toggle="modal" class="btn-new btn-danger statusWidth" onclick=javascript:shipment_advice_status('.$aaData[$row_key]['SAH_SYS_ID'].')>Rejected</button>';
		}
	    
	    }
	}
	//Shipment Advice Transaction End

//Procurement End
//Finance Start
//Purchase Request Transaction Start
//print_r($tableName);
//	    exit;
	if($tableName=="FINC_T_BUDGET_HEAD_VIEW"){
	  
	    $ApproveButton = $_POST['ApproveButton'];	 
	 
	    if($aaData[$row_key]['BH_STATUS']=="Incomplete"){
		
		//if ($ApproveButton == 'N') {
		   // $aaData[$row_key]['PRQH_STATUS']='<button data-target="#large_view" id="created" class="btn-new btn-primary statusWidth" data-toggle="modal" disabled>Incomplete</button>';
		//}else{
		    $aaData[$row_key]['BH_STATUS']='<button data-target="#large_view" id="created" class="btn-new btn-primary statusWidth" data-toggle="modal" systemId = "'.$aaData[$row_key]['BH_SYS_ID'].'" onclick=javascript:budget_status('.$aaData[$row_key]['BH_SYS_ID'].')>Incomplete</button>';
		//}
	    
	    //print_r($aaData[$row_key]['BH_STATUS']);
	    //exit;
	    
	    }else if($aaData[$row_key]['BH_STATUS']=="Send for approval"){
		
		if ($ApproveButton == 'N') {
		    $aaData[$row_key]['BH_STATUS']='<button data-target="#large_view" id="approve" class="btn-new btn-warning statusWidth" data-toggle="modal" disabled>Approve ?</button>';
		}else{
		    $aaData[$row_key]['BH_STATUS']='<button data-target="#large_view" id="approve" class="btn-new btn-warning statusWidth" data-toggle="modal" systemId = "'.$aaData[$row_key]['BH_SYS_ID'].'" onclick=javascript:budget_status('.$aaData[$row_key]['BH_SYS_ID'].')>Approve ?</button>';
		}
		
	    }else if($aaData[$row_key]['BH_STATUS']=="Approved"){
		
		if ($ApproveButton == 'N') {
		    $aaData[$row_key]['BH_STATUS']='<button data-target="#large_view" id="approved" class="btn-new btn-success statusWidth" data-toggle="modal" disabled>Approved</button>';
		}else{
		    $aaData[$row_key]['BH_STATUS']='<button data-target="#large_view" id="approved" class="btn-new btn-success statusWidth" data-toggle="modal" systemId = "'.$aaData[$row_key]['BH_SYS_ID'].'" onclick=javascript:budget_status('.$aaData[$row_key]['BH_SYS_ID'].')>Approved</button>';
		}
		
	    }else if($aaData[$row_key]['BH_STATUS']=="Amended"){
		
		if (($ApproveButton == 'N') || ($AmendedButton == 'N')) {
		    $aaData[$row_key]['BH_STATUS']='<button data-target="#large_view" id="amend" data-toggle="modal" class="btn-new btn-new-inverse statusWidth" disabled>Amended</button>';
		}else{
		    $aaData[$row_key]['BH_STATUS']='<button data-target="#large_view" id="amend" data-toggle="modal" class="btn-new btn-new-inverse statusWidth" systemId = "'.$aaData[$row_key]['BH_SYS_ID'].'" onclick=javascript:budget_status('.$aaData[$row_key]['BH_SYS_ID'].')>Amended</button>';
		}
	    
	    }if($aaData[$row_key]['BH_STATUS']=="Rejected"){
		
		if ($ApproveButton == 'N') {
		    $aaData[$row_key]['BH_STATUS']='<button  data-target="#large_view" id="rejected" data-toggle="modal" class="btn-new btn-danger statusWidth" disabled>Rejected</button>';
		}else{
		    $aaData[$row_key]['BH_STATUS']='<button  data-target="#large_view" id="rejected" data-toggle="modal" class="btn-new btn-danger statusWidth" onclick=javascript:budget_status('.$aaData[$row_key]['BH_SYS_ID'].')>Rejected</button>';
		}
	    
	    }
	}

//Finance End
}

      $sColumns = array_diff($this->columns, $this->unset_columns);
      $sColumns = array_merge_recursive($sColumns, array_keys($this->add_columns));

      $sOutput = array
      (
        'sEcho'                => intval($this->ci->input->post('sEcho')),
        'iTotalRecords'        => $iTotal,
        'iTotalDisplayRecords' => $iFilteredTotal,
        'aaData'               => $aaData,
        'sColumns'             => implode(',', $sColumns)
      );
   
     
      if(strtolower($charset) == 'utf-8'){
	
        return json_encode($sOutput);
       
      }
      else
        return $this->jsonify($sOutput);
    }
	  
    /**
    * Get result count
    *
    * @return integer
    */
    protected function get_total_results($filtering = FALSE)
    {
      if($filtering)
        $this->get_filtering();

      foreach($this->joins as $val)
        $this->ci->db->join($val[0], $val[1], $val[2]);

      foreach($this->where as $val)
        $this->ci->db->where($val[0], $val[1], $val[2]);

      return $this->ci->db->count_all_results($this->table);
    }

    /**
    * Runs callback functions and makes replacements
    *
    * @param mixed $custom_val
    * @param mixed $row_data
    * @return string $custom_val['content']
    */
    protected function exec_replace($custom_val, $row_data)
    {
      $replace_string = '';

      if(isset($custom_val['replacement']) && is_array($custom_val['replacement']))
      {
        foreach($custom_val['replacement'] as $key => $val)
        {
          $sval = preg_replace("/(?<!\w)([\'\"])(.*)\\1(?!\w)/i", '$2', trim($val));

          if(preg_match('/(\w+)\((.*)\)/i', $val, $matches) && function_exists($matches[1]))
          {
            $func = $matches[1];
            $args = preg_split("/[\s,]*\\\"([^\\\"]+)\\\"[\s,]*|" . "[\s,]*'([^']+)'[\s,]*|" . "[,]+/", $matches[2], 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

            foreach($args as $args_key => $args_val)
            {
              $args_val = preg_replace("/(?<!\w)([\'\"])(.*)\\1(?!\w)/i", '$2', trim($args_val));
              $args[$args_key] = (in_array($args_val, $this->columns))? ($row_data[($this->check_mDataprop())? $args_val : array_search($args_val, $this->columns)]) : $args_val;
            }

            $replace_string = call_user_func_array($func, $args);
          }
          elseif(in_array($sval, $this->columns))
            $replace_string = $row_data[($this->check_mDataprop())? $sval : array_search($sval, $this->columns)];
          else
            $replace_string = $sval;

          $custom_val['content'] = str_ireplace('$' . ($key + 1), $replace_string, $custom_val['content']);
        }
      }

      return $custom_val['content'];
    }

    /**
    * Check mDataprop
    *
    * @return bool
    */
    protected function check_mDataprop()
    {
      if(!$this->ci->input->post('mDataProp_0'))
        return FALSE;

      for($i = 0; $i < intval($this->ci->input->post('iColumns')); $i++)
        if(!is_numeric($this->ci->input->post('mDataProp_' . $i)))
          return TRUE;

      return FALSE;
    }

    /**
    * Get mDataprop order
    *
    * @return mixed
    */
    protected function get_mDataprop()
    {
      $mDataProp = array();

      for($i = 0; $i < intval($this->ci->input->post('iColumns')); $i++)
        $mDataProp[] = $this->ci->input->post('mDataProp_' . $i);

      return $mDataProp;
    }

    /**
    * Return the difference of open and close characters
    *
    * @param string $str
    * @param string $open
    * @param string $close
    * @return string $retval
    */
    protected function balanceChars($str, $open, $close)
    {
      $openCount = substr_count($str, $open);
      $closeCount = substr_count($str, $close);
      $retval = $openCount - $closeCount;
      return $retval;
    }

    /**
    * Explode, but ignore delimiter until closing characters are found
    *
    * @param string $delimiter
    * @param string $str
    * @param string $open
    * @param string $close
    * @return mixed $retval
    */
    protected function explode($delimiter, $str, $open = '(', $close=')')
    {
      $retval = array();
      $hold = array();
      $balance = 0;
      $parts = explode($delimiter, $str);

      foreach($parts as $part)
      {
        $hold[] = $part;
        $balance += $this->balanceChars($part, $open, $close);

        if($balance < 1)
        {
          $retval[] = implode($delimiter, $hold);
          $hold = array();
          $balance = 0;
        }
      }

      if(count($hold) > 0)
        $retval[] = implode($delimiter, $hold);

      return $retval;
    }

    /**
    * Workaround for json_encode's UTF-8 encoding if a different charset needs to be used
    *
    * @param mixed result
    * @return string
    */
    protected function jsonify($result = FALSE)
    {
      if(is_null($result))
        return 'null';

      if($result === FALSE)
        return 'false';

      if($result === TRUE)
        return 'true';

      if(is_scalar($result))
      {
        if(is_float($result))
          return floatval(str_replace(',', '.', strval($result)));

        if(is_string($result))
        {
          static $jsonReplaces = array(array('\\', '/', '\n', '\t', '\r', '\b', '\f', '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
          return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $result) . '"';
        }
        else
          return $result;
      }

      $isList = TRUE;

      for($i = 0, reset($result); $i < count($result); $i++, next($result))
      {
        if(key($result) !== $i)
        {
          $isList = FALSE;
          break;
        }
      }

      $json = array();

      if($isList)
      {
        foreach($result as $value)
          $json[] = $this->jsonify($value);

        return '[' . join(',', $json) . ']';
      }
      else
      {
        foreach($result as $key => $value)
          $json[] = $this->jsonify($key) . ':' . $this->jsonify($value);

        return '{' . join(',', $json) . '}';
      }
    }
  }
/* End of file Datatables.php */
/* Location: ./application/libraries/Datatables.php */