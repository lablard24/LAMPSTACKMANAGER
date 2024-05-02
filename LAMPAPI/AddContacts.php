<?php

    $inData = getRequestInfo();

    $firstName = $inData["firstName"];
    $lastName = $inData["lastName"];
    $phone = $inData["phone"];
    $email = $inData["email"];
    $userId = $inData["userId"];

    $conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");

    if ($conn->connect_error)
    {
        returnWithError( $conn->connect_error );
    }

    else
    {
        $stmt = $conn->prepare("INSERT into Contacts (FirstName, LastName, Phone, Email, UserID) VALUES(?,?,?,?,?)");
        $stmt->bind_param("sssss", $firstName, $lastName, $phone, $email, $userId);
        $stmt->execute();
        // $stmt->close();
        // $conn->close();

        if ($stmt->affected_rows > 0) {
            $stmt->close();
            $conn->close();


            $addedContactInfo = array(
                "firstName" => $firstName,
                "lastName" => $lastName,
                "phone" => $phone,
                "email" => $email,
                "userId" => $userId
            );


            $formattedContactInfo = json_encode($addedContactInfo, JSON_PRETTY_PRINT);

            returnWithInfo($formattedContactInfo);

        } else {
            $stmt->close();
            $conn->close();

            returnWithError("Contact not added.");
        }
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

    function returnWithInfo($info)
    {
        $retValue = json_encode(["result" => "success", "info" => $info]);
        sendResultInfoAsJson($retValue);
    }


    function returnWithError($error)
    {
        $retValue = '{"status": "error", "error": "' . $error . '"}';
        sendResultInfoAsJson($retValue);
    }
?>