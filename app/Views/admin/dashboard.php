<?php ob_start(); require VIEWS.'/admin/_shared.php'; ?>
<div class="dash">
 <?= adminSidebar('dashboard') ?>
 <main class="main">
  <h1 style="font-weight:700;font-size:1.2rem;letter-spacing:-.02em;margin-bottom:18px">Admin Dashboard</h1>

  <div class="stats mb4">
   <?php foreach([
    [$stats['users'],  'Foydalanuvchilar','ic-b'],
    [$stats['services'],'Barcha servislar','ic-r'],
    [$stats['reviews'], 'Sharhlar','ic-g'],
    [$stats['pending'], 'Tasdiq kutmoqda','ic-o'],
   ] as [$v,$l,$c]): ?>
   <div class="stat">
    <div class="stat-ico <?= $c ?>"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/></svg></div>
    <div class="stat-v"><?= number_format((int)$v) ?></div>
    <div class="stat-l"><?= $l ?></div>
   </div>
   <?php endforeach; ?>
  </div>

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
   <div class="card">
    <div class="card-head">So'nggi servislar <a href="<?= APP_URL ?>/admin/services" style="font-size:.8rem;font-weight:400;color:var(--muted)">Hammasi →</a></div>
    <div class="tbl-wrap">
     <table>
      <thead><tr><th>Nomi</th><th>Egasi</th><th>Holat</th></tr></thead>
      <tbody>
       <?php foreach($recentSvcs as $s): ?>
       <tr>
        <td><a href="<?= APP_URL ?>/services/<?= (int)$s['id'] ?>" style="color:var(--pr);font-weight:500"><?= e($s['name']) ?></a></td>
        <td style="color:var(--muted)"><?= e($s['owner_first'].' '.$s['owner_last']) ?></td>
        <td><?= $s['is_approved']?'<span class="badge b-ok">Faol</span>':'<span class="badge b-warn">Kutmoqda</span>' ?></td>
       </tr>
       <?php endforeach; ?>
      </tbody>
     </table>
    </div>
   </div>
   <div class="card">
    <div class="card-head">Tezkor amallar</div>
    <div class="card-body" style="display:flex;flex-direction:column;gap:8px">
     <a href="<?= APP_URL ?>/admin/services" class="btn btn-ghost btn-bl">Servislarni boshqarish</a>
     <a href="<?= APP_URL ?>/admin/users" class="btn btn-ghost btn-bl">Foydalanuvchilar</a>
     <a href="<?= APP_URL ?>/admin/reviews" class="btn btn-ghost btn-bl">Sharhlarni ko'rish</a>
    </div>
   </div>
  </div>
 </main>
</div>
