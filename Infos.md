Input => IN (inputs)
	Email => EM (email)
	Pass => PW (password)
	User => USR (user)
	Age => AG (age)
	Repeat => RPT (password repeat)
	Comment = CMT

Statement => STMT 
	Execution => Exe (exécution du stmt database)
	DBCheck => DBCH
	Insertion DB => INSRT
	RowCount => CNT (user exists)

Signup => SGN
	Taken => TKN (email taken or pw doesn't match)
	Password not matching => PWM (password doesn't match)

Login => LGN

Get User DB data => GET
	Recipe => RCP
	Check => CHK
	Data from inputs, url => DATA


Logged User => LGGDUSR
	Not Logged => OFF

Recipe Creation => RCPCREATE


Codes erreur :
"STMTSGNDBCHCNT - Cet utilisateur existe déjà"
"STMTSGNDBCHCNTEM - Cet email existe déjà"
"STMTSGNEXEDBCH - Failed"
"STMTSGNINSRTUSR - Failed"
"SGNTKN : On n'a pas pu check les inputs"
"SGNPWM : Les mots de passes ne sont pas identiques"
"STMTLGNGETUSR - Failed"
"STMTLGNGETRCP - Failed"
"STMTLGNGETPW - Failed"
"STMTLGNGETPWCNT - L'utilisateur n'a pas été trouvé"
"STMTRCPGETPWCNT - L'utilisateur n'a pas été trouvé"
"STMTRCPGETUSR - Failed"
"RCPLGGDUSROFF - Veuillez vous identifier avant de partager une recette."
"RCPDATACHK - Vous n'avez pas sélectionné la bonne recette"
