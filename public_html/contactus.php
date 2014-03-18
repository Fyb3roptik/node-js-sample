<?php
if(true == array_key_exists('formOPTin', $_POST)) {
	//they're a botz0rz
	exit;
}
// get posted data into local variables
$EmailFrom = Trim(stripslashes($_POST['EmailFrom'])); 
$EmailTo = "contactus@siing.co";
$Subject = "Contact Us";
$FirstName = Trim(stripslashes($_POST['FirstName'])); 
$LastName = Trim(stripslashes($_POST['LastName'])); 
$Company = Trim(stripslashes($_POST['Company'])); 
$EmailFrom = Trim(stripslashes($_POST['EmailFrom'])); 
$Address = Trim(stripslashes($_POST['Address'])); 
$City = Trim(stripslashes($_POST['City'])); 
$State = Trim(stripslashes($_POST['State'])); 
$Zip = Trim(stripslashes($_POST['Zip'])); 
$Country = Trim(stripslashes($_POST['Country'])); 
$Phone = Trim(stripslashes($_POST['Phone'])); 
$Fax = Trim(stripslashes($_POST['Fax'])); 
$Comments = Trim(stripslashes($_POST['Comments'])); 

// prepare email body text
$Body = "";
$Body .= "First Name: ";
$Body .= $FirstName;
$Body .= "\n";
$Body .= "Last Name: ";
$Body .= $LastName;
$Body .= "\n";
$Body .= "Company: ";
$Body .= $Company;
$Body .= "\n";
$Body .= "Email From: ";
$Body .= $EmailFrom;
$Body .= "\n";
$Body .= "Address: ";
$Body .= $Address;
$Body .= "\n";
$Body .= "City: ";
$Body .= $City;
$Body .= "\n";
$Body .= "State: ";
$Body .= $State;
$Body .= "\n";
$Body .= "Zip: ";
$Body .= $Zip;
$Body .= "\n";
$Body .= "Country: ";
$Body .= $Country;
$Body .= "\n";
$Body .= "Phone: ";
$Body .= $Phone;
$Body .= "\n";
$Body .= "Fax: ";
$Body .= $Fax;
$Body .= "\n";
$Body .= "Comments: ";
$Body .= $Comments;
$Body .= "\n";

// send email 
$success = mail($EmailTo, $Subject, $Body, "From: <$EmailFrom>");

// redirect to success page 
if ($success)
{header("Location: /pages/success.html");}
else 
{header("Location: /pages/email_failure.html");}
?> 
