<?php ob_start(); require VIEWS.'/admin/_shared.php'; ?>
<div class="dash">
 <?= adminSidebar('users') ?>
 <main class="main">
  <div class="sh mb3">
   <h1 style="font-weight:700;font-size:1.15rem;letter-spacing:-.02em">Foydalanuvchilar <span style="font-size:.8rem;font-weight:400;color:var(--muted)">(<?= $pag['total'] ?>)</span></h1>
  </div>
  <form method="GET" style="display:flex;gap:8px;margin-bottom:14px">
   <input type="text" name="q" class="inp" style="max-width:260px" placeholder="Ism, email, telefon..." value="<?= e($q) ?>">
   <button type="submit" class="btn btn-pr btn-sm">Qidirish</button>
   <?php if($q): ?><a href="<?= APP_URL ?>/admin/users" class="btn btn-ghost btn-sm">Tozalash</a><?php endif; ?>
  </form>
  <div class="card">
   <div class="tbl-wrap">
    <table>
     <thead><tr><th>#</th><th>Ism Familiya</th><th>Aloqa</th><th>Rol</th><th>Holat</th><th>Sana</th><th></th></tr></thead>
     <tbody>
      <?php foreach($users as $u): ?>
      <tr>
       <td style="color:var(--muted);font-size:.78rem"><?= (int)$u['id'] ?></td>
       <td style="font-weight:600"><?= e($u['first_name'].' '.$u['last_name']) ?></td>
       <td style="font-size:.84rem;color:var(--muted)"><?= e($u['email']??$u['phone']??'—') ?></td>
       <td><?= match($u['role']){'admin'=>'<span class="badge b-info">Admin</span>','service'=>'<span class="badge b-warn">Servis</span>',default=>'<span class="badge b-muted">User</span>'} ?></td>
       <td><?= $u['is_active']?'<span class="badge b-ok">Faol</span>':'<span class="badge" style="background:rgba(192,57,43,.08);color:var(--pr)">Bloklangan</span>' ?></td>
       <td style="font-size:.78rem;color:var(--muted)"><?= date('d.m.Y',strtotime($u['created_at'])) ?></td>
       <td>
        <form method="POST" action="<?= APP_URL ?>/admin/users/toggle" style="display:inline">
         <?= csrf_field() ?><input type="hidden" name="uid" value="<?= (int)$u['id'] ?>">
         <button type="submit" class="btn btn-ghost btn-sm" style="font-size:.76rem"><?= $u['is_active']?'Bloklash':'Ochish' ?></button>
        </form>
       </td>
      </tr>
      <?php endforeach; ?>
     </tbody>
    </table>
   </div>
  </div>
  <?php if($pag['pages']>1): ?>
  <div class="pag">
   <?php if($pag['page']>1): ?><a href="?q=<?= e($q) ?>&page=<?= $pag['page']-1 ?>">←</a><?php endif; ?>
   <?php for($p=max(1,$pag['page']-2);$p<=min($pag['pages'],$pag['page']+2);$p++): ?>
    <?= $p===$pag['page']?"<span class=\"on\">$p</span>":"<a href=\"?q=".e($q)."&page=$p\">$p</a>" ?>
   <?php endfor; ?>
   <?php if($pag['page']<$pag['pages']): ?><a href="?q=<?= e($q) ?>&page=<?= $pag['page']+1 ?>">→</a><?php endif; ?>
  </div>
  <?php endif; ?>
 </main>
</div>
