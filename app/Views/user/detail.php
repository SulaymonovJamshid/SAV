<?php ob_start();
function renderStars(float $avg): string {
    $r=round($avg); $h='<span class="stars">';
    for($i=1;$i<=5;$i++) $h.='<span class="'.($i<=$r?'s-on':'s-off').'">★</span>';
    return $h.'</span>';
}
?>
<div style="max-width:1300px;margin:0 auto;padding:20px 24px">
 <div style="display:grid;grid-template-columns:1fr 290px;gap:20px;align-items:start">

  <!-- MAIN COLUMN -->
  <div>
   <!-- Back -->
   <a href="<?= APP_URL ?>/services" style="display:inline-flex;align-items:center;gap:6px;font-size:.84rem;color:var(--muted);margin-bottom:14px;transition:var(--tr)" onmouseover="this.style.color='var(--text)'" onmouseout="this.style.color='var(--muted)'">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="15 18 9 12 15 6"/></svg>
    Servislar ro'yxatiga qaytish
   </a>

   <!-- Image slider -->
   <?php $imgs=array_values($images); ?>
   <div style="margin-bottom:16px;border-radius:var(--r);overflow:hidden;border:1px solid var(--bdr)">
    <?php if($imgs): ?>
     <div class="slider" id="imgSlider">
      <?php foreach($imgs as $img): ?>
       <img src="<?= svc_img($img['filename']) ?>" class="slide" alt="<?= e($svc['name']) ?>">
      <?php endforeach; ?>
     </div>
     <?php if(count($imgs)>1): ?>
     <div class="dots" style="padding:10px 0;background:var(--card)">
      <?php foreach($imgs as $i=>$img): ?><button class="dot <?= $i===0?'on':'' ?>" onclick="goSlide(<?= $i ?>)"></button><?php endforeach; ?>
     </div>
     <?php endif; ?>
    <?php else: ?>
     <div class="slide-ph">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
     </div>
    <?php endif; ?>
   </div>

   <!-- Title card -->
   <div class="card" style="margin-bottom:12px">
    <div class="card-body">
     <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:8px">
      <div>
       <h1 style="font-weight:700;font-size:1.4rem;color:var(--text);letter-spacing:-.02em;margin-bottom:4px"><?= e($svc['name']) ?></h1>
       <p style="font-size:.84rem;color:var(--muted);display:flex;align-items:center;gap:4px">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
        <?= e(viloyatlar()[$svc['viloyat']]??$svc['viloyat']) ?><?= $svc['tuman']?', '.e($svc['tuman']):'' ?> — <?= e($svc['address']) ?>
       </p>
      </div>
      <div style="display:flex;align-items:center;gap:8px;flex-shrink:0">
       <?php if(!$svc['is_approved']): ?><span class="badge b-warn">Tasdiq kutmoqda</span><?php endif; ?>
       <?php if(user()['role']==='user'): ?>
       <button class="fav <?= $isFav?'on':'' ?>" onclick="toggleFav(this,<?= (int)$svc['id'] ?>,'<?= APP_URL ?>')" style="width:34px;height:34px;border:1px solid var(--bdr)">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
       </button>
       <?php endif; ?>
      </div>
     </div>
     <div style="display:flex;align-items:center;gap:8px">
      <?= renderStars((float)$svc['avg_rating']) ?>
      <strong style="font-size:.9rem"><?= round((float)$svc['avg_rating'],1) ?></strong>
      <span style="font-size:.8rem;color:var(--muted)">(<?= (int)$svc['review_count'] ?> sharh)</span>
     </div>

     <?php if($svc['description']): ?>
     <div class="hr"></div>
     <p style="font-size:.9rem;color:var(--text2);line-height:1.75"><?= nl2br(e($svc['description'])) ?></p>
     <?php endif; ?>

     <div class="meta">
      <div class="meta-item">
       <span class="meta-l">Ish vaqti</span>
       <span class="meta-v" style="<?= $svc['is_24h']?'color:var(--ok)':'' ?>"><?= $svc['is_24h']?'24/7 — Har doim ochiq':substr($svc['work_start'],0,5).' – '.substr($svc['work_end'],0,5) ?></span>
      </div>
      <div class="meta-item">
       <span class="meta-l">Tajriba</span>
       <span class="meta-v"><?= (int)$svc['experience_years'] ?> yil</span>
      </div>
      <div class="meta-item">
       <span class="meta-l">Narx oralig'i</span>
       <span class="meta-v text-pr"><?= ($svc['price_from']||$svc['price_to'])?number_format((int)$svc['price_from'],0,'',' ').' – '.number_format((int)$svc['price_to'],0,'',' ').' so\'m':'Kelishiladi' ?></span>
      </div>
      <div class="meta-item">
       <span class="meta-l">Ro'yxatga qo'shildi</span>
       <span class="meta-v"><?= date('M Y',strtotime($svc['created_at'])) ?></span>
      </div>
     </div>
     <?php if($svc['price_note']): ?><p style="font-size:.8rem;color:var(--muted);margin-top:8px"><?= e($svc['price_note']) ?></p><?php endif; ?>
    </div>
   </div>

   <!-- Specializations -->
   <?php if($svc['specializations']): ?>
   <div class="card" style="margin-bottom:12px">
    <div class="card-body">
     <p class="cap" style="color:var(--muted);margin-bottom:10px">Mutaxassisliklar</p>
     <div class="tags">
      <?php foreach($svc['specializations'] as $sp): ?><span class="tag on"><?= t('s_'.$sp) ?></span><?php endforeach; ?>
     </div>
    </div>
   </div>
   <?php endif; ?>

   <!-- Map -->
   <?php if($svc['latitude']&&$svc['longitude']): ?>
   <div class="card" style="margin-bottom:12px">
    <div class="card-body">
     <p class="cap" style="color:var(--muted);margin-bottom:10px">Joylashuv</p>
     <div id="map"></div>
    </div>
   </div>
   <?php endif; ?>

   <!-- Reviews -->
   <div class="card">
    <div class="card-head">
     Sharhlar
     <span style="font-size:.8rem;font-weight:400;color:var(--muted)"><?= count($reviews) ?> ta</span>
    </div>
    <div class="card-body">

     <?php if(user()['role']==='user'): ?>
     <div style="background:var(--card2);border-radius:var(--rs);padding:14px;margin-bottom:16px">
      <p style="font-weight:600;font-size:.9rem;color:var(--text);margin-bottom:10px">
       <?= $myReview?'Sharhni tahrirlash':'Sharh qoldirish' ?>
      </p>
      <form method="POST" action="<?= APP_URL ?>/reviews/store">
       <?= csrf_field() ?>
       <input type="hidden" name="service_id" value="<?= (int)$svc['id'] ?>">
       <div style="margin-bottom:10px">
        <div class="star-inp" id="starInp">
         <?php for($i=1;$i<=5;$i++): ?><span class="star <?= $myReview&&$i<=(int)$myReview['rating']?'on':'' ?>" data-v="<?= $i ?>">★</span><?php endfor; ?>
        </div>
        <input type="hidden" name="rating" id="ratingVal" value="<?= (int)($myReview['rating']??0) ?>">
       </div>
       <textarea name="comment" class="ta" rows="3" placeholder="Izoh..."><?= e($myReview['comment']??'') ?></textarea>
       <div style="display:flex;gap:8px;margin-top:8px">
        <button type="submit" class="btn btn-pr btn-sm">Saqlash</button>
        <?php if($myReview): ?>
        <form method="POST" action="<?= APP_URL ?>/reviews/delete" style="display:inline">
         <?= csrf_field() ?><input type="hidden" name="review_id" value="<?= (int)$myReview['id'] ?>">
         <button type="submit" class="btn btn-danger btn-sm">O'chirish</button>
        </form>
        <?php endif; ?>
       </div>
      </form>
     </div>
     <?php endif; ?>

     <?php foreach($reviews as $rv): ?>
     <div class="rev-card">
      <div class="rev-head">
       <div class="rev-user">
        <div class="rev-av"><?= strtoupper(mb_substr($rv['first_name'],0,1)) ?></div>
        <div>
         <div class="rev-name"><?= e($rv['first_name'].' '.$rv['last_name']) ?></div>
         <?= renderStars((int)$rv['rating']) ?>
        </div>
       </div>
       <span class="rev-date"><?= date('d.m.Y',strtotime($rv['created_at'])) ?></span>
      </div>
      <?php if($rv['comment']): ?><p style="font-size:.875rem;color:var(--text2);line-height:1.65"><?= e($rv['comment']) ?></p><?php endif; ?>
     </div>
     <?php endforeach; ?>
     <?php if(empty($reviews)): ?>
     <div class="empty" style="padding:28px">
      <div class="empty-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg></div>
      <h3>Hali sharh yo'q</h3>
      <p style="font-size:.84rem">Birinchi bo'ling!</p>
     </div>
     <?php endif; ?>
    </div>
   </div>
  </div>

  <!-- SIDEBAR -->
  <aside style="position:sticky;top:78px">
   <div class="card">
    <div class="card-head">Aloqa</div>
    <div class="card-body" style="display:flex;flex-direction:column;gap:8px">
     <?php if($svc['phone']): ?>
     <a href="tel:<?= e($svc['phone']) ?>" class="btn btn-pr btn-bl">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
      <?= e($svc['phone']) ?>
     </a>
     <?php endif; ?>
     <?php if($svc['telegram']): ?>
     <a href="https://t.me/<?= e(ltrim($svc['telegram'],'@')) ?>" target="_blank" class="btn btn-out btn-bl" style="font-size:.84rem">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
      @<?= e($svc['telegram']) ?>
     </a>
     <?php endif; ?>
     <?php if($svc['website']): ?>
     <a href="<?= e($svc['website']) ?>" target="_blank" class="btn btn-ghost btn-bl" style="font-size:.84rem">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
      Veb-sayt
     </a>
     <?php endif; ?>
    </div>
   </div>

   <div class="card mt3">
    <div class="card-body" style="display:flex;flex-direction:column;gap:9px;font-size:.84rem">
     <?php foreach([
       ['Viloyat', e(viloyatlar()[$svc['viloyat']]??$svc['viloyat'])],
       $svc['tuman']?['Tuman', e($svc['tuman'])]:null,
       ['Ish vaqti', $svc['is_24h']?'<span style="color:var(--ok);font-weight:600">24/7</span>':substr($svc['work_start'],0,5).' – '.substr($svc['work_end'],0,5)],
       ['Tajriba', (int)$svc['experience_years'].' yil'],
     ] as $row): if(!$row) continue; [$lbl,$val]=$row; ?>
     <div style="display:flex;justify-content:space-between;padding-bottom:8px;border-bottom:1px solid var(--bdrl)">
      <span style="color:var(--muted)"><?= $lbl ?></span>
      <strong style="color:var(--text)"><?= $val ?></strong>
     </div>
     <?php endforeach; ?>
     <div style="display:flex;justify-content:space-between">
      <span style="color:var(--muted)">Holat</span>
      <?= $svc['is_approved']?'<span class="badge b-ok">Tasdiqlangan</span>':'<span class="badge b-warn">Kutilmoqda</span>' ?>
     </div>
    </div>
   </div>
  </aside>
 </div>
</div>

<?php
$extraJs='';
if($svc['latitude']&&$svc['longitude']){
 $extraJs='<script>document.addEventListener("DOMContentLoaded",()=>initDetailMap('.e($svc['latitude']).','.e($svc['longitude']).',"'.addslashes(e($svc['name'])).'"));</script>';
}
$pageContent=ob_get_clean();
?>
