[vite:css] grid-auto-columns is not supported by IE
345|      .form-index {
346|          display: grid;
347|          grid-auto-columns: 1fr;
   |          ^^^^^^^^^^^^^^^^^^^^^^^
348|          grid-template-areas:
349|          "username username password password"
[vite:css] IE does not support justify-items on grid containers. Try using justify-self on child elements instead: .form-index > * { justify-self: center }
350|          "submit submit submit submit"
351|          "recovery recovery . .";
352|          justify-items: center;
   |          ^^^^^^^^^^^^^^^^^^^^^^
353|      }
354|
[vite:css] Autoplacement does not work without grid-template-rows property
420|  #recovery-input {
421|      & > .signupsuccess {
422|          grid-template-columns: repeat(2, 400px);
   |          ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
423|      }
424|      gap: 1.2rem;
[vite:css] IE does not support justify-content on grid containers
449|  .contact {
450|      display: grid;
451|      justify-content: center;
   |      ^^^^^^^^^^^^^^^^^^^^^^^^
452|      align-items: center;
453|      max-width: 100%;
[vite:css] IE does not support align-items on grid containers. Try using align-self on child elements instead: .contact > * { align-self: center }
450|      display: grid;
451|      justify-content: center;
452|      align-items: center;
   |      ^^^^^^^^^^^^^^^^^^^^
453|      max-width: 100%;
454|      width: 100%;
[vite:css] IE does not support justify-content on grid containers
484|
485|      display: grid;
486|      justify-content: center;
   |      ^^^^^^^^^^^^^^^^^^^^^^^^
487|      /* grid: minmax(50px, min-content) / repeat(auto-fill, minmax(400px, 650px)); */
488|      /* grid-template-columns: repeat(2, auto); */
[vite:css] Autoplacement does not work without grid-template-rows property
487|      /* grid: minmax(50px, min-content) / repeat(auto-fill, minmax(400px, 650px)); */
488|      /* grid-template-columns: repeat(2, auto); */
489|      grid-template-columns: repeat(2, 1fr);
   |      ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
490|      /* grid-template-columns: repeat(2, 50%);
491|      /* position: relative; */
[vite:css] IE does not support align-items on grid containers. Try using align-self on child elements instead: .contact-grid > * { align-self: center }
491|      /* position: relative; */
492|
493|      align-items: center;
   |      ^^^^^^^^^^^^^^^^^^^^
494|      align-content: center;
495|      justify-items: end;
[vite:css] IE does not support align-content on grid containers
492|
493|      align-items: center;
494|      align-content: center;
   |      ^^^^^^^^^^^^^^^^^^^^^^
495|      justify-items: end;
496|
[vite:css] IE does not support justify-items on grid containers. Try using justify-self on child elements instead: .contact-grid > * { justify-self: end }
493|      align-items: center;
494|      align-content: center;
495|      justify-items: end;
   |      ^^^^^^^^^^^^^^^^^^^
496|
497|      @media screen and (min-width: 576px) and (max-width: 992px) {
[vite:css] IE does not support justify-content on grid containers
581|  .pw-recovery {
582|      display: grid;
583|      justify-content: center;
   |      ^^^^^^^^^^^^^^^^^^^^^^^^
584|      grid: minmax(50px, min-content) / repeat(auto-fill, minmax(400px, 650px));
585|      /* grid-template-columns: repeat(2, auto); */
[vite:css] auto-fill value is not supported by IE
582|      display: grid;
583|      justify-content: center;
584|      grid: minmax(50px, min-content) / repeat(auto-fill, minmax(400px, 650px));
   |                                               ^^^^^^^^^
585|      /* grid-template-columns: repeat(2, auto); */
586|
[vite:css] IE does not support align-items on grid containers. Try using align-self on child elements instead: .pw-recovery > * { align-self: center }
585|      /* grid-template-columns: repeat(2, auto); */
586|
587|      align-items: center;
   |      ^^^^^^^^^^^^^^^^^^^^
588|      align-content: center;
589|
[vite:css] IE does not support align-content on grid containers
586|
587|      align-items: center;
588|      align-content: center;
   |      ^^^^^^^^^^^^^^^^^^^^^^
589|
590|      @media screen and (max-width: 992px) {
[vite:css] Autoplacement does not work without grid-template-rows property
995|  @keyframes gridContraction {
996|      to {
997|          grid-template-columns: repeat(2, 50%);
   |          ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
998|      }
999|  }
[vite:css] Autoplacement does not work without grid-template-rows property
1219|      display: grid;
1220|      /* gap: 2rem; */
1221|      grid-template-columns: repeat(3, 1fr);
   |      ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
1222|      align-content: center;
1223|      justify-items: start;
[vite:css] IE does not support align-content on grid containers
1220|      /* gap: 2rem; */
1221|      grid-template-columns: repeat(3, 1fr);
1222|      align-content: center;
   |      ^^^^^^^^^^^^^^^^^^^^^^
1223|      justify-items: start;
1224|      align-items: center;
[vite:css] IE does not support justify-items on grid containers. Try using justify-self on child elements instead: .time > * { justify-self: start }
1221|      grid-template-columns: repeat(3, 1fr);
1222|      align-content: center;
1223|      justify-items: start;
   |      ^^^^^^^^^^^^^^^^^^^^^
1224|      align-items: center;
1225|      & input {
[vite:css] IE does not support align-items on grid containers. Try using align-self on child elements instead: .time > * { align-self: center }
1222|      align-content: center;
1223|      justify-items: start;
1224|      align-items: center;
   |      ^^^^^^^^^^^^^^^^^^^^
1225|      & input {
1226|          width: 40px;
[vite:css] auto-fit value is not supported by IE
1241|  } */
1242|  .ingredient {
1243|      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
   |                                    ^^^^^^^^
1244|      display: grid;
1245|      grid-gap: 5px;
[vite:css] grid-gap only works if grid-template(-areas) is being used or both rows and columns have been declared and cells have not been manually placed inside the explicit grid
1243|      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
1244|      display: grid;
1245|      grid-gap: 5px;
   |      ^^^^^^^^^^^^^^
1246|      & > select {
1247|          margin-left: 0;
[vite:css] IE does not support align-items on grid containers. Try using align-self on child elements instead: .navbar-grid > * { align-self: center }
1620|      width: 100%;
1621|      display: grid;
1622|      align-items: center;
   |      ^^^^^^^^^^^^^^^^^^^^
1623|      justify-items: center;
1624|      justify-content: center;
[vite:css] IE does not support justify-items on grid containers. Try using justify-self on child elements instead: .navbar-grid > * { justify-self: center }
1621|      display: grid;
1622|      align-items: center;
1623|      justify-items: center;
   |      ^^^^^^^^^^^^^^^^^^^^^^
1624|      justify-content: center;
1625|      /* overflow: hidden; */
[vite:css] IE does not support justify-content on grid containers
1622|      align-items: center;
1623|      justify-items: center;
1624|      justify-content: center;
   |      ^^^^^^^^^^^^^^^^^^^^^^^^
1625|      /* overflow: hidden; */
1626|      /* border-radius: 100vh; */
[vite:css] Autoprefixer currently does not support line names. Try using grid-template-areas instead.
1631|      gap: 1rem;
1632|      grid-template-rows:
1633|          [top-start] 1fr
   |          ^
1634|          [top-end center-start] 80px
1635|          [center-end bottom-start] minmax(0, 0) [bottom-end];
[vite:css] Autoprefixer currently does not support line names. Try using grid-template-areas instead.
1635|          [center-end bottom-start] minmax(0, 0) [bottom-end];
1636|      grid-template-columns:
1637|          /* [logo-start] 100px [logo-end] 1fr [search-start] 1fr */
   |             ^
1638|          /* [search-end menu-start] minmax(0, 600px) */
1639|          /* [menu-end register-start] 1fr [register-end]; */
[vite:css] IE does not support subgrid
1664|          grid-column: search / burger;
1665|          grid-row: center;
1666|          grid-template-columns: subgrid;
   |          ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
1667|          grid-template-rows: subgrid;
1668|          /* gap: 5rem; */
[vite:css] IE does not support subgrid
1665|          grid-row: center;
1666|          grid-template-columns: subgrid;
1667|          grid-template-rows: subgrid;
   |          ^^^^^^^^^^^^^^^^^^^^^^^^^^^^
1668|          /* gap: 5rem; */
1669|          align-items: center;
[vite:css] IE does not support align-items on grid containers. Try using align-self on child elements instead: .navbar-grid > .navbar > * { align-self: center }
1667|          grid-template-rows: subgrid;
1668|          /* gap: 5rem; */
1669|          align-items: center;
   |          ^^^^^^^^^^^^^^^^^^^^
1670|          gap: .5rem;
1671|          position: sticky;