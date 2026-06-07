<?php ob_start(); ?>
<div class="auth-wrap">
 <div class="auth-left">
  <div class="auth-left-inner">
   <a href="<?= APP_URL ?>/" class="brand" style="font-size:1.1rem;margin-bottom:28px;display:inline-flex">
    <div class="brand-mark" style="width:30px;height:30px">
     <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
    </div>
    Smart<em>Avto</em>Servis
   </a>
   <h1 style="font-family:var(--fd);font-size:2.2rem;color:#fff;line-height:1.12;margin-bottom:14px;font-style:italic">
    Avtomobilingizga <em style="color:var(--gold);font-style:normal">Eng Yaxshi</em> Servisni Toping
   </h1>
   <p style="color:rgba(255,255,255,.45);font-size:.9rem;line-height:1.8">
    O'zbekiston bo'ylab 500+ tasdiqlangan avto servislar. GPS, reyting va narxlar asosida tanlang.
   </p>
   <div class="auth-left-stats" style="margin-top:32px;padding-top:28px;border-top:1px solid rgba(255,255,255,.07);display:flex;gap:28px">
    <div><span class="auth-stat-n">500+</span><span class="auth-stat-l">Servislar</span></div>
    <div><span class="auth-stat-n">4.8</span><span class="auth-stat-l">O'rtacha Reyting</span></div>
    <div><span class="auth-stat-n">14</span><span class="auth-stat-l">Viloyat</span></div>
   </div>
  </div>
 </div>

 <div class="auth-right">
  <div class="auth-box">
   <div class="auth-title">
    <h2>Xush kelibsiz</h2>
    <p>Hisobingizga kiring</p>
   </div>

   <?php if(!empty($errors['general'])): ?>
    <div class="alert a-err">
     <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
     <?= e($errors['general']) ?>
    </div>
   <?php endif; ?>

   <form method="POST" action="<?= APP_URL ?>/login">
    <?= csrf_field() ?>
    <div class="fg">
     <label class="lbl">Email yoki Telefon</label>
     <input type="text" name="identifier" class="inp <?= !empty($errors['identifier'])?'err':'' ?>"
            placeholder="email@example.com yoki +998..." value="<?= e($old['identifier']??'') ?>" autofocus>
     <?php if(!empty($errors['identifier'])): ?><span class="err-msg"><?= e($errors['identifier']) ?></span><?php endif; ?>
    </div>
    <div class="fg">
     <label class="lbl">Parol</label>
     <div style="position:relative">
      <input type="password" name="password" id="pwdI" class="inp" placeholder="Parolingiz" required>
      <button type="button" onclick="const i=document.getElementById('pwdI');i.type=i.type==='password'?'text':'password'"
              style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--muted);line-height:1;padding:4px">
       <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
      </button>
     </div>
    </div>
    <button type="submit" class="btn btn-pr btn-bl btn-lg" style="margin-top:6px">Kirish</button>
   </form>

   <p style="text-align:center;margin-top:18px;font-size:.84rem;color:var(--muted)">
    Hisob yo'qmi?
    <a href="<?= APP_URL ?>/register" style="color:var(--pr);font-weight:600">Ro'yxatdan o'tish</a>
   </p>
  </div>
 </div>
</div>
