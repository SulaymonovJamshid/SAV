<?php ob_start(); require VIEWS.'/admin/_shared.php'; ?>
<div class="dash">
 <?= adminSidebar('reviews') ?>
 <main class="main">
  <div class="sh mb3">
   <h1 style="font-weight:700;font-size:1.15rem;letter-spacing:-.02em">Sharhlar <span style="font-size:.8rem;font-weight:400;color:var(--muted)">(<?= $pag['total'] ?>)</span></h1>
  </div>
  <div class="card">
   <div class="tbl-wrap">
    <table>
     <thead><tr><th>Foydalanuvchi</th><th>Servis</th><th>Reyting</th><th>Sharh</th><th>Sana</th><th></th></tr></thead>
     <tbody>
      <?php foreach($reviews as $rv): ?>
      <tr>
       <td style="font-weight:600;font-size:.875rem"><?= e($rv['first_name'].' '.$rv['last_name']) ?></td>
       <td><a href="<?= APP_URL ?>/services/<?= (int)$rv['service_id'] ?>" style="color:var(--pr);font-size:.84rem"><?= e($rv['svc_name']) ?></a></td>
       <td><span class="stars" style="font-size:.8rem"><?php for($i=1;$i<=5;$i++) echo '<span class="'.($i<=(int)$rv['rating']?'s-on':'s-off').'">★</span>'; ?></span></td>
       <td style="font-size:.82rem;color:var(--muted);max-width:200px"><?= e(mb_substr($rv['comment']??'—',0,80)) ?></td>
       <td style="font-size:.78rem;color:var(--muted)"><?= date('d.m.Y',strtotime($rv['created_at'])) ?></td>
       <td>
        <form method="POST" action="<?= APP_URL ?>/admin/reviews/delete" onsubmit="return confirm('O\'chirishni tasdiqlaysizmi?')">
         <?= csrf_field() ?><input type="hidden" name="rid" value="<?= (int)$rv['id'] ?>">
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
