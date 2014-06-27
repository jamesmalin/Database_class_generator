<?php
include_once("MysqlTable.php");
include_once("Db_connection.php");


$m1 = new MySqlTable();
$sql = 'show tables';
$result = $m1->customQuery($sql);

//get tables and fields names
for($i=0; $i<count($result); $i++) {
	$table_name = $result[$i][0];
	$tab[$i]['table'] = $table_name;
	$sql = "DESCRIBE $table_name";
	$result2 = $m1->customQuery($sql);
	for($j=0; $j<count($result2); $j++) {
		$field = $result2[$j][0];
		$tab[$i]['fields'][] = $field;
	}
}

//gen tables classes
echo '<b>Generating classes for these tables:</b>';
foreach($tab as $value) {
	$table = $value['table'];
	$fields = $value['fields'];
	echo '<br>'.$table;
	genClass($table,$fields);
}

// include_once('Adresse.php');


echo '<br><br>';
echo '<b>Generating the tables list file:</b><br>';
$tablesList='';
for($i=0; $i<count($tab); $i++) {
	$tablesList .= "include_once('".ucfirst($tab[$i]['table']).".php');\n";
}

$tablesList = '<?php'."\n\n".$tablesList."\n".'?>';
createFile('class/tablesList.php',$tablesList);
echo 'Done';

function writeFile($table,$content) {
	$file = 'class/'.ucfirst($table).'.php';
	if (file_exists($file)) {
		unlink($file);
	}
	createFile($file,$content);
}

// generate the class table files
// use db connection datas from the var_globals.php file of the mach-ii framework
function genClass($table,$tab) {
	
	$content='<?php'."\n\n";
	
	$table2 = ucfirst($table);
	foreach($tab as $value) {
		$tab2[] = ucfirst($value);
	}
	$content .= 'class '.$table2.' extends MySqlTable '."\n".'{ '."\n".''."\n".'';
	$content .= '// ##### PRIVATE PROPERTIES ##### // '."\n".'';
	foreach($tab as $v) {
		$content .= 'var $'.$v.'; '."\n".'';
	}

	$content .= ''."\n".' // ##### BEGIN DEFAULT FUNCTIONS ##### // '."\n".'';

	$content .= ''."\n".'';
	$content .= 'function '.$table2.'() '."\n".'{ '."\n".'';
	$content .= tab5().'parent::MySqlTable("'.$table.'"); '."\n".'}'."\n".''."\n".'';

	$content .= 'function loadFromArray($array) '."\n".'{ '."\n".'';
	$content .= '$inst1 = new '.$table2.'();'."\n";
	foreach($tab2 as $i2 => $v2) {
		$content .= tab5().'@$inst1->set'.$v2.'($array["'.$tab[$i2].'"]); '."\n".'';
	}
	$content .= tab5().'return $inst1; '."\n".'}'."\n".'';

	$content .= ''."\n".'';
	$content .= 'function loadIntoArray() '."\n".'{ '."\n".'';
	$content .= tab5().'$array = array(); '."\n".'';
	foreach($tab2 as $i2 => $v2) {
		$content .= tab5().'$array["'.$tab[$i2].'"] = $this->get'.$v2.'(); '."\n".'';
	}
	$content .= tab5().'return $array; '."\n".'}'."\n".'';
	
	$content .= ''."\n".''."\n".' // ##### END DEFAULT FUNCTIONS ##### //';
	
	$content .= ''."\n".''."\n".'';
	$content .= '// ##### SET PUBLIC METHODS ##### // '."\n".''."\n".'';
	foreach($tab2 as $i2 => $v2) {
		$content .= 'function set'.$v2.'($'.$tab[$i2].') '."\n".'{ '."\n".'';
		$content .= tab5().'$this->'.$tab[$i2].' = $'.$tab[$i2].'; '."\n".'';
		$content .= '}'."\n".'';
	}
	
	$content .= ''."\n".'';
	$content .= '// ##### GET PUBLIC METHODS ##### // '."\n".''."\n".'';
	foreach($tab2 as $i2 => $v2) {
		$content .= 'function get'.$v2.'() '."\n".'{ '."\n".'';
		$content .= tab5().'return $this->'.$tab[$i2].'; '."\n".'';
		$content .= '}'."\n".'';
	}
	$content .= ''."\n".'} // end of class';
	
	$content .= "\n\n".' ?>';
	
	writeFile($table,$content);
}

function createFile($file,$content) {
	$fh = fopen($file, 'w') or die("Can't create the file");
	fwrite($fh, $content);
	fclose($fh);
}

function tab5() {
	$content .= '';
}

?>