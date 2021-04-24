<?php

//katha.php

class pms extends file
{
	public $base_url = 'http://localhost/pms/';	
	public $connect;
	public $query;
	public $statement;

	function __construct()
	{
		try{
				$this->connect = new PDO("mysql:host=localhost;dbname=patient_management_system", "root", "root");
				$this->connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$this->query = " SET NAMES utf8 ";				
				if (session_status() === PHP_SESSION_NONE){session_start();}
		}
		catch(PDOException $e){
			$msg = date("Y-m-d h:i:s A")." Connection, PDO: ".$e->getMessage()."\r\n";
			error_log($msg, 3, ERROR_LOG);
		} catch(Exception $e){
			$msg = date("Y-m-d h:i:s A")." Connection, General: ".$e->getMessage()."\r\n";
			error_log($msg, 3, ERROR_LOG);
		}
	}
	function execute($data = null){
		$this->statement = $this->connect->prepare($this->query);
		$data=$this->check_array($data);
		return $this->statement->execute($data);		
	}

	function row_count(){
		return $this->statement->fetchColumn();
	}

	function statement_result(){
		return $this->statement->fetchAll();
	}
	function get_array(){		
		return $this->statement->fetch();
    }
	function get_result(){
		return $this->connect->query($this->query, PDO::FETCH_ASSOC);
	}

	function close(){
        $this->statement=NULL;
    }
	function id(){
		return $this->connect->lastInsertId();
	}
	function row(){
		return $this->statement->rowCount();
    }

	function is_login()
	{
		if(isset($_SESSION['user_id']))		
			return true;		
		else	
		return false;
	}

	function is_master_user()
	{
		return $this->is_admin();
	}
	function is_admin()
	{
		if(isset($_SESSION['user_type']))
		{
			if($_SESSION["user_type"] == 'Master'|| $_SESSION["user_type"] == 'admin')
			{
				return true;
			}
			return false;
		}
		return false;
	}

	function clean_input($string)
	{
	  	$string = trim($string);
	  	$string = stripslashes($string);
	  	$string = htmlspecialchars($string);
	  	return $string;
	}

	function get_datetime()
	{
		return date("Y-m-d H:i:s",  STRTOTIME(date('h:i:sa')));
	}
	function check_array($value)	{
		if (is_array($value))  
			return $value;
		else 
			return array($value);		
	}
	function create_placeholder($value)	{
		if (is_array($value))
		{	$marker=array();
			foreach($value as $values)
			{	$values='?'; 
			array_push($marker,$values);      
			}				
		  return $marker;
		}
		else	
			return array('?');	
	}
	
	function Delete($table,$placeholder=NULL,$conditions=NULL,$combine='AND'){
		$row_condition=$this->implode_array($placeholder,'?',$combine); 
		$this->query ="DELETE FROM $table WHERE ". $row_condition;
		return $this->execute($conditions);	
	}

	function total($table=null,$column=null,$placeholder=null,$value=null,$combine='AND',$join=array(),$attr=array())
	{
		$implode=isset($attr['implode'])?$attr['implode']:',';
		$firstclose=isset($attr['firstclose'])?$attr['firstclose']:' ) AS ';
		$start=	isset($attr['start'])?$attr['start']:'IFNULL (SUM(';	
		$value=$this->check_array($value);
		$finalclose=isset($attr['finalclose'])?$attr['finalclose']:'), 0) AS total';
		if(isset($attr['implodehere']))
			$columnvalue=$start.$this->implode_array($column,'',') + SUM(').$finalclose;
		else
			$columnvalue=$this->implode_join($column,$firstclose,$start,$implode,$finalclose);
		$row_condition=$this->implode_array($placeholder,'?',$combine);						
		$this->query = "SELECT ".$columnvalue." FROM $table ";
		if($join){
			$joincondition=$this->implode_join($join);
			$this->query .=$joincondition;
		}	
		if(!empty($row_condition)) 
			$this->query .= " WHERE ".$row_condition;
		if(isset($attr['groupby']))
			$this->query .= " GROUP BY ".$attr['groupby'];
		$this->execute($value);
		if(isset($attr['groupby']))
			return $this->statement_result();
		$row=$this->get_array();			
		if ($row)
				return $row['total'];				
		return 0;
	}
	function website_name(){
		return $this->get_data('facility_name','facility_table');	
	}
	function getArray($table,$placeholder=null,$conditions=null,$combine='AND',$limit=null){//gives one array
		return $this->get_data('',$table,$placeholder,$conditions,$combine,$limit);	
	}
	
