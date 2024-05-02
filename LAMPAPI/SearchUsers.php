<?php

	$inData = getRequestInfo();

	$searchResults = "";
	$searchCount = 0;

	$conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");
	if ($conn->connect_error)
	{
		returnWithError( $conn->connect_error );
	}
	else
	{
		// $stmt = $conn->prepare("SELECT * FROM Users WHERE (Login like ?) AND ID=?");
		// $colorName = "%" . $inData["search"] . "%";
		// $stmt->bind_param("ss", $colorName, $inData["ID"]);
		// $stmt->execute();

		$stmt = $conn->prepare("SELECT * FROM Users WHERE (Login like ?) AND ID=?");
		$loginName = "%" . $inData["search"] . "%";
		$stmt->bind_param("ss", $loginName, $inData["ID"]);
		$stmt->execute();


		$result = $stmt->get_result();

		while($row = $result->fetch_assoc())
		{
			if( $searchCount > 0 )
			{
				$searchResults .= ",";
			}
			$searchCount++;
			$searchResults .= '{"Login" : "' . $row["Login"]. '", "ID" : "' . $row["ID"]. '"}';
		}

		if( $searchCount == 0 )
		{
			returnWithError( "No Records Found" );
		}
		else
		{
			returnWithInfo( $searchResults );
		}

		$stmt->close();
		$conn->close();
	}

	function getRequestInfo()
	{
		return json_decode(file_get_contents('php://input'), true);
	}

	function sendResultInfoAsJson( $obj )
	{
		header('Content-type: application/json');
		echo $obj;
	}

	function returnWithError( $err )
	{
		$retValue = '{"Login":"","ID":"","' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}

	function returnWithInfo( $searchResults )
	{
		$retValue = '{' . $searchResults . '}';
		sendResultInfoAsJson( $retValue );
	}

?>