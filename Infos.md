
# Pour la recherche dans la barre de navigation

<br>

```
Modification APACHE de la redirection HTTPD dans le httpd.conf 
Cela permettra à la recherche de pouvoir se réactualiser facilement

Si un utilisateur tente d'accéder à la navigation, cela créera une erreur 403
```
<br>

## Désactiver la possibilité pour l'utilisateur de naviguer dans le dossier serveur 

Dans le <Directory "var/www/"> du fichier Apache httpd.conf au lieu de :

1. ` Options Indexes FollowSymLinks Includes ExecCGI `

Le modifier par :

2. ` Options -Indexes +FollowSymLinks `

## Permettre une lecture directe du fichier quand un pathname est entré dans la barre d'adresse

Rajouter le nom des fichiers à lire dans le directory index :
` recherche.php \ `  

<br>
<br>

# Il est possible d'utiliser VITE si installé 

<br>

```
S'assurer que NodeJs est installé sur la machine
```
<br>

## Pour lancer une build

Dans le terminal, saisir ce code :
1. `npm run dev`

## Pour construire une build

Dans le terminal, saisir ce code :
1. `npm run build`
npm install postcss-preset-env --save-dev 
npm install postcss postcss-preset-env --save-dev 

20

Add postcss and autoprefixer: yarn add -D postcss@latest autoprefixer@latest

Install postcss and autoprefixer with:
npm i autoprefixer -D
npm i postcss -D

then add a file postcss.config.js on your root project directory:

module.exports = {
  plugins: {
    autoprefixer: {}
  }
}
ℹ️ You can find bellow an example of vite.config.ts for a new project without a postcss config file.

import { defineConfig } from "vite"
import react from '@vitejs/plugin-react'
import autoprefixer from 'autoprefixer'

export default defineConfig({
  plugins: [
    react()
  ],
  css: {
    postcss: {
      plugins: [
        autoprefixer({}) // add options if needed
      ],
    }
  }
})
And if it still doesn't work please provide a reproducible project.

https://medium.com/@abheet.etuitions.com/this-is-how-you-can-create-dark-mode-for-your-website-using-css-85749bf40000


https://redstapler.co/pure-css-minimal-dark-mode-toggle-button/


<br>
<br>

Input => IN (inputs)
	Email => EM (email)
	Pass => PW (password)
	User => USR (user)
	Age => AG (age)
	Repeat => RPT (password repeat)
	Comment = CMT

Statement => STMT 
	Execution => EXE (exécution du stmt database)
	DBCheck => DBCH
	Insertion DB => INSRT
	RowCount => CNT (user exists / data already exists / no changes)

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

Recipe Update => RCPUPDT
	Update DB with new datas => STMT


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
"RCPUPDTSTMTEXECNT - Aucun changement opéré"


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