	function fill_list($category,$column=null,$category_id=null,$value=null)
	{	
		$result = $this->getAllArray($category,$column,$category_id,'AND','',$category."_name",'ASC');
		$output = '';		
		$selected_value = $value;// value from database	
		$output .= '<option value="" selected hidden disabled> Select '.ucwords($category).'</option>';
		foreach($result as $row)
		{			
			$selected = ($selected_value == $row[$category."_id"]) ? "selected" : "";
			$output .= '<option value="'.$row[$category."_id"].'"'. $selected. '>'.$row[$category."_name"].'</option>';
		}
		return $output;
	}
	function load_department(){	
		return $this->fill_list('department');
	}
	function load_specialization(){
		return $this->fill_list('specialization');
	}
	function load_doctor($d_id=null)
	{					
		$placeholder=$condition=array();	
		if ($d_id)	{	$placeholder[]=" department_id";$condition[]=$d_id;	}
		return $this->fill_list('doctor',$placeholder,$condition);
	}
	function load_email()
	{
		$this->query = "SELECT user_email FROM users WHERE user_email IS NOT NULL AND user_email!='' ORDER BY user_email ASC";
		$result = $this->get_result();
		$output = '';
		foreach($result as $row){
			$output .= '<option value="'.$row["user_email"].'" >'.$row["user_email"].'</option>';
		}
		return $output;
	}

	
	function Get_profile_image($user_id=null)
	{
		if 	((isset($_SESSION['user_id']) &&!empty($_SESSION['user_id'])) || $user_id!=null)
		{   
			$id=(($user_id)?$user_id:$_SESSION['user_id']);							
					$row = $this->get_data(array('user_profile','user_name'),'users','user_id',$id,'',1);					
					if(!empty($row['user_profile']))
					{
						 return $this->image_check($row['user_profile']);
					} 		
					else { 
								$image=$this-> make_avatar(strtoupper($row['user_name'][0]));						
								$saved=$this->UpdateDataColumn("users","user_profile",$image,"user_id",$id);
								if($saved){
									return $image;
								}
									return null;
						}
		}
		  return 'user_profile.png';
	}
	
	function Get_count($table,$where,$condition)
	{		
		return $this->CountTable($table,$where,$condition);
	}

	function Set_timezone()
	{    $row=$this->get_data('facility_timezone','facility_table');		
			return $row;		
	}

	function facilityArray(){				
			return $this->getArray('facility_table');
	}
	function insert($table,$column,$value,$attr=array()){			
		$column=$this->check_array($column);
		$column_condition=$this->implode_array($column,'',',');
		$value=$this->check_array($value);   		
		$marker= implode(', ', array_fill(0, sizeof($column), '?'));
		$this->query = " INSERT " ;
		if(isset($attr['ignore']))
			$this->query .= " IGNORE " ;
		$this->query .=" INTO $table ($column_condition) VALUES($marker) ";
		if(isset($attr['duplicate']))
			$this->query .=" ON DUPLICATE KEY UPDATE ". $attr['duplicate'];
		if(isset($attr['debug'])){
			return $this->query;	
			}
		return $this->execute($value);
	}

	function UpdateDataColumn($table,$column,$value=null,$placeholder=null,$condition=null,$combine='AND'){	
		if($value===null)	$column_condition=$this->implode_array($column);
		else				$column_condition=$this->implode_array($column,'?',',');
		$value=$this->check_array($value);
		$condition=$this->check_array($condition);
		$placeholder_condition=$this->implode_array($placeholder,'?',$combine);
		$finalvalue=array_merge($value,$condition);
		$this->query = "UPDATE $table SET $column_condition  ";
		if($placeholder_condition)
			$this->query .= " WHERE $placeholder_condition ";			
		return $this->execute($finalvalue);
	}
	
	function CountTable($table,$condition=null,$value=null,$combine='AND',$compare='='){   
		if ($value!=null){		  
			$marker=$this->create_placeholder($condition);	  
			if(!is_array($condition)&&is_array($value))			
			  	$marker=$this->create_placeholder($value);
		   $row_condition=$this->implode_array($condition,$marker,$combine,$compare);
		}	 
	  	else	  
			$row_condition=$this->implode_array($condition);	  			  
	 	$this->query = "SELECT Count(*) AS total FROM $table ";	
		if($row_condition)	$this->query .= " WHERE ".$row_condition;
		if(isset($attr['debug'])){
			return $this->query;	
		  }
		$this->execute($value);
		$row= $this->get_array();	
		return $row['total'];
	}
	function getAllArray($table,$column=null,$value=null,$combine='AND',$limit=null,$orderby=null,$order='DESC',$join=array(),$attr=array()){	//gives all array
		if ($value!=null)
			$row_condition=$this->implode_array($column,'?',$combine);
		else
			$row_condition=$this->implode_array($column);
		  if(isset($attr['fields']))
		  $requireddate=$attr['fields'];
		  else
		  $requireddate='*';
		$this->query =" SELECT " ;
		$this->query .= $requireddate  ;	  
		$this->query .= " FROM $table "  ;		 
		if($join){
		  $joincondition=$this->implode_join($join);
		  $this->query .=$joincondition;
		  }	
		if(!empty($row_condition)) $this->query .= " WHERE ".$row_condition;
		if(isset($attr['groupby'])){
		  $this->query .= " GROUP BY ". $attr['groupby'];	
		}
		if(!empty($orderby))  	 $this->query .= " ORDER BY ". $orderby ." ".$order;
		if(!empty($limit))	 	 $this->query .= " LIMIT ".$limit;
		if(isset($attr['debug'])){
		  return $this->query;	
		}
	   // return $this->query;			  
		$this->execute($value);
		return $this->statement_result();
	}

