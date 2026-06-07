<?php ob_start();
$svgIcons=[
 'grid'   =>'<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>',
 'edit'   =>'<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>',
 'eye'    =>'<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>',
 'user'   =>'<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>',
 'logout' =>'<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>',
 'star'   =>'<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>',
 'chat'   =>'<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>',
 'image'  =>'<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>',
 'heart'  =>'<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>',
 'trash'  =>'<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>',
 'plus'   =>'<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>',
 'pin'    =>'<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>',
 'phone'  =>'<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>',
];
function si(array $icons, string $k): string { return $icons[$k]??''; }
?>
<div class="dash">
 <aside class="sidebar">
  <div style="padding:4px 4px 14px">
   <div style="padding:8px 10px;border-radius:var(--rs);background:var(--card2)">
    <div style="font-weight:600;font-size:.875rem;color:var(--text)"><?= e(user()['first_name'].' '.user()['last_name']) ?></div>
    <div style="font-size:.72rem;color:var(--muted);margin-top:1px">Servis egasi</div>
   </div>
  </div>
  <div class="sb-label">Asosiy</div>
  <a href="<?= APP_URL ?>/dashboard"      class="on"><?= si($svgIcons,'grid') ?> Dashboard</a>
  <a href="<?= APP_URL ?>/dashboard/edit"><?= si($svgIcons,'edit') ?> Tahrirlash</a>
  <a href="<?= APP_URL ?>/services/<?= (int)$svc['id'] ?>" target="_blank"><?= si($svgIcons,'eye') ?> Sahifani ko'rish</a>
  <div class="sb-label">Hisob</div>
  <a href="<?= APP_URL ?>/profile"><?= si($svgIcons,'user') ?> Profil</a>
  <a href="<?= APP_URL ?>/logout" style="color:var(--pr)!important"><?= si($svgIcons,'logout') ?> Chiqish</a>
 </aside>

 <main class="main">
  <div class="sh mb4">
   <div>
    <h1 style="font-weight:700;font-size:1.3rem;letter-spacing:-.02em">Dashboard</h1>
    <p style="font-size:.82rem;color:var(--muted);margin-top:2px"><?= e($svc['name']) ?></p>
   </div>
   <div style="display:flex;align-items:center;gap:8px">
    <?= $svc['is_approved']?'<span class="badge b-ok">Tasdiqlangan</span>':'<span class="badge b-warn">Tasdiq kutmoqda</span>' ?>
    <a href="<?= APP_URL ?>/dashboard/edit" class="btn btn-pr btn-sm"><?= si($svgIcons,'edit') ?> Tahrirlash</a>
   </div>
  </div>

  <?php if(!$svc['is_approved']): ?>
  <div class="pending-notice">
   <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
   Servisingiz admin tomonidan tasdiqlanish kutmoqda. Bu vaqt ichida ham barcha foydalanuvchilar ko'ra oladi.
  </div>
  <?php endif; ?>

  <!-- Stats -->
  <div class="stats mb4">
   <?php foreach([
    ['avg_rating','Reyting','star','r'],
    ['review_count','Sharhlar','chat','b'],
    [null,'Rasmlar','image','o'],
    ['fav_count','Sevimlilarda','heart','g'],
   ] as [$key,$lbl,$ico,$color]): ?>
   <div class="stat">
    <div class="stat-ico ic-<?= $color ?>"><?= si($svgIcons,$ico) ?></div>
    <div class="stat-v"><?= $key?round((float)$svc[$key],1):count($images).'/6' ?></div>
    <div class="stat-l"><?= $lbl ?></div>
   </div>
   <?php endforeach; ?>
  </div>

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px">
   <!-- Info card -->
   <div class="card">
    <div class="card-head">Servis ma'lumotlari <a href="<?= APP_URL ?>/dashboard/edit" class="btn btn-ghost btn-sm"><?= si($svgIcons,'edit') ?></a></div>
    <div class="card-body" style="display:flex;flex-direction:column;gap:9px;font-size:.855rem">
     <?php foreach([
       [si($svgIcons,'pin'),'Manzil', e(viloyatlar()[$svc['viloyat']]??$svc['viloyat']).(($svc['tuman'])?', '.e($svc['tuman']):'')],
       [si($svgIcons,'phone'),'Telefon', e($svc['phone'])],
       ['<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>','Ish vaqti', $svc['is_24h']?'<span style="color:var(--ok)">24/7</span>':substr($svc['work_start'],0,5).'–'.substr($svc['work_end'],0,5)],
       ['<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>','Tajriba', (int)$svc['experience_years'].' yil'],
     ] as [$ico,$lbl,$val]): ?>
     <div style="display:flex;align-items:flex-start;gap:9px;padding-bottom:9px;border-bottom:1px solid var(--bdrl)">
      <span style="color:var(--muted);flex-shrink:0;margin-top:1px"><?= $ico ?></span>
      <div style="flex:1">
       <div style="font-size:.72rem;color:var(--muted);text-transform:uppercase;letter-spacing:.04em;margin-bottom:1px"><?= $lbl ?></div>
       <div style="color:var(--text);font-weight:500"><?= $val ?></div>
      </div>
     </div>
     <?php endforeach; ?>
     <div>
      <div style="font-size:.72rem;color:var(--muted);text-transform:uppercase;letter-spacing:.04em;margin-bottom:6px">Mutaxassisliklar</div>
      <div class="tags"><?php foreach($svc['specializations'] as $sp): ?><span class="tag"><?= t('s_'.$sp) ?></span><?php endforeach; ?></div>
     </div>
    </div>
   </div>

   <!-- Images card -->
   <div class="card">
    <div class="card-head">Rasmlar (<?= count($images) ?>/6) <a href="<?= APP_URL ?>/dashboard/edit#imgs" class="btn btn-ghost btn-sm"><?= si($svgIcons,'plus') ?> Qo'shish</a></div>
    <div class="card-body">
     <div class="img-grid">
      <?php foreach($images as $img): ?>
      <div class="img-slot" data-id="<?= (int)$img['id'] ?>">
       <img src="<?= svc_img($img['filename']) ?>" alt="">
       <button class="img-del" onclick="delImgDash(this,<?= (int)$img['id'] ?>)"><?= si($svgIcons,'trash') ?></button>
      </div>
      <?php endforeach; ?>
      <?php for($i=count($images);$i<6;$i++): ?>
      <div class="img-slot" data-empty><div class="img-ph"><?= si($svgIcons,'image') ?><span>Rasm qo'shish</span></div></div>
      <?php endfor; ?>
     </div>
    </div>
   </div>
  </div>

  <!-- Recent reviews -->
  <div class="card mb4">
   <div class="card-head">So'nggi sharhlar <a href="<?= APP_URL ?>/services/<?= (int)$svc['id'] ?>" style="font-size:.8rem;font-weight:400;color:var(--muted)">Hammasini ko'rish →</a></div>
   <div class="card-body">
    <?php if($reviews): ?>
    <?php foreach(array_slice($reviews,0,6) as $rv): ?>
    <div class="rev-card">
     <div class="rev-head">
      <div class="rev-user">
       <div class="rev-av"><?= strtoupper(mb_substr($rv['first_name'],0,1)) ?></div>
       <div>
        <div class="rev-name"><?= e($rv['first_name'].' '.$rv['last_name']) ?></div>
        <span class="stars" style="font-size:.8rem"><?php for($i=1;$i<=5;$i++) echo '<span class="'.($i<=(int)$rv['rating']?'s-on':'s-off').'">★</span>'; ?></span>
       </div>
      </div>
      <span class="rev-date"><?= date('d.m.Y',strtotime($rv['created_at'])) ?></span>
     </div>
     <?php if($rv['comment']): ?><p style="font-size:.855rem;color:var(--text2)"><?= e(mb_substr($rv['comment'],0,160)) ?></p><?php endif; ?>
    </div>
    <?php endforeach; ?>
    <?php else: ?>
    <div class="empty" style="padding:20px"><div class="empty-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg></div><p style="font-size:.84rem">Hali sharh yo'q</p></div>
    <?php endif; ?>
   </div>
  </div>

  <!-- Danger zone -->
  <div style="border:1px solid rgba(192,57,43,.2);border-radius:var(--r);padding:16px">
   <p style="font-weight:600;font-size:.9rem;color:var(--pr);margin-bottom:5px">Xavfli hudud</p>
   <p style="font-size:.84rem;color:var(--muted);margin-bottom:12px">Servisni o'chirsangiz barcha ma'lumotlar va rasmlar butunlay yo'q bo'ladi.</p>
   <form method="POST" action="<?= APP_URL ?>/dashboard/delete" onsubmit="return confirm('Servisni o\'chirishni tasdiqlaysizmi?')">
    <?= csrf_field() ?>
    <button type="submit" class="btn btn-danger btn-sm"><?= si($svgIcons,'trash') ?> Servisni o'chirish</button>
   </form>
  </div>
 </main>
</div>

<script>
const BASE='<?= APP_URL ?>',CSRF='<?= csrf_token() ?>';
async function delImgDash(btn,id){
 if(!confirm('Rasmni o\'chirish?'))return;
 const fd=new FormData();fd.append('image_id',id);fd.append('_csrf',CSRF);
 const r=await fetch(BASE+'/dashboard/img/delete',{method:'POST',body:fd});
 const d=await r.json();
 if(d.ok){
  const s=btn.closest('.img-slot');
  s.innerHTML='<div class="img-ph"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg><span>Rasm qo\'shish</span></div>';
  s.setAttribute('data-empty','');s.removeAttribute('data-id');
  toast('Rasm o\'chirildi','ok');
 }
}
</script>
