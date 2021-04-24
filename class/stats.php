<?php

class stats extends pms
{	
	function today_patient(){
		return $this->Get_patient_value('patient_enter_time');
	}

	function yesterday_patient(){
		return $this->Get_patient_value('patient_enter_time',1);
	}

	function last_seven_day_patient(){
		return $this->Get_patient_value('patient_enter_time',7,'>');
	}

	function total_patient(){
		return $this->Get_patient_value();	
    }
	function Get_patient_value($date=null,$interval=null,$sign=null)
	{
		$condition=$value=$combine=$compare=array();	
		if ($date){		
			$condition[]= 'DATE('.$date.')';$value[]=DATE($this->get_datetime());
			$combine[]=$interval?' - ':'';$compare[]=$sign?$sign.'=':'=';
		}
		if($interval){	
			$condition[]=' INTERVAL ';$value[]=$interval ;
			$combine[]=!$this->is_admin()?' DAY  AND ':' DAY ';	$compare[]=' ';
		}				
		return $this->CountTable('patient_history',$condition,$value,$combine,$compare);	
	}

	function patient_limit()
	{   $patient=$this->total_patient();
		$target=$this->get_data('facility_target','facility_table');		
		$limit=$patient/$target*100;	
		return $limit;
	}
	
    function getfullmonthhtml(){
		return $this->loopmonth("","-9");
	}		
	function getfullmonth(){
		return $this->loopmonth("&quot;","-9");
	}
	function getmonthhtml()	{		
		return $this->loopmonth();
	}	
	function getmonth()	{	
		return $this->loopmonth('&quot;');			
	}
	function loopmonth($quote=null,$start=1){
		$months=array();
		for($i=$start;$i<date('n');$i++)
			array_push($months,$quote.substr(date('F', mktime(0, 0, 0, ($i), 2, date('Y'))),0,3).$quote);
		if ($quote)
			return  implode(',',$months);
		return  $months;
	}
	function getmonthvalue($table='patient',$type='number'){	
		return  $this->loopmonthvalue($table,$type,'&quot;');
	}	
	function getmonthvaluehtml($table='patient',$type='number'){
		return $this-> loopmonthvalue($table,$type);
	}
	function loopmonthvalue($table='patient',$type='number',$quote=null)
	{	$value=array();
		for($i=1;$i<=date('n');$i++)
			array_push($value,$quote .$this->getValuePerMonth($i,$table,$type).$quote);
		if ($quote)
			return  implode(',',$value);
		return  $value;
	}
	function getfullmonthvalue($table='patient',$type='number'){
		return $this->loopfullmonthvalue($table,$type,"&quot;");		
	}
	function getfullmonthvaluehtml($table='patient',$type='number'){		
		return $this->loopfullmonthvalue($table,$type);
	}
	function loopfullmonthvalue($table='patient',$type='number',$quote=null)
	{	$value=array();
		$startpos = date('n');
		for($i=1;$i<=12;$i++)
			array_push($value,$quote .$this->getValuePerMonth($i,$table,$type).$quote); 
		$output = array_merge(array_slice($value,$startpos), array_slice($value, 0, $startpos)); 		
		if ($quote)
			return implode(',',$output);
		return $output;
	}

	function getValuePerMonth($value,$table='patient',$type='number')	{		
		if($type=='number')
			return $this->CountTable($table."_history",'MONTH('.$table.'_enter_time)',$value);
		else
			return $this->total($table."_history",$table,'MONTH($table."_enter_time")',$value);
	}
function get_patient_sources($quote="&quot;"){
	$sources=array('regular','outside','referral');
	$value=array();	
	foreach ($sources as $source)
		array_push($value,$quote .$this->CountTable("patient_history",'patient_source',$source).$quote);
	return  implode(',',$value);
	
}

function avg_month_patient()
{	
	$sum=0;
	for($i=1;$i<=12;$i++)
		$sum+=$this->getValuePerMonth($i);		
	 $average=$sum/12;
		return  number_format($average);
}

function avg_yearly_patient()
{	$sum=0;
	for($i=1;$i<=12;$i++)
		$sum+=$this->getValuePerMonth($i);		
	 $average=$sum;
		return  number_format($average);
}

function total_apppointments()
{	
	return $this->CountTable("appointments",'appointment_status','waiting');
}

}
?>