<?php ob_start(); ?>
<div class="dash">
 <aside class="sidebar">
  <a href="<?= APP_URL ?>/dashboard"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg> Dashboard</a>
  <a href="<?= APP_URL ?>/dashboard/edit" class="on"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg> Tahrirlash</a>
  <?php if($svc): ?><a href="<?= APP_URL ?>/services/<?= (int)$svc['id'] ?>" target="_blank"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg> Ko'rish</a><?php endif; ?>
 </aside>
 <main class="main">
  <div class="sh mb4">
   <h1 style="font-weight:700;font-size:1.2rem;letter-spacing:-.02em"><?= $svc?'Servisni tahrirlash':'Servis yaratish' ?></h1>
   <a href="<?= APP_URL ?>/dashboard" class="btn btn-ghost btn-sm">Orqaga</a>
  </div>

  <?php foreach($errors as $k=>$e): ?>
  <?php if(in_array($k,['name','viloyat','tuman','phone'])): ?><div class="alert a-err" style="margin-bottom:8px"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg><?= e($e) ?></div><?php endif; ?>
  <?php endforeach; ?>

  <form method="POST" action="<?= APP_URL ?>/dashboard/save" id="svcForm">
   <?= csrf_field() ?>
   <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">

    <!-- Left column -->
    <div style="display:flex;flex-direction:column;gap:14px">
     <!-- Basic -->
     <div class="card">
      <div class="card-head">Asosiy ma'lumotlar</div>
      <div class="card-body">
       <div style="display:grid;grid-template-columns:1fr auto;gap:10px;align-items:start">
        <div class="fg" style="margin-bottom:0">
         <label class="lbl">Servis nomi *</label>
         <input type="text" name="name" class="inp <?= !empty($errors['name'])?'err':'' ?>" value="<?= e($old['name']??'') ?>" required>
        </div>
        <div class="fg" style="margin-bottom:0">
         <label class="lbl">Tajriba (yil)</label>
         <input type="number" name="experience_years" class="inp" style="width:90px" value="<?= (int)($old['experience_years']??0) ?>" min="0" max="99">
        </div>
       </div>
       <div class="fg mt3" style="margin-bottom:0">
        <label class="lbl">Tavsif</label>
        <textarea name="description" class="ta" rows="3"><?= e($old['description']??'') ?></textarea>
       </div>
      </div>
     </div>

     <!-- Specs -->
     <div class="card">
      <div class="card-head">Mutaxassisliklar</div>
      <div class="card-body">
       <div style="display:flex;flex-wrap:wrap;gap:6px">
        <?php foreach($specs as $sp): $chk=in_array($sp,$old['specializations']??[]); ?>
        <label class="spec-lbl"><input type="checkbox" name="specs[]" value="<?= e($sp) ?>" <?= $chk?'checked':'' ?>><?= t('s_'.$sp) ?></label>
        <?php endforeach; ?>
       </div>
      </div>
     </div>

     <!-- Work hours -->
     <div class="card">
      <div class="card-head">Ish vaqti</div>
      <div class="card-body">
       <label class="check-row mb3">
        <input type="checkbox" name="is_24h" id="is24h" value="1" <?= !empty($old['is_24h'])?'checked':'' ?>>
        <span>24/7 ishlaydi</span>
       </label>
       <div id="timeWrap">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:12px">
         <div class="fg" style="margin-bottom:0"><label class="lbl">Boshlanish</label><input type="time" name="work_start" class="inp" value="<?= e($old['work_start']??'08:00') ?>"></div>
         <div class="fg" style="margin-bottom:0"><label class="lbl">Tugash</label><input type="time" name="work_end" class="inp" value="<?= e($old['work_end']??'18:00') ?>"></div>
        </div>
        <label class="lbl">Ish kunlari</label>
        <div style="display:flex;flex-wrap:wrap;gap:6px">
         <?php foreach(['Mon'=>'Du','Tue'=>'Se','Wed'=>'Ch','Thu'=>'Pa','Fri'=>'Ju','Sat'=>'Sh','Sun'=>'Ya'] as $d=>$l): ?>
         <label style="display:inline-flex;align-items:center;gap:5px;padding:5px 10px;border:1px solid var(--bdr);border-radius:var(--rs);cursor:pointer;font-size:.8rem;transition:var(--tr)" class="day-lbl">
          <input type="checkbox" name="work_days[]" value="<?= $d ?>" <?= in_array($d,$old['work_days']??[])?'checked':'' ?> style="width:14px;height:14px;accent-color:var(--pr)"><?= $l ?>
         </label>
         <?php endforeach; ?>
        </div>
       </div>
      </div>
     </div>
    </div>

    <!-- Right column -->
    <div style="display:flex;flex-direction:column;gap:14px">
     <!-- Location -->
     <div class="card">
      <div class="card-head">Joylashuv</div>
      <div class="card-body">
       <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
        <div class="fg">
         <label class="lbl">Viloyat *</label>
         <select name="viloyat" class="sel <?= !empty($errors['viloyat'])?'err':'' ?>" id="vilSel" onchange="loadTumans(this.value,'<?= APP_URL ?>','<?= e($old['tuman']??'') ?>')">
          <option value="">Tanlang</option>
          <?php foreach($viloyatlar as $k=>$v): ?><option value="<?= e($k) ?>" <?= ($old['viloyat']??'')===$k?'selected':'' ?>><?= e($v) ?></option><?php endforeach; ?>
         </select>
        </div>
        <div class="fg">
         <label class="lbl">Tuman *</label>
         <select name="tuman" class="sel <?= !empty($errors['tuman'])?'err':'' ?>" id="tumanSel">
          <option value="">Tanlang</option>
          <?php if($old['tuman']??''): ?><option value="<?= e($old['tuman']) ?>" selected><?= e($old['tuman']) ?></option><?php endif; ?>
         </select>
        </div>
       </div>
       <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
        <div class="fg"><label class="lbl">Shahar/Mahalla</label><input type="text" name="shahar" class="inp" value="<?= e($old['shahar']??'') ?>"></div>
        <div class="fg"><label class="lbl">To'liq manzil</label><input type="text" name="address" class="inp" value="<?= e($old['address']??'') ?>"></div>
       </div>
       <div class="fg">
        <label class="lbl">Xaritada belgilash</label>
        <button type="button" class="btn btn-ghost btn-sm mb2" id="gpsBtn" onclick="detectGps()">
         <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3"/></svg>
         GPS aniqlash
        </button>
        <div id="map-pick"></div>
        <input type="hidden" name="latitude"  id="latInp" value="<?= e($old['latitude']??'') ?>">
        <input type="hidden" name="longitude" id="lngInp" value="<?= e($old['longitude']??'') ?>">
        <p style="font-size:.72rem;color:var(--muted);margin-top:5px">Xaritada bosib aniq joylashuvni belgilang</p>
       </div>
      </div>
     </div>

     <!-- Pricing -->
     <div class="card">
      <div class="card-head">Narxlar</div>
      <div class="card-body">
       <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
        <div class="fg"><label class="lbl">Dan (so'm)</label><input type="number" name="price_from" class="inp" value="<?= (int)($old['price_from']??0) ?>" min="0"></div>
        <div class="fg"><label class="lbl">Gacha (so'm)</label><input type="number" name="price_to" class="inp" value="<?= (int)($old['price_to']??0) ?>" min="0"></div>
       </div>
       <div class="fg" style="margin-bottom:0"><label class="lbl">Narx izohi</label><textarea name="price_note" class="ta" rows="2"><?= e($old['price_note']??'') ?></textarea></div>
      </div>
     </div>

     <!-- Contact -->
     <div class="card">
      <div class="card-head">Aloqa ma'lumotlari</div>
      <div class="card-body">
       <div class="fg"><label class="lbl">Telefon *</label><input type="tel" name="phone" class="inp <?= !empty($errors['phone'])?'err':'' ?>" value="<?= e($old['phone']??'') ?>" required></div>
       <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
        <div class="fg" style="margin-bottom:0"><label class="lbl">Telegram</label><input type="text" name="telegram" class="inp" placeholder="@username" value="<?= e($old['telegram']??'') ?>"></div>
        <div class="fg" style="margin-bottom:0"><label class="lbl">Veb-sayt</label><input type="url" name="website" class="inp" placeholder="https://" value="<?= e($old['website']??'') ?>"></div>
       </div>
      </div>
     </div>
    </div>
   </div>

   <!-- Images -->
   <div class="card mt3" id="imgs">
    <div class="card-head">
     Rasmlar (<?= count($images) ?>/6)
     <span style="font-size:.78rem;color:var(--muted);font-weight:400">JPG/PNG/WEBP, max <?= env('UPLOAD_MAX_MB',5) ?>MB</span>
    </div>
    <div class="card-body">
     <div class="img-grid">
      <?php foreach($images as $img): ?>
      <div class="img-slot" data-id="<?= (int)$img['id'] ?>">
       <img src="<?= svc_img($img['filename']) ?>" alt="">
       <button type="button" class="img-del" onclick="delImg(this,<?= (int)$img['id'] ?>)"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
      </div>
      <?php endforeach; ?>
      <?php for($i=count($images);$i<6;$i++): ?>
      <div class="img-slot" data-empty onclick="clickUpload()">
       <div class="img-ph"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg><span>Rasm qo'shish</span></div>
      </div>
      <?php endfor; ?>
     </div>
     <input type="file" id="imgFile" accept="image/*" style="display:none" onchange="uploadImg(this)">
    </div>
   </div>

   <!-- Actions -->
   <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:16px">
    <a href="<?= APP_URL ?>/dashboard" class="btn btn-ghost">Bekor qilish</a>
    <button type="submit" class="btn btn-pr btn-lg">
     <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
     Saqlash
    </button>
   </div>
  </form>
 </main>
</div>

<script>
const BASE='<?= APP_URL ?>',CSRF='<?= csrf_token() ?>';
document.addEventListener('DOMContentLoaded',()=>{
 const v='<?= e($old['viloyat']??'') ?>',tm='<?= e($old['tuman']??'') ?>';
 if(v) loadTumans(v,BASE,tm);
 const lat=parseFloat('<?= e($old['latitude']??'') ?>'),lng=parseFloat('<?= e($old['longitude']??'') ?>');
 initPickMap(lat||null,lng||null);
 initSpecBoxes();init24h();
 // Day label active state
 document.querySelectorAll('.day-lbl input').forEach(inp=>{
  const upd=()=>{const l=inp.closest('.day-lbl');l.style.borderColor=inp.checked?'var(--pr)':'';l.style.background=inp.checked?'rgba(192,57,43,.07)':''};
  upd();inp.addEventListener('change',upd);
 });
});
function clickUpload(){document.getElementById('imgFile').click()}
async function uploadImg(inp){
 if(!inp.files[0])return;
 const fd=new FormData();fd.append('image',inp.files[0]);fd.append('_csrf',CSRF);
 const r=await fetch(BASE+'/dashboard/img/upload',{method:'POST',body:fd});
 const d=await r.json();
 if(d.ok){
  const slot=document.querySelector('.img-slot[data-empty]');
  if(slot){slot.innerHTML=`<img src="${d.url}" alt=""><button type="button" class="img-del" onclick="delImg(this,0)"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>`;slot.removeAttribute('data-empty');slot.setAttribute('data-fn',d.filename);}
  toast('Rasm yuklandi!','ok');
 }else toast(d.error||'Xato','err');
 inp.value='';
}
async function delImg(btn,id){
 if(!confirm('Rasmni o\'chirish?'))return;
 const s=btn.closest('.img-slot');
 const fd=new FormData();fd.append('image_id',id||s.dataset.id||s.dataset.fn);fd.append('_csrf',CSRF);
 const r=await fetch(BASE+'/dashboard/img/delete',{method:'POST',body:fd});
 const d=await r.json();
 if(d.ok){
  s.innerHTML='<div class="img-ph"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg><span>Rasm qo\'shish</span></div>';
  s.setAttribute('data-empty','');s.removeAttribute('data-id');s.style.cursor='pointer';s.onclick=()=>clickUpload();
  toast('Rasm o\'chirildi','ok');
 }
}
</script>
