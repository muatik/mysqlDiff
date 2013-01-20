<?php
/**
 * http://github.com/muatik/dbDiffs
 * http://cookingthecode.com/a48_Veritabanlari-Arasindaki-Farklar
 * Mustafa Atik 
 * Apr 14 2011
 * */

require_once('db.php');
require_once('arrays.php');
class dbDiffs
{
	
	public $db;
	
	public function __construct(){
		
		// tablo karşılaştırma kriterleri.
		$this->tableFields=array(
			'ENGINE','TABLE_COLLATION','TABLE_COMMENT'
		);
		
		// sütun karşılaştırma kriterleri
		$this->columnFields=array(
			'COLUMN_TYPE','COLUMN_DEFAULT','IS_NULLABLE',
			'CHARACTER_SET_NAME','COLLATION_NAME',
			'COLUMN_KEY','EXTRA','COLUMN_COMMENT'
		);
		
		$this->db=new db();
		$this->db->database='information_schema';
	}
	
	/**
	 * Adı belirtilen iki veritabanını arasındaki farklılıkları bulur
	 * @param db1 birinci veritabanının adı
	 * @param db2 ikinci veritabanının adı
	 * @return array
	 * */
	public function check($db1Name,$db2Name){
		$diffs=new stdClass;
		
		$db1=new stdClass;
		$db2=new stdClass;
		$db1->tables=$this->fetchTables($db1Name);
		$db2->tables=$this->fetchTables($db2Name);
		
		// db1'in db2'den farklı tabloları alınıyor
		$diffs->tableDiff=arrays::diff(
			$db2->tables,$db1->tables,'TABLE_NAME','to'
		);
		// db2'in db1'den farklı tabloları alınıyor
		$diffs->tableDiffr=arrays::diff(
			$db2->tables,$db1->tables,'TABLE_NAME','from'
		);
		
		// ikisinde de olan tablo listesi hazırlanıyor
		$tables=arrays::intersect(
			$db1->tables,$db2->tables,'TABLE_NAME'
		);
		
		
		
		foreach($tables as $t){
			
			// iki tablo yapısı karşılaştırılıyor
			$tblDiff=$this->compareObjects(
				$db1->tables[$t],
				$db2->tables[$t],
				$this->tableFields
			);
			
			
			if(count($tblDiff)>0)
				$diffs->tables[$t]->structure=$tblDiff;
			
			
			
			// tablo sütunları çekiliyor
			$t1Clm=$this->fetchColumns($db1Name,$t);
			$t2Clm=$this->fetchColumns($db2Name,$t);
			
			// db1 tablosu ile db2 tablosu arasındaki sütun 
			// farklılıklarına bakılıyor
			$diffs->tables[$t]->columnDiff=arrays::diff(
				$t1Clm,$t2Clm,'COLUMN_NAME','to'
			);
			// ve tam tersi, db2'nin db1'den farkları
			$diffs->tables[$t]->columnDiffr=arrays::diff(
				$t1Clm,$t2Clm,'COLUMN_NAME','from'
			);
			
			// ikisinde de olan sütun listesi hazırlanıyor
			$columns=arrays::intersect(
				$t1Clm,$t2Clm,'COLUMN_NAME'
			);
			
			
			foreach($columns as $c){
				
				// iki tablo yapısı karşılaştırılıyor
				$cDiff=$this->compareObjects(
					$t1Clm[$c],
					$t2Clm[$c],
					$this->columnFields
				);
				
				if(count($cDiff)>0)
					$diffs->tables[$t]->columns[$c]=$cDiff;
			}
			
		}
		
		return $diffs;
		
	}
	
	
	/**
	 * iki nesneyi belirtilen alanlara göre karşılaştırır ve 
	 * uyuşmazlıkları verir.
	 * @param object o1 ilk nesne
	 * @param object o2 ikinci nesne
	 * @param filelds karşılaştırmanın yapılacağı nesne özellikleri
	 * @return array farklılıklar
	 * */
	private function compareObjects($o1,$o2,$fields){
		
		$diffs=array();
		foreach($fields as $f){
			
			if($o1->$f==$o2->$f) continue;
			
			$diff=array('field'=>$f,'value1'=>$o1->$f,
				'value2'=>$o2->$f
			);
			
			$diffs[]=$diff;
		}
		return $diffs;
	}
	
	
	/**
	 * Belirtilen veritabanındaki tablo kayıtlarını verir.
	 * @param db veritabanının adı
	 * */
	public function fetchTables($db){
		$sql='select * from TABLES
		where TABLE_SCHEMA=\''.$db.'\'';
		
		$rs=$this->db->fetch($sql);
		
		$rs2=array();
		foreach($rs as $i)
			$rs2[$i->TABLE_NAME]=$i;
		return $rs2;
	}
	
	/**
	 * Belirtilen tablodaki sütun kayıtlarını verir.
	 * */
	public function fetchColumns($db,$table){
		$sql='select * from COLUMNS 
		where TABLE_SCHEMA=\''.$db.'\' and TABLE_NAME=\''.$table.'\'';
		$rs=$this->db->fetch($sql);
		
		$rs2=array();
		foreach($rs as $i)
			$rs2[$i->COLUMN_NAME]=$i;
		return $rs2;
	}
	
	/**
	 * Erişim iznine sahip veritabanlarının listesini verir.
	 * */
	public function getDbList(){
		return $this->db->fetch(
			'select SCHEMA_NAME as name from SCHEMATA'
		);
	}
}


?>
