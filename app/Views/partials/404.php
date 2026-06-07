<?php
$pageContent='<div style="min-height:60vh;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:16px;text-align:center;padding:40px">
 <div style="width:64px;height:64px;background:var(--card2);border-radius:16px;display:flex;align-items:center;justify-content:center">
  <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--light)" stroke-width="1.4" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
 </div>
 <div>
  <h1 style="font-size:1.4rem;font-weight:700;color:var(--text);margin-bottom:6px">Sahifa topilmadi</h1>
  <p style="color:var(--muted);font-size:.9rem">Bu sahifa mavjud emas yoki o\'chirilgan</p>
 </div>
 <a href="'.APP_URL.'/services" class="btn btn-ghost">Bosh sahifaga qaytish</a>
</div>';
$title='404';
require __DIR__.'/layout.php';
