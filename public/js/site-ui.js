(function(){
  const THEME_KEY='dc_theme';
  const body=document.body;
  function apply(theme){
    if(theme==='dark'){ body.classList.add('theme-dark'); }
    else { body.classList.remove('theme-dark'); }
  }
  apply(localStorage.getItem(THEME_KEY)||'light');

  const btn=document.getElementById('siteThemeToggle');
  if(btn){
    btn.addEventListener('click',()=>{
      const next=body.classList.contains('theme-dark')?'light':'dark';
      localStorage.setItem(THEME_KEY,next);
      apply(next);
    });
  }
})();
