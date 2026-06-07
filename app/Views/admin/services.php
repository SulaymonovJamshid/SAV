<?php ob_start(); require VIEWS.'/admin/_shared.php'; ?>
<div class="dash">
 <?= adminSidebar('services') ?>
 <main class="main">
  <div class="sh mb3">
   <h1 style="font-weight:700;font-size:1.15rem;letter-spacing:-.02em">Servislar <span style="font-size:.8rem;font-weight:400;color:var(--muted)">(<?= $pag['total'] ?>)</span></h1>
  </div>
  <div class="card">
   <div class="tbl-wrap">
    <table>
     <thead><tr><th>#</th><th>Nomi</th><th>Egasi</th><th>Viloyat</th><th>Reyting</th><th>Holat</th><th>Sana</th><th></th></tr></thead>
     <tbody>
      <?php foreach($svcs as $s): ?>
      <tr>
       <td style="color:var(--muted);font-size:.78rem"><?= (int)$s['id'] ?></td>
       <td><a href="<?= APP_URL ?>/services/<?= (int)$s['id'] ?>" style="color:var(--pr);font-weight:600"><?= e($s['name']) ?></a></td>
       <td style="font-size:.84rem;color:var(--muted)"><?= e($s['owner_first'].' '.$s['owner_last']) ?></td>
       <td style="font-size:.82rem"><?= e(viloyatlar()[$s['viloyat']]??$s['viloyat']) ?></td>
       <td>
        <span class="stars" style="font-size:.75rem"><?php $a=round((float)$s['avg_rating']); for($i=1;$i<=5;$i++) echo '<span class="'.($i<=$a?'s-on':'s-off').'">★</span>'; ?></span>
        <span style="font-size:.75rem;color:var(--muted)"> <?= round((float)$s['avg_rating'],1) ?></span>
       </td>
       <td><?= $s['is_approved']?'<span class="badge b-ok">Tasdiqlangan</span>':'<span class="badge b-warn">Kutmoqda</span>' ?></td>
       <td style="font-size:.78rem;color:var(--muted)"><?= date('d.m.Y',strtotime($s['created_at'])) ?></td>
       <td style="display:flex;gap:5px;flex-wrap:wrap">
        <form method="POST" action="<?= APP_URL ?>/admin/services/approve" style="display:inline">
         <?= csrf_field() ?><input type="hidden" name="sid" value="<?= (int)$s['id'] ?>"><input type="hidden" name="val" value="<?= $s['is_approved']?0:1 ?>">
         <button type="submit" class="btn btn-sm <?= $s['is_approved']?'btn-ghost':'btn-pr' ?>" style="font-size:.75rem"><?= $s['is_approved']?'Bekor':'Tasdiqlash' ?></button>
        </form>
        <form method="POST" action="<?= APP_URL ?>/admin/services/delete" onsubmit="return confirm('O\'chirishni tasdiqlaysizmi?')">
         <?= csrf_field() ?><input type="hidden" name="sid" value="<?= (int)$s['id'] ?>">
         <button type="submit" class="btn btn-danger btn-sm" style="font-size:.75rem">O'chirish</button>
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
   <?php if($pag['page']>1): ?><a href="?page=<?= $pag['page']-1 ?>">←</a><?php endif; ?>
   <?php for($p=max(1,$pag['page']-2);$p<=min($pag['pages'],$pag['page']+2);$p++): ?>
    <?= $p===$pag['page']?"<span class=\"on\">$p</span>":"<a href=\"?page=$p\">$p</a>" ?>
   <?php endfor; ?>
   <?php if($pag['page']<$pag['pages']): ?><a href="?page=<?= $pag['page']+1 ?>">→</a><?php endif; ?>
  </div>
  <?php endif; ?>
 </main>
</div>
