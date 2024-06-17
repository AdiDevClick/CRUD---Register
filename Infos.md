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


URL utiles :

Remote plugin pour vite : 
1: https://github.com/antfu/vite-plugin-remote-assets
2: https://laracasts.com/discuss/channels/vite/vite-doesnt-work-on-remote-dev-servers
3: https://vitejs.dev/config/server-options#server-hmr
Safari optimisation : 
1: https://stackoverflow.com/questions/2989263/disable-auto-zoom-in-input-text-tag-safari-on-iphone
2: https://til.simonwillison.net/css/resizing-textarea
Key press script : https://developer.mozilla.org/en-US/docs/Web/API/Event/preventDefault
JSON response - Header setup : 
https://stackoverflow.com/questions/70379431/cant-display-json-code-in-php-when-send-threw-fetch-in-javascript
JSON AJAX course : 
1: https://www.youtube.com/watch?v=82hnvUYY6QA
2: https://www.youtube.com/watch?v=crtwSmleWMA
3: https://www.youtube.com/watch?v=crtwSmleWMA


