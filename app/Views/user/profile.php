<?php ob_start(); ?>
<div style="max-width:900px;margin:0 auto;padding:24px">
 <h1 style="font-weight:700;font-size:1.2rem;letter-spacing:-.02em;margin-bottom:20px">Profil sozlamalari</h1>
 <div style="display:grid;grid-template-columns:320px 1fr;gap:16px;align-items:start">

  <!-- Left: edit form -->
  <div class="card">
   <div class="card-head">Ma'lumotlarni tahrirlash</div>
   <div class="card-body">
    <!-- Avatar -->
    <div style="text-align:center;margin-bottom:18px">
     <?php if($u['avatar']): ?>
      <img src="<?= asset('uploads/avatars/'.e($u['avatar'])) ?>" style="width:72px;height:72px;border-radius:50%;object-fit:cover;border:2px solid var(--bdr);display:inline-block" alt="">
     <?php else: ?>
      <div style="width:72px;height:72px;border-radius:50%;background:var(--pr);display:inline-flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:1.5rem;border:2px solid var(--bdr)"><?= strtoupper(mb_substr($u['first_name'],0,1)) ?></div>
     <?php endif; ?>
     <div style="margin-top:8px">
      <label class="btn btn-ghost btn-sm" style="cursor:pointer;display:inline-flex">
       <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
       Rasm o'zgartirish
       <input type="file" name="avatar" id="avFile" accept="image/*" style="display:none" onchange="previewAv(this)">
      </label>
     </div>
    </div>

    <form method="POST" action="<?= APP_URL ?>/profile/update" enctype="multipart/form-data">
     <?= csrf_field() ?>
     <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
      <div class="fg" style="margin-bottom:0"><label class="lbl">Ism</label><input type="text" name="first_name" class="inp <?= !empty($errors['first_name'])?'err':'' ?>" value="<?= e($u['first_name']) ?>" required></div>
      <div class="fg" style="margin-bottom:0"><label class="lbl">Familiya</label><input type="text" name="last_name" class="inp" value="<?= e($u['last_name']) ?>" required></div>
     </div>
     <div class="hr"></div>
     <div class="fg"><label class="lbl">Email</label><input type="email" name="email" class="inp <?= !empty($errors['email'])?'err':'' ?>" value="<?= e($u['email']??'') ?>"></div>
     <div class="fg"><label class="lbl">Telefon</label><input type="tel" name="phone" class="inp" value="<?= e($u['phone']??'') ?>"></div>
     <div class="hr"></div>
     <p class="cap" style="color:var(--muted);margin-bottom:10px">Parolni o'zgartirish</p>
     <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
      <div class="fg" style="margin-bottom:0"><label class="lbl">Yangi parol</label><input type="password" name="new_password" class="inp" placeholder="Kamida 8 belgi"></div>
      <div class="fg" style="margin-bottom:0"><label class="lbl">Tasdiqlash</label><input type="password" name="confirm_new_pw" class="inp"></div>
     </div>
     <button type="submit" class="btn btn-pr btn-bl mt4">
      <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/></svg>
      Saqlash
     </button>
    </form>
   </div>
  </div>

  <!-- Right: favorites + reviews -->
  <div style="display:flex;flex-direction:column;gap:14px">
   <div class="card">
    <div class="card-head">Sevimlilar <span style="font-size:.8rem;font-weight:400;color:var(--muted)"><?= count($favorites) ?> ta</span></div>
    <div class="card-body">
     <?php foreach($favorites as $fv): ?>
     <div style="display:flex;align-items:center;gap:10px;padding:9px 0;border-bottom:1px solid var(--bdrl)">
      <?php $fc=$fv['cover']??null; ?>
      <?php if($fc): ?><img src="<?= svc_img($fc) ?>" style="width:44px;height:44px;border-radius:6px;object-fit:cover;flex-shrink:0" alt="">
      <?php else: ?><div style="width:44px;height:44px;background:var(--card2);border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--light)" stroke-width="1.5"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg></div><?php endif; ?>
      <div style="flex:1;min-width:0">
       <div style="font-weight:600;font-size:.875rem;color:var(--text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= e($fv['name']) ?></div>
       <div style="font-size:.75rem;color:var(--muted)"><?= e(viloyatlar()[$fv['viloyat']]??$fv['viloyat']) ?></div>
      </div>
      <a href="<?= APP_URL ?>/services/<?= (int)$fv['id'] ?>" class="btn btn-ghost btn-sm">Ko'rish</a>
     </div>
     <?php endforeach; ?>
     <?php if(empty($favorites)): ?><p style="text-align:center;color:var(--muted);font-size:.84rem;padding:16px 0">Hali sevimli servis yo'q</p><?php endif; ?>
    </div>
   </div>
   <div class="card">
    <div class="card-head">Mening sharhlarim</div>
    <div class="card-body">
     <?php foreach($reviews as $rv): ?>
     <div style="padding:9px 0;border-bottom:1px solid var(--bdrl)">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:3px">
       <a href="<?= APP_URL ?>/services/<?= (int)$rv['svc_id'] ?>" style="font-weight:600;font-size:.875rem;color:var(--pr)"><?= e($rv['svc_name']) ?></a>
       <span style="font-size:.72rem;color:var(--muted)"><?= date('d.m.Y',strtotime($rv['created_at'])) ?></span>
      </div>
      <span class="stars" style="font-size:.8rem"><?php for($i=1;$i<=5;$i++) echo '<span class="'.($i<=(int)$rv['rating']?'s-on':'s-off').'">★</span>'; ?></span>
      <?php if($rv['comment']): ?><p style="font-size:.82rem;color:var(--muted);margin-top:3px"><?= e(mb_substr($rv['comment'],0,100)) ?><?= mb_strlen($rv['comment'])>100?'...':'' ?></p><?php endif; ?>
     </div>
     <?php endforeach; ?>
     <?php if(empty($reviews)): ?><p style="text-align:center;color:var(--muted);font-size:.84rem;padding:16px 0">Hali sharh qoldirmadingiz</p><?php endif; ?>
    </div>
   </div>
  </div>
 </div>
</div>
<script>
function previewAv(inp){
 if(!inp.files[0])return;
 const r=new FileReader();
 r.onload=e=>{
  const old=document.querySelector('img[alt=""]');
  if(old){old.src=e.target.result;return;}
  const div=document.querySelector('.av-lg,[style*="1.5rem"]');
  if(div){const img=document.createElement('img');img.src=e.target.result;img.style.cssText='width:72px;height:72px;border-radius:50%;object-fit:cover;border:2px solid var(--bdr);display:inline-block';img.alt='';div.replaceWith(img);}
 };
 r.readAsDataURL(inp.files[0]);
}
</script>
