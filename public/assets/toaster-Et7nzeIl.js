var D=(i,e,s)=>{if(!e.has(i))throw TypeError("Cannot "+s)};var t=(i,e,s)=>(D(i,e,"read from private field"),s?s.call(i):e.get(i)),c=(i,e,s)=>{if(e.has(i))throw TypeError("Cannot add the same private member more than once");e instanceof WeakSet?e.add(i):e.set(i,s)},p=(i,e,s,d)=>(D(i,e,"write to private field"),d?d.call(i,s):e.set(i,s),s);var m=(i,e,s)=>(D(i,e,"access private method"),s);function k(i,e={}){const s=document.createElement(i);for(const[d,V]of Object.entries(e))V!==null&&s.setAttribute(d,V);return s}function A(i,e){return new Promise(s=>{setTimeout(()=>{s(e)},i)})}var h;class B{constructor(e){c(this,h,void 0);this.alert=e.toasterContainer.querySelector(".toast"),p(this,h,new AbortController),e.toasterContainer.addEventListener("dragstart",s=>s.preventDefault(),{signal:t(this,h).signal}),e.toasterContainer.addEventListener("mousedown",this.startDrag.bind(this),{signal:t(this,h).signal,passive:!0}),e.toasterContainer.addEventListener("touchstart",this.startDrag.bind(this),{signal:t(this,h).signal,passive:!0}),window.addEventListener("mousemove",this.drag.bind(this),{signal:t(this,h).signal}),window.addEventListener("touchmove",this.drag.bind(this),{signal:t(this,h).signal}),window.addEventListener("touchend",this.endDrag.bind(this),{signal:t(this,h).signal}),window.addEventListener("mouseup",this.endDrag.bind(this),{signal:t(this,h).signal}),window.addEventListener("touchcancel",this.endDrag.bind(this),{signal:t(this,h).signal}),this.toaster=e,e.toasterContainer.addEventListener("onRemove",s=>{t(this,h).abort()},{once:!0})}startDrag(e){if(e.touches){if(e.touches.length>1)return;e=e.touches[0]}this.origin={x:e.screenX,y:e.screenY},this.disableTransition(),this.width=this.toaster.toasterContainer.offsetWidth}drag(e){if(this.origin){const s=e.touches?e.touches[0]:e,d={x:s.screenX-this.origin.x,y:s.screenY-this.origin.y};e.touches&&Math.abs(d.x)>Math.abs(d.y)&&(e.cancelable&&e.preventDefault(),e.stopPropagation()),this.lastTranslate=d,this.translate(100*d.x/this.width)}}translate(e){this.toaster.toasterContainer.style.transform="translate3d("+e+"%,  0, 0)"}disableTransition(){this.toaster.toasterContainer.style.transition="none"}enableTransition(){this.toaster.toasterContainer.style.transition=""}async endDrag(e){this.origin&&this.lastTranslate&&(this.enableTransition(),Math.abs(this.lastTranslate.x/this.toasterWidth)>.2&&(this.lastTranslate.x<0||this.lastTranslate.x>0)&&(this.alert.classList.add("close"),this.alert.addEventListener("animationend",()=>{this.toaster.toasterContainer.remove()},{once:!0}))),this.origin=null}get toasterWidth(){return this.toaster.toasterContainer.offsetWidth}}h=new WeakMap;var b,v,r,g,f,L,C,x,w,R,y,j,E,N,S,U,T,W;class O{constructor(e,s="Erreur"){c(this,w);c(this,y);c(this,E);c(this,S);c(this,T);c(this,b,void 0);c(this,v,void 0);c(this,r,void 0);c(this,g,void 0);c(this,f,void 0);c(this,L,void 0);c(this,C,void 0);c(this,x,void 0);p(this,b,e),p(this,v,s),this.toasterContainer=document.querySelector(".toast-container"),this.toasterContainer||(m(this,w,R).call(this),this.toasterContainer=document.querySelector(".toast-container")),p(this,x,this.toasterContainer.dataset.endpoint),p(this,L,JSON.parse(this.toasterContainer.dataset.elements)),p(this,C,document.querySelector(this.toasterContainer.dataset.template)),p(this,r,t(this,C).content.firstElementChild.cloneNode(!0)),p(this,g,t(this,r).querySelector(this.toasterContainer.dataset.progressbar)),p(this,f,t(this,r).querySelector(this.toasterContainer.dataset.closebtn)),m(this,E,N).call(this),m(this,y,j).call(this),t(this,r).addEventListener("animationend",d=>{m(this,S,U).call(this,d)},{once:!0}),t(this,f).addEventListener("click",d=>{m(this,T,W).call(this,d)},{once:!0}),new B(this)}}b=new WeakMap,v=new WeakMap,r=new WeakMap,g=new WeakMap,f=new WeakMap,L=new WeakMap,C=new WeakMap,x=new WeakMap,w=new WeakSet,R=function(){const e=k("div",{class:"toast-container",role:"alert","data-template":"#alert-layout","data-progressbar":".progress","data-elements":'{"title": ".text-1", "body": ".text-2"}',"data-closebtn":".toggle_btn-box","data-endpoint":"./templates/toaster_template.html"});document.body.append(e)},y=new WeakSet,j=function(){if(this.toasterContainer)this.toasterContainer.insertAdjacentElement("beforeend",t(this,r));else return},E=new WeakSet,N=function(){t(this,r).classList.add("active"),t(this,f).classList.add("open"),t(this,r).classList.add(t(this,v)),t(this,v)==="Success"?t(this,r).querySelector("i").classList.add("fa-check"):t(this,r).querySelector("i").classList.add("fa-info"),t(this,g).classList.add("active"),t(this,r).querySelector(".text-1").innerText=t(this,v),t(this,r).querySelector(".text-2").innerText=t(this,b)},S=new WeakSet,U=async function(e){await A(5e3),t(this,r).classList.remove("active"),t(this,r).classList.add("close"),await A(300),t(this,g).classList.remove("active"),t(this,r).remove();const s=new CustomEvent("onRemove",{detail:t(this,r),cancelable:!0,bubbles:!1});this.toasterContainer.dispatchEvent(s),this.toasterContainer.childNodes.length<=0&&this.toasterContainer.remove()},T=new WeakSet,W=async function(e){e.preventDefault(),t(this,r).classList.remove("active"),t(this,r).classList.add("close"),await A(300),t(this,g).classList.remove("active"),t(this,r).addEventListener("animationend",()=>{t(this,r).remove(),this.toasterContainer.remove()},{once:!0})};function l(i,e=null,s=null){return s==null||s.delete(e),window.history.replaceState({},document.title,i)}let a,u,o=!1;const X=window.location,n=new URLSearchParams(X.search),z=n.get("error");z==="invalid-input"&&(o=!0,a="Veuillez modifier votre identifiant",l("index.php","error",n));z==="no-update_id"&&(o=!0,a="La recette à mettre à jour n'existe pas",l("index.php","error",n));z==="no-delete-id"&&(o=!0,a="La recette que vous tentez de supprimer n'existe pas",l("index.php","error",n));const M=n.get("delete");M==="success"&&(o=!0,u="Success",a="La recette a été supprimée avec succès",l("index.php","delete",n));M==="error"&&(o=!0,a="Une erreur s'est produite lors de la suppression de la recette",l("index.php","delete",n));const q=n.get("success");q==="disconnected"&&(o=!0,u="Success",a="Vous avez été déconnecté avec succès",l("index.php","success",n));q==="recipe-shared"&&(o=!0,u="Success",a="Votre recette vient d'être partagée, elle semble excellente !",l("index.php","success",n));q==="recipe-updated"&&(o=!0,u="Success",a="Votre recette a bien été mise à jour!",l("index.php","success",n));q==="recipe-unchanged"&&(o=!0,u="Success",a="Aucune modification apportée à votre recette",l("index.php","success",n));const Y=n.get("reset");Y==="success"&&(o=!0,u="Success",a="Un email a été envoyé à l'adresse que vous avez saisie",l("index.php","reset",n));const J=n.get("login");J==="success"&&(o=!0,u="Success",a="Vous êtes connecté avec succès",l("index.php","login",n));const P=n.get("register");P==="success"&&(o=!0,u="Success",a="Votre compte a été enregistré avec succès, vous pouvez maintenant vous connecter",l("index.php","register",n));P==="failed"&&(o=!0,u="Erreur",a="Problème dans la création de votre compte, veuillez vérifier que tous les champs soient correctement remplis",l("register.php","failed",n));const _=n.get("failed");_==="recipe-creation"&&(o=!0,u="Erreur",a="Veuillez vous enregistrer ou vous identifier avant de pouvoir partager une recette",l("register.php","failed",n));_==="update-recipe"&&(o=!0,u="Erreur",a="Cette recette ne vous appartient pas, vous ne pouvez la modifier",l("register.php","failed",n));o&&new O(a,u);