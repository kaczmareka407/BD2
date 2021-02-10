<?php
session_start();
require("functions.php");
include("connect_to_database.php");

if (!($stmt = $conn->prepare('SELECT * FROM books WHERE ID = ?')))
			{
                echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
            }
                
			if (!$stmt->bind_param("i", $_GET["book_id"])) 
			{
                echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
            }
                
			if (!$stmt->execute()) 
			{
                echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            }
			
			$result = $stmt->get_result();
			$stmt->close();
            $conn->close();
			
	
	if(mysqli_num_rows($result)>0)displayBook($_GET["book_id"]);
	else
	{
		echo '<div style="margin: 20px; padding: 20px; outline: 6px solid red; font-weight: bold; font-size: 50px; text-align: center;">
		Book not found
		<br>
		<button onclick="goBack()" style="font-size: 25px;">Go Back</button>
		</div>';
		echo '
			<script>
				function goBack()
				{
					window.history.back();
				}
			</script>
		';
	}
?>
