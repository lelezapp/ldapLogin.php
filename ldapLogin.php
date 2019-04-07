<?php
/*
Script Name: ldapLogin.php
Author: Riontino Raffaele
Author URI: https://www.lelezapp.it/
Description: example script for ldap authentication in PHP
Version: 1.0
*/

$ldapDomain = "@domain.lan"; 			// set here your ldap domain
$ldapHost = "ldap://192.168.10.110"; 	// set here your ldap host
$ldapPort = "389"; 						// ldap Port (default 389)
$ldapUser  = ""; 						// ldap User (rdn or dn)
$ldapPassword = ""; 					// ldap associated Password  

$successMessage = "";
$errorMessage = "";

// connect to ldap server
$ldapConnection = ldap_connect($ldapHost, $ldapPort) 
	or die("Could not connect to Ldap server.");

if (isset($_POST["ldapLogin"])){

	if ($ldapConnection) {
		
		if (isset($_POST["user"]) && $_POST["user"] != "") 
			$ldapUser = addslashes(trim($_POST["user"]));
		else 
			$errorMessage = "Invalid User value!!";
		
		if (isset($_POST["password"]) && $_POST["password"] != "") 
			$ldapPassword = addslashes(trim($_POST["password"]));
		else 
			$errorMessage = "Invalid Password value!!";
		
		if ($errorMessage == ""){
			// binding to ldap server
			ldap_set_option($ldapConnection, LDAP_OPT_PROTOCOL_VERSION, 3) or die('Unable to set LDAP protocol version');
			ldap_set_option($ldapConnection, LDAP_OPT_REFERRALS, 0);
			$ldapbind = @ldap_bind($ldapConnection, $ldapUser . $ldapDomain, $ldapPassword);

			// verify binding
			if ($ldapbind){
				ldap_close($ldapConnection);	// close ldap connection
				$successMessage = "Login done correctly!!";
			} 
			else 
				$errorMessage = "Invalid credentials!";
		}
	}
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Ldap Login</title>
	</head>
	<body data-rsssl=1 data-rsssl=1>
		<?php		
			if ($errorMessage != "") echo "<h3 style='color:blue;'>$errorMessage</h3>";
			if ($successMessage != "") echo "<h3 style='color:blue;'>$successMessage</h3>";
		?>
		<h3 style="color:orange">Ldap Login</h3>
		
		<form action="" method="post" style="display:inline-block;">
			<table style="display:inline-block;">
				<tr>
					<td>User</td>
					<td><input type="text" name="user" value="" maxlength="50"></td>
				</tr>
				<tr>
					<td>Password</td>
					<td><input type="password" name="password" value="" maxlength="50"></td>
				</tr>
				<tr>
					<td colspan="2"><input type="submit" name="ldapLogin" value="Ldap Login"></td>
				</tr>
			</table>
		</form>
	</body>
</html>
