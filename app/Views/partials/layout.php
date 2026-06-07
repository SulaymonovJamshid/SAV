<!DOCTYPE html>
<html lang="<?= lang() ?>" data-theme="<?= e($_SESSION['auth']['theme'] ?? $_SESSION['theme'] ?? 'light') ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= e($title ?? t('app_name')) ?> — SmartAvtoServis</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="stylesheet" href="<?= asset('css/app.css') ?>">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
</head>
<body>
<nav class="nav">
 <div class="nav-in">
  <a href="<?= APP_URL ?>/services" class="brand">
   <div class="brand-mark">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
   </div>
   Smart<em>Avto</em>
  </a>

  <?php if(logged_in()): $u=user(); ?>
  <div class="nav-links">
   <a href="<?= APP_URL ?>/services" class="<?= str_contains($_SERVER['REQUEST_URI'],'/services')?'on':'' ?>">Servislar</a>
   <?php if($u['role']==='service'): ?>
   <a href="<?= APP_URL ?>/dashboard" class="<?= str_contains($_SERVER['REQUEST_URI'],'/dashboard')?'on':'' ?>">Dashboard</a>
   <?php endif; ?>
   <?php if($u['role']==='admin'): ?>
   <a href="<?= APP_URL ?>/admin" class="<?= str_contains($_SERVER['REQUEST_URI'],'/admin')?'on':'' ?>">Admin</a>
   <?php endif; ?>
  </div>
  <?php endif; ?>

  <div class="nav-r">
   <div class="langs">
    <?php foreach(['uz','ru','en'] as $lg): ?>
    <a href="?lang=<?= $lg ?>" class="lang-btn <?= lang()===$lg?'on':'' ?>"><?= strtoupper($lg) ?></a>
    <?php endforeach; ?>
   </div>
   <button class="th-tog" onclick="Theme.toggle()"></button>

   <?php if(logged_in()): $u=user(); ?>
   <div class="dd">
    <button class="av-btn" onclick="toggleDd()">
     <?php if($u['avatar']): ?>
      <img src="<?= asset('uploads/avatars/'.e($u['avatar'])) ?>" class="av" alt="" style="width:24px;height:24px;border-radius:50%;object-fit:cover">
     <?php else: ?>
      <span class="av"><?= strtoupper(mb_substr($u['first_name'],0,1)) ?></span>
     <?php endif; ?>
     <span class="av-btn-name"><?= e($u['first_name']) ?></span>
     <svg width="9" height="9" viewBox="0 0 9 9" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M1.5 3l3 3 3-3"/></svg>
    </button>
    <div class="dd-menu" id="ddMenu" style="display:none">
     <a href="<?= APP_URL ?>/profile">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
      <?= t('profile') ?>
     </a>
     <?php if($u['role']==='service'): ?>
     <a href="<?= APP_URL ?>/dashboard">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
      <?= t('dashboard') ?>
     </a>
     <?php endif; ?>
     <?php if($u['role']==='admin'): ?>
     <a href="<?= APP_URL ?>/admin">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
      Admin
     </a>
     <?php endif; ?>
     <div class="sep"></div>
     <a href="<?= APP_URL ?>/logout" class="red">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>
      <?= t('logout') ?>
     </a>
    </div>
   </div>
   <?php else: ?>
   <a href="<?= APP_URL ?>/login"    class="btn btn-ghost btn-sm"><?= t('login') ?></a>
   <a href="<?= APP_URL ?>/register" class="btn btn-pr btn-sm"><?= t('register') ?></a>
   <?php endif; ?>
  </div>
 </div>
</nav>

<?php foreach(get_flash() as $fl): ?>
<div style="max-width:1300px;margin:.6rem auto;padding:0 24px">
 <div class="alert a-<?= $fl['type']==='success'?'ok':($fl['type']==='error'?'err':'info') ?>" data-ad>
  <?php if($fl['type']==='success'): ?>
  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
  <?php else: ?>
  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
  <?php endif; ?>
  <?= e($fl['msg']) ?>
 </div>
</div>
<?php endforeach; ?>

<?= $pageContent ?? '' ?>

<footer style="background:var(--card);border-top:1px solid var(--bdr);padding:20px 0;margin-top:48px">
 <div style="max-width:1300px;margin:0 auto;padding:0 24px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
  <a href="<?= APP_URL ?>/services" class="brand" style="font-size:.97rem">
   <div class="brand-mark" style="width:24px;height:24px;border-radius:5px">
    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
   </div>
   Smart<em>Avto</em>Servis
  </a>
  <p style="font-size:.74rem;color:var(--muted)">© <?= date('Y') ?> SmartAvtoServis</p>
 </div>
</footer>

<div id="toasts"></div>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="<?= asset('js/app.js') ?>"></script>
<?= $extraJs ?? '' ?>
</body>
</html>
