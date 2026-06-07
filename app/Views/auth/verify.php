<?php ob_start(); ?>
<div class="auth-wrap">
 <div class="auth-left">
  <div class="auth-left-inner">
   <h1 style="font-family:var(--fd);font-size:2rem;color:#fff;line-height:1.15;font-style:italic;margin-bottom:14px">
    SMS <em style="color:var(--gold);font-style:normal">Tasdiqlash</em>
   </h1>
   <p style="color:rgba(255,255,255,.42);font-size:.875rem;line-height:1.8">
    Telefon raqamingizni tasdiqlash orqali hisobingiz xavfsizligini ta'minlang.
    Kod 10 daqiqa davomida amal qiladi.
   </p>
  </div>
 </div>
 <div class="auth-right">
  <div class="auth-box">
   <div class="auth-title">
    <h2>Kodni kiriting</h2>
    <p><?= t('code_sent',['phone'=>'']) ?> <strong style="color:var(--pr)"><?= e($phone) ?></strong></p>
   </div>

   <?php if(!empty($errors['code'])): ?>
    <div class="alert a-err"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg><?= e($errors['code']) ?></div>
   <?php endif; ?>

   <form method="POST" action="<?= APP_URL ?>/verify-phone" id="otpForm">
    <?= csrf_field() ?>
    <div class="otp-row">
     <?php for($i=0;$i<6;$i++): ?>
     <input type="text" class="otp-c" maxlength="1" inputmode="numeric" pattern="[0-9]"
            autocomplete="<?= $i===0?'one-time-code':'off' ?>">
     <?php endfor; ?>
    </div>
    <input type="hidden" name="code" id="otpVal">
    <button type="submit" class="btn btn-pr btn-bl btn-lg" id="otpBtn" disabled>
     Tasdiqlash
    </button>
   </form>

   <div style="text-align:center;margin-top:18px">
    <div id="cdWrap" style="font-size:.84rem;color:var(--muted)">
     Qayta yuborish <strong style="color:var(--text)" id="cd">60</strong> soniyadan so'ng
    </div>
    <button id="resendBtn" style="display:none;background:none;border:none;cursor:pointer;color:var(--pr);font-size:.84rem;font-weight:600;font-family:inherit" onclick="resendOtp('<?= APP_URL ?>')">
     Kodni qayta yuborish
    </button>
   </div>
   <a href="<?= APP_URL ?>/register" style="display:block;text-align:center;margin-top:14px;font-size:.84rem;color:var(--muted)">
    Orqaga qaytish
   </a>
  </div>
 </div>
</div>
