<?php

$inData = getRequestInfo();

$firstName = $inData["firstName"];
$lastName = $inData["lastName"];
$phone = $inData["phone"];
$email = $inData["email"];
$userId = $inData["userId"];
$contactId = $inData["contactId"]; // Add this line to retrieve the contactID


$conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");

if ($conn->connect_error) {
    returnWithError($conn->connect_error);
} else {
   // $stmt = $conn->prepare("UPDATE Contacts SET FirstName=?, LastName=?, Phone=?, Email=? WHERE UserID=?");
   // $stmt->bind_param("sssss", $firstName, $lastName, $phone, $email, $userId);
   $stmt = $conn->prepare("UPDATE Contacts SET FirstName=?, LastName=?, Phone=?, Email=? WHERE UserID=? AND ID=?"); // Modify the query to include ID
   $stmt->bind_param("ssssss", $firstName, $lastName, $phone, $email, $userId, $contactId); // Add $contactId to the bind_param
   $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $stmt->close();
        $conn->close();

        $updatedContactInfo = array(
            "firstName" => $firstName,
            "lastName" => $lastName,
            "phone" => $phone,
            "email" => $email,
            "userId" => $userId
        );

        $formattedContactInfo = json_encode($updatedContactInfo, JSON_PRETTY_PRINT);

        returnWithInfo($formattedContactInfo);
    } else {
        $stmt->close();
        $conn->close();

        returnWithError("Contact not updated.");
    }
}

    function getRequestInfo()
    {
        return json_decode(file_get_contents('php://input'), true);
    }

    function sendResultInfoAsJson($obj)
    {
        header('Content-type: application/json');
        echo $obj;
    }

    // function returnWithInfo($info)
    //     {
    //         $retValue ='{"' . $info .  '"  "Contact has been updated"}';
    //         sendResultInfoAsJson( $retValue );
    //     }

    function returnWithInfo($info)
    {
        $retValue = '{"status": "success", "message": "Contact has been updated", "data": ' . $info . '}';
        sendResultInfoAsJson($retValue);   
    }


    function returnWithError($error)                        
        {
            $retValue = '{"status": "error", "error": "' . $error . '"}';
            sendResultInfoAsJson($retValue);
        }
?>
