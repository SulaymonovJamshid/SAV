<?php ob_start(); ?>
<section class="hero">
 <div class="hero-left">
  <div class="hero-inner">
   <p class="cap" style="color:rgba(201,150,62,.7);margin-bottom:14px">O'zbekiston #1 Avto Servis Platformasi</p>
   <h1 class="hero-title">
    Ishonchli<br>
    <em>Avto Servis</em><br>
    Topish Endi Oson
   </h1>
   <p class="hero-sub">
    Shahringizda sifatli avto ta'mirlash markazlarini toping.
    Narx, reyting va sharhlarni solishtiring.
   </p>
   <div class="hero-btns">
    <a href="<?= APP_URL ?>/register?role=user" class="btn btn-pr btn-lg">Servis Topish</a>
    <a href="<?= APP_URL ?>/register?role=service" class="btn btn-out btn-lg" style="color:rgba(255,255,255,.7);border-color:rgba(255,255,255,.2)">Servis Qo'shish</a>
   </div>
   <div class="hero-stats">
    <div><span class="hst-n">500+</span><span class="hst-l">Servislar</span></div>
    <div><span class="hst-n">14</span><span class="hst-l">Viloyatlar</span></div>
    <div><span class="hst-n">10K+</span><span class="hst-l">Foydalanuvchilar</span></div>
   </div>
  </div>
 </div>

 <div class="hero-right">
  <div style="width:100%;max-width:400px">
   <p class="cap" style="color:var(--muted);margin-bottom:20px">Nima uchun SmartAvtoServis?</p>
   <div class="hero-card-stack">
    <?php
    $features = [
      ['GPS orqali yaqin servislar', 'Joylashuvingiz bo\'yicha eng yaqin va qulay servislarni avtomatik aniqlang'],
      ['Tasdiqlangan sharhlar', 'Faqat haqiqiy mijozlarning fikrlari — soxta sharh yo\'q'],
      ['Narxlarni solishtirish', 'Xizmat narxlarini oldindan ko\'ring va qulay variant tanlang'],
    ];
    foreach($features as [$ttl,$desc]): ?>
    <div class="hcard">
     <div style="font-weight:600;font-size:.9rem;color:var(--text);margin-bottom:4px"><?= $ttl ?></div>
     <div style="font-size:.82rem;color:var(--muted);line-height:1.6"><?= $desc ?></div>
    </div>
    <?php endforeach; ?>
   </div>
   <div style="margin-top:24px;text-align:center">
    <a href="<?= APP_URL ?>/login" class="btn btn-ghost" style="width:100%">Hisobim bor — Kirish</a>
   </div>
  </div>
 </div>
</section>
