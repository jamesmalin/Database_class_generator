<?php
include('includes/db_class.php');

/*
Display all the article titles.
The selectAll() function returns the values as an object
*/
echo '<b>Display all the articles</b>: <br>';
$a1 = new Article();
$result = $a1->selectAll();
foreach($result as $value) { 
	echo $value->title.' (id: '.$value->id.')<br>';
}

echo '<br>';

/*
Display the articles by id
No need to escape the variables since they are escaped automatically by the loadByFields() function.
*/
echo '<b>Display all the articles by id</b>: <br>';
$id = 3;
$a1 = new Article();
$result = $a1->loadByFields('id',$id);
foreach($result as $value) { 
	echo $value->title.' (id: '.$value->id.')<br>';
}

/*
Example with several conditions (select by id, by title and by description):
$a1->loadByFields('id,description,date',"$id,$description,$date");
*/

echo '<br>';

/*
Select with a custom Query
You can use the function escape() to escape the variable given as paramers.
The results are inside an array (that is indexed and starts at 0).
The field names are the same names that the fields returned from the query. So if you select a title from the database, you can use the name "title" to get it from the array returned.
*/
$id = '2';
echo '<b>Select with a custom Query</b>: <br>';
$sql = "SELECT * FROM article WHERE id>'".$a1->escape($id)."'";
$a1 = new Article();
$result = $a1->customQuery($sql);
foreach($result as $value) {
	echo $value['title'].' (id: '.$value['id'].')<br>';
}

echo '<br>';

/*
Update a row
Values are escaped natively by the updateByFields() functions
*/
echo '<b>Update a specific row by fields</b>: <br>';
$row_id_to_update = 4;
$updated_title = 'Title article 44';
$updated_description = 'New description of the title 4';
$fields = array('title'=>$updated_title, 'description'=>$updated_description);
$a1 = new Article();
$result = $a1->updateByFields($fields,$row_id_to_update);
echo 'Done ('.$result.')<br>';

echo '<br>';

/*
Insert a new row to the table article
Values are escaped natively by the insert() functions
Note that all the set functions have as a suffix the name of the corresponding field of the table.
So if your table field is named "created", the related set would be "setCreated()". 
The first letter of the field is always an upper case.
*/
echo '<b>Insert a new row</b>: <br>';
$title = "Article with '' and \"\" and any other special chracters";
$description = 'new description';
$a1 = new Article();
$a1->setTitle($title);
$a1->setDescription($description);
$inserted_id = $a1->insert();
echo $inserted_id.'<br>';

echo '<br>';

/*
Delete a row by id
the id is escaped by the delete function
*/
echo '<b>Delete a row by id</b>: <br>';
$a1 = new Article();
$a1->delete($inserted_id-1);
echo 'Row id = '.$inserted_id.' deleted<br>';

echo '<br>';

/*
Execute query
*/
$id = 45;
echo '<b>Delete with a custom query</b>: <br>';
$a1 = new Article();
$sql = "DELETE FROM article WHERE id>'".$a1->escape($id)."'";
//$a1->executeQuery($sql);

?>