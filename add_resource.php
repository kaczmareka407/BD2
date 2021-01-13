<?php
include("connect_to_database.php");
	$book_id = $_POST['book_id'];
	$resource_link = $_POST['rlink'];
	$resource_category = $_POST['category'];
	
	//load tagID from database
	if (!($stmt = $conn->prepare('SELECT * FROM resource_category WHERE tagName = ?')))
	{
        echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
    }
                
	if (!$stmt->bind_param("s", $resource_category)) 
	{
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }
                
	if (!$stmt->execute()) 
	{
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }
	$result = $stmt->get_result();
	$stmt->close();
	$tagID = $result->fetch_assoc()['ID'];

	//insert values
	if (!($stmt = $conn->prepare('INSERT INTO book_resources (bookID,tagID,link) VALUES(?,?,?)')))
	{
        echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
    }
                
	if (!$stmt->bind_param("iis",$book_id, $tagID, $resource_link)) 
	{
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }
                
	if (!$stmt->execute()) 
	{
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }
	$stmt->close();
    $conn->close();
	echo
	'<script>history.go(-1);</script>'
	;
?>