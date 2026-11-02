{{-- Global request loader: shows on form submits (incl. file uploads) and internal navigation --}}
<div id="app-loader" aria-hidden="true"><div class="al-spinner"></div><div class="al-text">Please wait…</div></div>
<style>
#app-loader{position:fixed;inset:0;z-index:11000;display:none;flex-direction:column;align-items:center;justify-content:center;gap:14px;background:rgba(255,255,255,.7);}
#app-loader.show{display:flex;}
#app-loader .al-spinner{width:48px;height:48px;border:4px solid rgba(26,60,110,.2);border-top-color:#0000FF;border-radius:50%;animation:al-spin .8s linear infinite;}
#app-loader .al-text{color:#1a3c6e;font-weight:600;font-size:14px;letter-spacing:.02em;}
@keyframes al-spin{to{transform:rotate(360deg)}}
</style>
<script>
(function(){
  var loader=document.getElementById('app-loader');
  if(!loader) return;
  function show(){ loader.classList.add('show'); }
  document.addEventListener('submit',function(e){
    var f=e.target;
    if(!f || f.tagName!=='FORM') return;
    if(f.getAttribute('data-submitting')==='1'){ e.preventDefault(); return; } // block a 2nd submit
    f.setAttribute('data-submitting','1');
    show();
    // Disable submit buttons just after the event so the clicked button's value is still sent
    setTimeout(function(){
      f.querySelectorAll('button[type=submit],button:not([type]),input[type=submit]').forEach(function(b){ b.disabled=true; });
    },0);
  },true);
  document.addEventListener('click',function(e){
    var a=e.target.closest ? e.target.closest('a') : null;
    if(!a) return;
    var href=a.getAttribute('href')||'';
    if(a.target==='_blank'||a.hasAttribute('download')) return;
    if(href===''||href.charAt(0)==='#'||href.indexOf('javascript:')===0||href.indexOf('mailto:')===0||href.indexOf('tel:')===0) return;
    try{ var u=new URL(a.href,window.location.href); if(u.origin!==window.location.origin) return;
      if(u.pathname===window.location.pathname && u.search===window.location.search && u.hash) return;
    }catch(_){ return; }
    show();
  },true);
  window.addEventListener('pageshow',function(){ loader.classList.remove('show'); });
})();
</script>
