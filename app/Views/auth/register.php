<?php ob_start(); $isSvc=($role==='service'); ?>
<div class="auth-wrap">
 <!-- LEFT PANEL -->
 <div class="auth-left">
  <div class="auth-left-inner">
   <a href="<?= APP_URL ?>/" class="brand" style="font-size:1.1rem;margin-bottom:28px;display:inline-flex">
    <div class="brand-mark" style="width:30px;height:30px">
     <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
    </div>
    Smart<em>Avto</em>Servis
   </a>
   <h1 style="font-family:var(--fd);font-size:2rem;color:#fff;line-height:1.15;margin-bottom:14px;font-style:italic">
    O'zbekistondagi Avto Servislar <em style="color:var(--gold);font-style:normal">Platformasi</em>
   </h1>
   <p style="color:rgba(255,255,255,.42);font-size:.88rem;line-height:1.8;margin-bottom:28px">
    Ro'yxatdan o'ting va minglab foydalanuvchilar bilan birlashing.
   </p>
   <div style="display:flex;flex-direction:column;gap:12px">
    <?php foreach([
      ['Bepul ro\'yxatdan o\'tish', 'Hech qanday to\'lov yo\'q'],
      ['GPS joylashuv', 'Yaqin servislarni avtomatik aniqlash'],
      ['Shaffof narxlar', 'Narxlarni oldindan ko\'ring'],
    ] as [$t,$d]): ?>
    <div style="display:flex;align-items:flex-start;gap:10px">
     <div style="width:20px;height:20px;border-radius:50%;background:rgba(201,150,62,.2);border:1px solid rgba(201,150,62,.4);display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:1px">
      <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="3" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
     </div>
     <div>
      <div style="font-size:.86rem;font-weight:600;color:#fff"><?= $t ?></div>
      <div style="font-size:.76rem;color:rgba(255,255,255,.38);margin-top:1px"><?= $d ?></div>
     </div>
    </div>
    <?php endforeach; ?>
   </div>
  </div>
 </div>

 <!-- RIGHT PANEL -->
 <div class="auth-right" style="overflow-y:auto;max-height:100vh;align-items:flex-start;padding-top:32px;padding-bottom:32px">
  <div class="auth-box" style="max-width:400px">
   <div class="auth-title">
    <h2>Hisob yaratish</h2>
    <p>Quyidagi formni to'ldiring</p>
   </div>

   <!-- Role selector -->
   <div class="role-grid">
    <div class="role-opt <?= !$isSvc?'on':'' ?>" onclick="setRole('user')" data-role="user">
     <div class="role-icon">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
     </div>
     <span class="role-label">Foydalanuvchi</span>
     <span class="role-desc">Servis qidiraman</span>
    </div>
    <div class="role-opt <?= $isSvc?'on':'' ?>" onclick="setRole('service')" data-role="service">
     <div class="role-icon">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
     </div>
     <span class="role-label">Servis egasi</span>
     <span class="role-desc">Servisimni qo'shaman</span>
    </div>
   </div>

   <?php if(!empty($errors['general'])||!empty($errors['contact'])): ?>
    <div class="alert a-err"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg><?= e($errors['general']??$errors['contact']) ?></div>
   <?php endif; ?>

   <form method="POST" action="<?= APP_URL ?>/register">
    <?= csrf_field() ?>
    <input type="hidden" name="role" id="roleInp" value="<?= e($role) ?>">

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
     <div class="fg" style="margin-bottom:0">
      <label class="lbl"><?= t('first_name') ?> *</label>
      <input type="text" name="first_name" class="inp <?= !empty($errors['first_name'])?'err':'' ?>"
             placeholder="Ismingiz" value="<?= e($old['first_name']??'') ?>" required>
      <?php if(!empty($errors['first_name'])): ?><span class="err-msg"><?= e($errors['first_name']) ?></span><?php endif; ?>
     </div>
     <div class="fg" style="margin-bottom:0">
      <label class="lbl"><?= t('last_name') ?> *</label>
      <input type="text" name="last_name" class="inp <?= !empty($errors['last_name'])?'err':'' ?>"
             placeholder="Familiyangiz" value="<?= e($old['last_name']??'') ?>" required>
     </div>
    </div>
    <div style="height:12px"></div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
     <div class="fg" style="margin-bottom:0">
      <label class="lbl">Email</label>
      <input type="email" name="email" class="inp <?= !empty($errors['email'])?'err':'' ?>"
             placeholder="email@example.com" value="<?= e($old['email']??'') ?>">
      <?php if(!empty($errors['email'])): ?><span class="err-msg"><?= e($errors['email']) ?></span><?php endif; ?>
     </div>
     <div class="fg" style="margin-bottom:0">
      <label class="lbl">Telefon <span id="phReq" style="color:var(--pr)"></span></label>
      <input type="tel" name="phone" id="phInp" class="inp <?= !empty($errors['phone'])?'err':'' ?>"
             placeholder="+998901234567" value="<?= e($old['phone']??'') ?>">
      <?php if(!empty($errors['phone'])): ?><span class="err-msg"><?= e($errors['phone']) ?></span><?php endif; ?>
     </div>
    </div>
    <p style="font-size:.72rem;color:var(--muted);margin:5px 0 12px">Email yoki telefon — kamida bittasi kiritilishi shart</p>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
     <div class="fg" style="margin-bottom:0">
      <label class="lbl">Parol *</label>
      <input type="password" name="password" class="inp <?= !empty($errors['password'])?'err':'' ?>"
             placeholder="Kamida 8 belgi" required minlength="8">
      <?php if(!empty($errors['password'])): ?><span class="err-msg"><?= e($errors['password']) ?></span><?php endif; ?>
     </div>
     <div class="fg" style="margin-bottom:0">
      <label class="lbl">Tasdiqlash *</label>
      <input type="password" name="password2" class="inp <?= !empty($errors['password2'])?'err':'' ?>"
             placeholder="Qayta kiriting" required>
      <?php if(!empty($errors['password2'])): ?><span class="err-msg"><?= e($errors['password2']) ?></span><?php endif; ?>
     </div>
    </div>

    <!-- Service fields -->
    <div id="svcFields" style="display:<?= $isSvc?'block':'none' ?>;margin-top:16px">
     <div class="hr"></div>
     <p class="cap" style="color:var(--muted);margin-bottom:12px">Servis ma'lumotlari</p>

     <div class="fg">
      <label class="lbl">Servis nomi *</label>
      <input type="text" name="svc_name" class="inp <?= !empty($errors['svc_name'])?'err':'' ?>"
             placeholder="Masalan: AutoMaster Servis" value="<?= e($old['svc_name']??'') ?>">
      <?php if(!empty($errors['svc_name'])): ?><span class="err-msg"><?= e($errors['svc_name']) ?></span><?php endif; ?>
     </div>

     <div class="fg">
      <label class="lbl">Mutaxassisliklar</label>
      <div style="display:flex;flex-wrap:wrap;gap:5px">
       <?php foreach($specs as $sp): ?>
        <label class="spec-lbl">
         <input type="checkbox" name="specs[]" value="<?= e($sp) ?>" <?= in_array($sp,$old['specs']??[])?'checked':'' ?>>
         <?= t('s_'.$sp) ?>
        </label>
       <?php endforeach; ?>
      </div>
     </div>

     <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
      <div class="fg" style="margin-bottom:0">
       <label class="lbl">Viloyat *</label>
       <select name="viloyat" class="sel <?= !empty($errors['viloyat'])?'err':'' ?>">
        <option value="">Tanlang</option>
        <?php foreach($viloyatlar as $k=>$v): ?><option value="<?= e($k) ?>" <?= ($old['viloyat']??'')===$k?'selected':'' ?>><?= e($v) ?></option><?php endforeach; ?>
       </select>
      </div>
      <div class="fg" style="margin-bottom:0">
       <label class="lbl">Tuman *</label>
       <input type="text" name="tuman" class="inp" placeholder="Tuman" value="<?= e($old['tuman']??'') ?>">
      </div>
     </div>
     <div class="fg" style="margin-top:10px">
      <label class="lbl">Manzil</label>
      <input type="text" name="address" class="inp" placeholder="To'liq manzil" value="<?= e($old['address']??'') ?>">
     </div>
    </div>

    <button type="submit" class="btn btn-pr btn-bl btn-lg" style="margin-top:14px">Ro'yxatdan o'tish</button>
   </form>

   <p style="text-align:center;margin-top:16px;font-size:.84rem;color:var(--muted)">
    Hisobingiz bormi?
    <a href="<?= APP_URL ?>/login" style="color:var(--pr);font-weight:600">Kirish</a>
   </p>
  </div>
 </div>
</div>
<script>
function setRole(r){
 document.getElementById('roleInp').value=r;
 document.querySelectorAll('.role-opt').forEach(o=>o.classList.toggle('on',o.dataset.role===r));
 document.getElementById('svcFields').style.display=r==='service'?'block':'none';
 const pi=document.getElementById('phInp');if(pi)pi.required=r==='service';
 document.getElementById('phReq').textContent=r==='service'?'*':'';
}
document.addEventListener('DOMContentLoaded',()=>setRole('<?= e($role) ?>'));
</script>
