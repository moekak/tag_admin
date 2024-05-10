(()=>{"use strict";document.getElementById("js_copy_site"),document.getElementById("js_copy_tag"),document.getElementById("tag_reference_id");const e=(e,t)=>{const d=`editor${t}`;var a=ace.edit(d);a.$blockScrolling=1/0,a.setOptions({enableBasicAutocompletion:!1,enableSnippets:!1,enableLiveAutocompletion:!1}),a.setTheme("ace/theme/monokai"),a.getSession().setMode("ace/mode/html"),a.getSession().on("change",(function(){var t=a.getValue();l(e,t),o()}))};document.getElementById("js_domain_add_modal"),document.getElementById("js_info_modal");const t=document.querySelector(".bg-gray");document.querySelector(".bg-gray2"),document.querySelector(".admin_modal_container");let d={trigger:"",ad_code:"",ad_range:"",ad_num:"",tag_head:"",tag_body:"",applygingAll:""};const a=(e,t)=>{d[e]=t,s()},l=(e,t)=>{/^[\s]*$/.test(t)?d[e]="":d[e]=t,s()},n=e=>{d[e]=""},o=()=>{const e=document.getElementById("tag_head"),t=document.getElementById("tag_body");/^[\s]*$/.test(d.tag_head)?e.value="":e.value=d.tag_head,/^[\s]*$/.test(d.tag_body)?t.value="":t.value=d.tag_body},r=()=>{const e=document.getElementById("js_type");""===d.ad_code&&""===d.ad_num||"1"!==d.ad_range||(e.value="withCode"),""!==d.ad_num&&""!==d.ad_range&&"1"!==d.ad_range&&(e.value="withCodeRange"),""==d.ad_code&&""==d.ad_num&&""==d.ad_range&&(e.value="withoutCode")},c=()=>{document.querySelector(".tag_form").classList.remove("activeTrigger")},s=()=>{const e=document.getElementById("js_tagCreate_btn");(()=>{let e=y();return"checked"===d.applygingAll?e.trigger_all:e.trigger_exceptAllWithAdRange&&e.trigger_exceptAllWithAdRAnge_dataCheck})()?e.classList.remove("disabled_btn"):e.classList.add("disabled_btn")},u=e=>{e.parentNode.querySelector(".alert_txt").classList.remove("hidden"),e.parentNode.querySelector(".label").classList.add("red"),e.classList.add("alert_active")},i=e=>{e.parentNode.querySelector(".alert_txt").classList.add("hidden"),e.parentNode.querySelector(".label").classList.remove("red"),e.classList.remove("alert_active")},g=e=>/^[0-9]+$/.test(e),_=e=>/^[A-Za-z\s!-/:-@\[-`{-~]+$/.test(e),m=(e,t,d)=>e>=t&&e<=d,y=()=>({trigger_all:Boolean(d.trigger&&(d.tag_head||d.tag_body)),trigger_exceptAllWithAdRange:Boolean(d.trigger&&d.ad_range&&(1==d.ad_range||d.ad_num)&&(d.tag_head||d.tag_body)),trigger_exceptAllWithAdRAnge_dataCheck:Boolean((""==d.ad_num||g(d.ad_num))&&g(d.ad_range)&&m(d.ad_range,1,100)&&(""==d.ad_code||_(d.ad_code)))});{const e=document.querySelector(".dummy_down"),t=document.querySelector(".dummy_up");e&&e.addEventListener("click",(()=>{document.querySelector(".tag_form").classList.add("activeTrigger"),e.classList.add("hidden"),t.classList.remove("hidden")})),t&&t.addEventListener("click",(()=>{c(),e.classList.remove("hidden"),t.classList.add("hidden")}))}document.querySelectorAll(".js_trigger_btns").forEach((e=>{e.addEventListener("click",(e=>{document.querySelector(".dummy_down").classList.remove("hidden"),document.querySelector(".dummy_up").classList.add("hidden");let t=e.currentTarget.getAttribute("data-type");(e=>{const t=document.querySelector(".js_trigger");document.querySelector(".trigger_msg").querySelector(".label").innerHTML=e,t.value=e})(t),a("trigger",t),c()}))}));{const e=document.querySelector(".js_ad_field"),t=document.querySelector(".js_adNum_field"),d=document.querySelector(".js_adRange_field");null!==e&&e.addEventListener("input",(()=>{_(e.value)?i(e):""==e.value?(n("ad_code"),i(e)):(u(e),n("ad_code")),a("ad_code",e.value),r()})),null!==t&&t.addEventListener("input",(()=>{g(t.value)?i(t):""==t.value?(n("ad_num"),i(t)):u(t),a("ad_num",t.value),r()})),null!==d&&d.addEventListener("input",(()=>{g(d.value)&&m(d.value,1,100)?i(d):""==d.value?(n("ad_range"),i(d)):u(d),a("ad_range",d.value),r()}));const l=document.getElementById("apply_all");null!==l&&l.addEventListener("change",(()=>{l.checked?(document.getElementById("js_type").value="withoutCode",a("applygingAll","checked"),(()=>{const e=document.querySelectorAll(".js_data_field");document.querySelector(".js_adCode").querySelectorAll(".domain_name_input").forEach((e=>{e.value=""})),e.forEach((e=>{e.style.background="#80808026",e.style.cursor="not-allowed",e.setAttribute("readonly",!0),i(e)}))})()):(a("applygingAll","unchecked"),document.querySelectorAll(".js_data_field").forEach((e=>{e.style.background="transparent",e.style.cursor="text",e.removeAttribute("readonly")})))}))}{const e=document.querySelectorAll(".js_close_btns2"),d=document.querySelectorAll(".js_modals");e.forEach((e=>{e.addEventListener("click",(()=>{d.forEach((e=>{(e=>{e.classList.add("hidden"),t.classList.add("hidden")})(e)}))}))}))}e("tag_head",1),e("tag_body",2)})();