<?php


    $inData = getRequestInfo();

    $firstName = $inData["firstName"];
    $lastName = $inData["lastName"];
    $userId = $inData["userId"];

    $conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");

    if ($conn->connect_error)
    {
        returnWithError( $conn->connect_error );
    }

    else
    {
        $stmt = $conn->prepare("DELETE FROM Contacts WHERE FirstName = ? AND LastName = ? AND UserID = ?");
        $stmt->bind_param("sss", $firstName, $lastName, $userId);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            returnWithError("Contact has been deleted");
        } else {
            returnWithError("Contact not found or could not be deleted. Error: " . $stmt->error);
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
        $retValue = '{"result":"' . $err . '"}';
        sendResultInfoAsJson( $retValue );
    }

?>
