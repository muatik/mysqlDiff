<?php
function showDiffs($diff){
	echo '<ul>';
	
	foreach($diff->tableDiff as $i)
		echo '<li><b class="table exists">'.$i.'</b></li>';
	foreach($diff->tableDiffr as $i)
		echo '<li><b class="table absent">'.$i.'</b></li>';
		
	foreach($diff->tables as $t=>$i){
		
		if(!(
			isset($i->structure) 
			|| count($i->columnDiff)>0 
			|| count($i->columnDiffr)>0 
			|| isset($i->columns)
		))
			continue;
		
		echo '<li><b class="table">'.$t.'</b><ul>';
		
		if(isset($i->structure)){
			echo '<li><b class="group">STRUCTURE:</b><ul>';
			foreach($i->structure as $s) {
				echo '<li><b class="field">'.$s['field'].'</b><ul>';
					if($s['value1']!='')
						echo '<li class="value1">'.$s['value1'].'</li>';
					if($s['value2']!='')
						echo '<li class="value2">'.$s['value2'].'</li>';
					
				echo '</ul>
				</li>';
			}
			echo '</ul></li>';
		}
		
		
		if(!(
			count($i->columnDiff)>0 
			|| count($i->columnDiffr)>0 
			|| isset($i->columns)
		)){
			echo '</ul></li>';
			continue;
		}
		
		echo '<li><b class="group">COLUMNS:</b><ul>';
		
		foreach($i->columnDiff as $c)
			echo '<li><b class="column exists">'.$c.'</b></li>';
		foreach($i->columnDiffr as $c)
			echo '<li><b class="column absent">'.$c.'</b></li>';
		
		if(isset($i->columns))
		foreach($i->columns as $cName=>$c){
			echo '<li><b class="column">'.$cName.'</b><ul>';
			foreach($c as $ci){
				echo '<li><b class="field">'.$ci['field'].'</b><ul>';
				if($ci['value1']!='')
					echo '<li class="value1">'.$ci['value1'].'</li>';
				if($ci['value2']!='')
					echo '<li class="value2">'.$ci['value2'].'</li>';
				echo '</ul></li>';
			}
			echo '</ul></li>';
		}
		echo '</ul></li>';
		
		
		echo '</ul></li>';
	}
	echo '</ul>';
}
?>
