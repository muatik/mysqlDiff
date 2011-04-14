<?php
/*	
	PHP 4.4.2  sürümünde yazýldý ve denendi. Bu sýnýf mysql veri tabaný sunucusu üzerinde iþlem yapmayý kolaþtýrmak amacýyla yazýldý.
	Gerekli veri tabaný hata ve kontrol kodlarýný her uygulamada tekrardan yazmaktansa bu sýnýfý kullanarak zaman kazanýlabilir.
	
	NOT:PHP5 ve daha üst bir sürüm kullanýyorsanýz bu sýnýf çalýþmayabilir.  Çünki php5 sürümünde sýnýflar konusunda köklü deðiþiklikler yapýldý.
	
	mustafa
*/

class db{
	
	var $host='localhost';
	var $username='root';
	var $password='root';
	var $database='test';
	
	var $connection;
	var $reader;
	var $affectedRows;
	var $numRows;
	var $error;
	var $charSet='utf8';
	var $collate='utf8_turkish_ci';
	
	public function __construct(){
		/*
		 * eğer veritabanı bağlantı sabitleri tanımlandıysa
		 * */
		if(defined('_dbHost')!=null){
			$this->host=constant('_dbHost');
			$this->username=constant('_dbUser');
			$this->password=constant('_dbPassword');
			if(constant('_dbDatabase')!=null)
				$this->database=constant('_dbDatabase');
		}
		
	}
	
	function connect(){
		if($this->connection=new mysqli($this->host,$this->username,$this->password)){
			$this->query('set names "'.$this->charSet.'" collate "'.$this->collate.'"');
			if(@$this->connection->select_db($this->database)){
				$this->query('set names "'.$this->charSet.'" collate "'.$this->collate.'"');
				return true;
			}
			$this->error='Veri tabaný seçilemedi.';
			return false;
		}
		$this->error='Veri tabaný sunucusuna baðlanýlamadý.';return false;
	}
	
	function query($sql,$buffered=true){
		$this->affectedRows=0;
		$this->numRows=0;
		if(!$this->connection && !$this->connect())	return false;
		if(($buffered && $this->reader=$this->connection->query($sql)) ||
			(!$buffered && $this->reader=$this->connection->query($sql))){
				
				if(gettype($this->reader)=='object') 
					$this->numRows=$this->reader->num_rows; 
				else
					$this->affectedRows=$this->connection->affected_rows;
				
			return true;
		}
		$this->error='Sorgu çalýþtýrýlamadý.';return false;
	}
	
	function unbufferedQuery($sql){
		return $this->query($sql,false);
	}
	
	function fetchObject(){
		return $this->reader->fetch_object();
	}
	function fetchArray(){
		return $this->reader->fetch_array();
	}
	function fetchRow(){
		return $this->reader->fetch_row();
	}
	function nextIncrement($t){
		$this->query('show table status like \''.$t.'\'');
		$nau=$this->fetchObject(); // next auto_increment
		return $nau->Auto_increment;
	}
	function lastIncrement($t){
		return $this->nextIncrement($t)-1;
	}
	function getInsertId(){
		return $this->connection->insert_id;
	}
	function getError(){
		return $this->connection->error;
	}
	function fetchListByQuery($sql,$style='object'){
		if($this->query($sql) ){
			$arr=array();
			if($style=='object')
				while($r=$this->fetchObject()) $arr[]=$r;
			elseif($style=='array')
				while($r=$this->fetchArray()) $arr[]=$r;
			elseif($style=='row')
				while($r=$this->fetchRow()) $arr[]=$r;
			return $arr;
		}
		return false;
	}
	function fetchFirstRecord($q){
		if($this->query($q)){
			if($this->numRows>0)
			return $this->fetchObject();
		}
		return false;
	}
	
	/*
	 * aşağıdakiler yeni metodlardır. 
	 * üstekilerle değiştirilecektir zamanla
	 * */
	public function fetch($sql,$style='object'){
		return $this->fetchListByQuery($sql,$style='object');
	}
	public function fetchFirst($q){
		return $this->fetchFirstRecord($q);
	}
	
	public function escape($s,$strip=true){
		
		if(!$this->connection && !$this->connect())	return false;
		if(!is_array($s)){		
			if($strip){
				if(strpos($s,'\\\'')!==false || strpos($s,'\\"')!==false)
					$s=stripslashes($s);
			}
			return $this->connection->real_escape_string($s);
		}
		else{
			
			if($strip){
				foreach($s as $k=>$i)
				if(strpos($i,'\\\'')!==false || strpos($i,'\\"')!==false)
					$s[$k]=stripslashes($i);
			}
			foreach($s as $k=>$i)
				$s[$k]=$this->connection->real_escape_string($i);
				
			return $s;
		}
	}
}
?>
