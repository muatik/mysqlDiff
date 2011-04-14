<?php
class arrays{
	public static function makeCloud($arr,$field,$separator=', '){
		if(count($arr)<1) return null;
		
		$cloud='';
		$first=each($arr);
		if(is_object($first['value'])){
			foreach($arr as $i)
				$cloud.=$i->$field.$separator;
		}
		elseif(is_array($first['value'])){
			foreach($arr as $i)
				$cloud.=$i[$field].$separator;
		}
		$cloud=mb_substr($cloud,0,mb_strlen($separator)*-1);
		return $cloud;
	}
	
	/* @brief	nesneleri diziye çevirir.
	 * @params	$field	dizi değeri olaca nesne özelliği
	 * @params	$fieldKey	dizi anahtarı olacak nesne özelliği
	 * @example	
	 * $x->a=elma; $x->b=5;
	 * print_r(arrays::convertToArray($x,'a','b'));
	 * çıktı: Array([5] => elma) 
	 * */
	public static function convertToArray($arr,$field,$keyField=null){
		$newArr='';
		if(count($arr)<1) return null;
		
		if($keyField==null)
			foreach($arr as $i)
				$newArr[]=$i->$field;
		else
			foreach($arr as $i)
				$newArr[$i->$keyField]=$i->$field;
		
		return $newArr;
	}
	
	public static function makeUnique($arr,$field=null){
		if(count($arr)<1) return null;
		$narr=array();
		$vals=array();
		
		if(is_object($arr[0])){
			foreach($arr as $k=>$i)
			if(!in_array($i,$vals)){
				$narr[$k]=$i;
				$vals[]=$i[$field];
			}
		}
		elseif(is_array($arr[0])){
			foreach($arr as $k=>$i) 
				if(!in_array($i[$field],$vals)){
					$narr[$k]=$i;
					$vals[]=$i[$field];
				}
		}
		else{
			foreach($arr as $k=>$i)
			if(!in_array($i,$vals)){
				$narr[$k]=$i;
				$vals[]=$i;
			}
		}
		
		return $narr;
	}
	
	public static function removeEmpties($arr){
		foreach($arr as $k=>$v){
			$v=trim($v);
			if($v=='') unset($arr[$k]);
			else $arr[$k]=$v;
		}
		return $arr;
	}
	
	
	public static function _compare($o1,$o2,$field,$action='diff'){
		
		if(!is_array($o1)){
			$o1=array($o1);
			$o2=array($o2);
		}
		
		$o1=arrays::makeCloud($o1,$field,',');
		$o2=arrays::makeCloud($o2,$field,',');
		
		$o1=explode(',',$o1);
		$o2=explode(',',$o2);
		
		if($action=='intersect') 
			return array_intersect($o1,$o2);
		else
			return array_diff($o1,$o2);
	}
	
	public static function diff($o1,$o2,$field,$type='all'){
		$i1=array();
		$i2=array();
		
		switch($type){
			case 'to':
				$i1=arrays::_compare($o1,$o2,$field,'diff');
				break;
			case 'from':
				$i1=arrays::_compare($o2,$o1,$field,'diff');
				break;
			default:
				$i1=arrays::_compare($o1,$o2,$field,'diff');
				$i2=arrays::_compare($o2,$o1,$field,'diff');
		}
		
		return array_merge($i1,$i2);
	}
	
	public static function intersect($o1,$o2,$field){
		return arrays::_compare($o2,$o1,$field,'intersect');
	}
	
}
?>
