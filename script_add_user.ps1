# format du fichier txt : Prenom;Nom;Mail;mdp_en_base_64
foreach($line in Get-Content F:\list_user.txt) {
    	$data = $line -split ';'
    	echo $data
	echo $data[0].ToUpper()
	echo "$($data[0]) $($data[1])"
	$sam_account_name = "$($data[0].ToLower()).$($data[1].ToLower().substring(0, 1))"
	$UserPrincipalName = "$($sam_account_name)@chatelet.local"
	$EmailAddress = "$($data[2])"
	$Path = "OU=Utilisateurs;DC=chatelet;DC=local"
	#$DecodedPasswd = [System.Text.Encoding]::Unicode.GetString([System.Convert]::FromBase64String($data[3]))
	
	New-ADUser -Name "$($data[0]) $($data[1])" -DisplayName "$($data[0].ToUpper()) $($data[1])" -GivenName "$($data[0])" -Surname "$($data[1])" -SamAccountName "$($sam_account_name)" -UserPrincipalName "$($UserPrincipalName)" -EmailAddress "$($EmailAddress)" -Path "$($Path)" -AccountPassword (ConvertTo-SecureString "root00R" -AsPlainText -Force) -Enabled $true
	add-ADGroupMember -Identity Medecin -Members $sam_account_name
}

Clear-Content "F:\list_user.txt"
