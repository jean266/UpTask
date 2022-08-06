!function(){!async function(){try{const t="/api/tareas?id="+i(),a=await fetch(t),n=await a.json();e=n.tareas,o()}catch(e){console.log(e)}}();let e=[],t=[];document.querySelector("#agregar_tarea").addEventListener("click",(function(){r()}));function a(a){const n=a.target.value;t=""!==n?e.filter(e=>e.estado===n):[],o()}document.querySelectorAll('#filtros input[type="radio"]').forEach(e=>{e.addEventListener("input",a)});const n={0:"Pendiente",1:"Completa"};function o(){!function(){const e=document.querySelector("#listado_tareas");for(;e.firstChild;)e.removeChild(e.firstChild)}(),function(){const t=e.filter(e=>"0"===e.estado),a=document.querySelector("#pendientes");0===t.length?a.disabled=!0:a.disabled=!1}(),function(){const t=e.filter(e=>"1"===e.estado),a=document.querySelector("#completadas");0===t.length?a.disabled=!0:a.disabled=!1}();const a=t.length?t:e;if(0===a.length){const e=document.querySelector("#listado_tareas"),t=document.createElement("LI");return t.textContent="No hay Tareas",t.classList.add("no_tareas"),void e.appendChild(t)}a.forEach(t=>{const a=document.createElement("LI");a.dataset.tareaId=t.id,a.classList.add("tarea");const c=document.createElement("P");c.textContent=t.nombre,c.ondblclick=function(){r(!0,{...t})};const s=document.createElement("DIV");s.classList.add("opciones");const l=document.createElement("BUTTON");l.classList.add("estado_tarea"),l.classList.add(""+n[t.estado].toLowerCase()),l.textContent=n[t.estado],l.dataset.btnEstadoTarea=t.estado,l.ondblclick=function(){!function(e){const t="1"===e.estado?"0":"1";e.estado=t,d(e)}({...t})};const u=document.createElement("BUTTON");u.classList.add("eliminar_tarea"),u.dataset.idTarea=t.id,u.textContent="Eliminar",u.ondblclick=function(){!function(t){Swal.fire({title:"¿Eliminar esta tarea?",showCancelButton:!0,confirmButtonText:"Si",cancelButtonText:"No"}).then(a=>{a.isConfirmed&&async function(t){const{id:a,nombre:n,estado:r}=t,c=new FormData;c.append("id",a),c.append("nombre",n),c.append("estado",r),c.append("proyectoId",i());try{const a="http://127.0.0.1:3000/api/tarea/eliminar",n=await fetch(a,{method:"POST",body:c}),r=await n.json();"exito"===r.tipo&&(Swal.fire("Eliminado!",r.mensaje,"success"),e=e.filter(e=>e.id!==t.id),o())}catch(e){console.log(e)}}(t)})}({...t})},s.appendChild(l),s.appendChild(u),a.appendChild(c),a.appendChild(s),document.querySelector("#listado_tareas").appendChild(a)})}function r(t=!1,a={}){const n=document.createElement("DIV");n.classList.add("modal"),n.innerHTML=`\n            <form class="formulario nueva_tarea">\n                <legend>${t?"Editar tarea":"Añade una nueva tarea"}</legend>\n                <div class="campo">\n                    <label for="tarea">Tarea:</label>\n                    <input \n                        type="text"\n                        name="tarea"\n                        placeholder="${t?"Edita la tarea":"Añadir Tarea al Proyecto Actual"}"\n                        id="tarea"\n                        value="${a.nombre?a.nombre:""}"\n                    /> \n                </div>\n                <div class="opciones">\n                    <input \n                        type="submit" \n                        value="${t?"Guardar Cambios":"Añadir Tarea"}" \n                        class="submit_nueva_tarea" \n                        />\n                    <button \n                        type="button" \n                        class="cerrar_modal"\n                    >Cancelar</button>\n                </div>\n            </form>\n        `,setTimeout(()=>{document.querySelector(".formulario").classList.add("animar")},0),n.addEventListener("click",r=>{if(r.preventDefault(),r.target.classList.contains("cerrar_modal")){document.querySelector(".formulario").classList.add("cerrar"),setTimeout(()=>{n.remove()},500)}if(r.target.classList.contains("submit_nueva_tarea")){const n=document.querySelector("#tarea").value.trim();if(""===n)return void c("La tarea no tiene nombre","error",document.querySelector(".formulario legend"));t?(a.nombre=n,d(a)):async function(t){const a=new FormData;a.append("nombre",t),a.append("proyectoId",i());try{const n="http://127.0.0.1:3000/api/tarea",r=await fetch(n,{method:"POST",body:a}),d=await r.json();if(c(d.mensaje,d.tipo,document.querySelector(".formulario legend")),"exito"===d.tipo){const a=document.querySelector(".modal");setTimeout(()=>{a.remove()},1e3);const n={id:String(d.id),nombre:t,estado:"0",proyectoId:d.proyectoId};e=[...e,n],o()}}catch(e){console.log(e)}}(n)}}),document.querySelector(".dashboard").appendChild(n)}function c(e,t,a){const n=document.querySelector(".alerta");n&&n.remove();const o=document.createElement("DIV");o.classList.add("alerta",t),o.textContent=e,a.parentElement.insertBefore(o,a.nextElementSibling),setTimeout(()=>{o.remove()},5e3)}async function d(t){const{estado:a,id:n,nombre:r}=t,c=new FormData;c.append("id",n),c.append("nombre",r),c.append("estado",a),c.append("proyectoId",i());try{const t="http://127.0.0.1:3000/api/tarea/actualizar",d=await fetch(t,{method:"POST",body:c}),i=await d.json();if("exito"===i.tipo){Swal.fire(i.mensaje,i.mensaje,"success");const t=document.querySelector(".modal");t&&t.remove(),e=e.map(e=>(e.id===n&&(e.estado=a,e.nombre=r),e)),o()}}catch(e){console.log(e)}}function i(){const e=new URLSearchParams(window.location.search);return Object.fromEntries(e.entries()).id}}();