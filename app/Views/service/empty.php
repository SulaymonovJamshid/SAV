<?php ob_start(); ?>
<div class="dash">
 <aside class="sidebar">
  <a href="<?= APP_URL ?>/dashboard"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg> Dashboard</a>
  <a href="<?= APP_URL ?>/dashboard/edit" style="color:var(--pr)"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg> Servis yaratish</a>
 </aside>
 <main class="main">
  <div class="empty" style="background:var(--card);border:1px solid var(--bdr);border-radius:var(--r);max-width:400px;margin:40px auto">
   <div class="empty-ico">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
   </div>
   <h3>Servis hali yaratilmagan</h3>
   <p style="font-size:.84rem;margin-bottom:16px">Servis ma'lumotlaringizni kiriting va foydalanuvchilarga ko'rining</p>
   <a href="<?= APP_URL ?>/dashboard/edit" class="btn btn-pr">Servis yaratish</a>
  </div>
 </main>
</div>