	function get_data($data=null,$table=null,$placeholder=null,$conditions=null,$combine='AND',$limit=null,$join=array(),$attr=array()){    
		if (empty($table)) 
		return null;
		if(!$data)		$requireddata="*";
		else			$requireddata=$this->implode_array($data,'',',');
		if ($conditions!=null)	$row_condition=$this->implode_array($placeholder,'?',$combine);
		else					$row_condition=$this->implode_array($placeholder);
		$this->query ="SELECT $requireddata FROM $table ";
		if($join){
			$joincondition=$this->implode_join($join);
			$this->query .=$joincondition;
		}	
		if($row_condition)	$this->query .= " WHERE ".$row_condition;			
		if($limit)			$this->query .= " LIMIT ".$limit;
		if(isset($attr['debug'])){
			return $this->query;	
		  }
		//return $this->query;
		$this->execute($conditions);
		$row= $this->get_array();	
		if ($row)
		{	if(!$data || is_array($data))			
				return $row;	
			else
				return $row[$data];	
		}						
		return null;
	}


	function get_facility_logo()
	{	$row = $this->get_data('facility_logo','facility_table');
			return $row;		
	}

	function Get_currency_symbol()
	{
		$row = $this->getarray('facility_table');
		$currency = $row['facility_currency'];
		$currency_data = $this->currency_array();
			foreach($currency_data as $row)
			{
				if($row['code'] == $currency)				
					return $row["symbol"];				
			}		
	}

	function PatientsArray(){
		$results=$this-> getAllArray(" patient_history") ;;
		foreach($results as $row){ 
				return $row;
		}		
	}
	function implode_join($join,$how=' ON  ',$default='SUM(',$implode=" ",$close=')'){		
		if(is_string($join))
			return $default.$join.$close;
		$temp=array();			
		foreach($join as $column_name => $firstvalue)								
			if (is_array($firstvalue)){			
				foreach($firstvalue as $extracolumn_name => $extravalue)					
					if(is_array($extravalue))
						$temp[]=$this->implode_array($column_name,$how,""," ",$extracolumn_name,implode(' AND ',$extravalue));
					else
						$temp[] =$this->implode_array($column_name,$how,""," ",$extracolumn_name,$extravalue);
			}			
			else
				$temp[]=$column_name.' '.$firstvalue;	
		return  implode($implode,$temp);		
	}
	function implode_array($placeholder=null,$conditions=null,$combine="AND",$compare="=",$frontquotes="'",$backquotes="'"){
		$finalarray=array();
		if ($placeholder==null)
				return null;
		elseif (!is_array($placeholder)&&!empty($placeholder) && empty($conditions))						
				return " ".$placeholder." "; 
		elseif (!is_array($placeholder) && !empty($conditions) && !is_array($conditions))		
				if	($conditions=='?')
					return " ".$placeholder.$compare."?"." ";
				else
					return " ".$placeholder.$compare.$frontquotes.$conditions.$backquotes." "; 		
		elseif (is_array($placeholder)&& empty($conditions))				
				$finalarray=$placeholder;
		elseif (is_array($placeholder)&& is_array($conditions) && !empty($conditions))							
				if ( in_array('?',$conditions))
					foreach($conditions as $key=> $condition)
						if(is_array($compare))
							array_push($finalarray," ".$placeholder[$key].$compare[$key].$condition);
						else						 
							array_push($finalarray," ".$placeholder[$key].$compare.$condition);
				else
					foreach($conditions as $key=> $condition)
						if(is_array($compare))							
						array_push($finalarray," ".$placeholder[$key].$compare[$key].$frontquotes.$condition.$backquotes);
						else
						array_push($finalarray," ".$placeholder[$key].$compare.$frontquotes.$condition.$backquotes);	
		elseif (is_array($placeholder)&& !is_array($conditions) && !empty($conditions))					
				if ($conditions=='?')
					foreach($placeholder as $key=> $place)
						array_push($finalarray," ".$place.$compare.$conditions);
				else
					foreach($placeholder as $key=> $place)
						array_push($finalarray," ".$place.$compare.$frontquotes.$conditions.$backquotes);		
		else					
				if ( in_array('?',$conditions))						
					foreach($conditions as $key=> $condition)							
						array_push($finalarray," ".$placeholder.$compare.$condition); 
				else						
					foreach($conditions as $key=> $condition)
						array_push($finalarray," ".$placeholder.$compare.$frontquotes.$condition.$backquotes); 
		if ($combine)
			if(is_array($combine)){
				$temp=array();
				$count=0;
				foreach($finalarray as $key=>$returnarray){
					$temp[]=$returnarray.$combine[$count];
					$count++;
				}
				return implode('  ',$temp);
			}
			else
				return implode(' '.$combine.' ',$finalarray); 
		return  $finalarray;    
	}

	



	







}


?>