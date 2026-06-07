/* SmartAvtoServis — app.js v2 */

/* ── Theme ──────────────────────────────────────────────────── */
const Theme = {
  init() {
    const t = localStorage.getItem('theme') ||
      document.documentElement.getAttribute('data-theme') || 'light';
    document.documentElement.setAttribute('data-theme', t);
  },
  toggle() {
    const cur = document.documentElement.getAttribute('data-theme') || 'light';
    const nxt = cur === 'light' ? 'dark' : 'light';
    document.documentElement.setAttribute('data-theme', nxt);
    localStorage.setItem('theme', nxt);
    const base = document.querySelector('meta[name=base]')?.content ||
      document.querySelector('.brand')?.href?.replace(/\/[^/]*$/, '') || '';
    fetch(`${base}/set-theme?theme=${nxt}`).catch(() => {});
  }
};

/* ── Dropdown ───────────────────────────────────────────────── */
function toggleDd() {
  const m = document.getElementById('ddMenu');
  if (!m) return;
  const open = m.style.display === 'block';
  m.style.display = open ? 'none' : 'block';
}
document.addEventListener('click', e => {
  if (!e.target.closest('.dd'))
    document.querySelectorAll('.dd-menu').forEach(m => m.style.display = 'none');
});

/* ── Toast ──────────────────────────────────────────────────── */
function toast(msg, type = 'info') {
  let w = document.getElementById('toasts');
  if (!w) { w = document.createElement('div'); w.id = 'toasts'; document.body.appendChild(w); }
  const el = document.createElement('div');
  el.className = `toast t-${type}`;
  el.innerHTML = `<span class="t-dot"></span><span>${msg}</span>`;
  w.appendChild(el);
  setTimeout(() => {
    el.style.opacity = '0'; el.style.transform = 'translateX(10px)';
    el.style.transition = '.25s ease';
    setTimeout(() => el.remove(), 260);
  }, 3500);
}

/* ── OTP ────────────────────────────────────────────────────── */
function initOtp() {
  const cells = [...document.querySelectorAll('.otp-c')];
  const hidden = document.getElementById('otpVal');
  const btn    = document.getElementById('otpBtn');
  if (!cells.length) return;

  const sync = () => {
    const v = cells.map(c => c.value).join('');
    if (hidden) hidden.value = v;
    if (btn) btn.disabled = v.length < 6;
  };
  cells.forEach((c, i) => {
    c.addEventListener('input', () => {
      c.value = c.value.replace(/\D/, '').slice(-1);
      if (c.value && i < cells.length - 1) cells[i + 1].focus();
      sync();
    });
    c.addEventListener('keydown', e => {
      if (e.key === 'Backspace' && !c.value && i > 0) cells[i - 1].focus();
    });
    c.addEventListener('paste', e => {
      e.preventDefault();
      const d = (e.clipboardData || window.clipboardData).getData('text')
        .replace(/\D/g, '').slice(0, 6);
      d.split('').forEach((ch, j) => { if (cells[j]) cells[j].value = ch; });
      cells[Math.min(d.length, cells.length - 1)]?.focus();
      sync();
    });
  });
}

/* ── OTP Countdown ──────────────────────────────────────────── */
function startCountdown(sec = 60) {
  const el  = document.getElementById('cd');
  const wrap = document.getElementById('cdWrap');
  const btn  = document.getElementById('resendBtn');
  if (!el) return;
  const iv = setInterval(() => {
    sec--;
    el.textContent = sec;
    if (sec <= 0) {
      clearInterval(iv);
      if (wrap) wrap.style.display = 'none';
      if (btn)  btn.style.display  = 'block';
    }
  }, 1000);
}

function resendOtp(base) {
  fetch(`${base}/resend-otp`).then(r => r.json()).then(() => {
    toast('SMS qayta yuborildi!', 'ok');
    startCountdown(60);
    document.getElementById('resendBtn').style.display = 'none';
    const wrap = document.getElementById('cdWrap');
    if (wrap) wrap.style.display = 'block';
    document.getElementById('cd').textContent = '60';
  });
}

/* ── Star rating ────────────────────────────────────────────── */
function initStars() {
  const inp  = document.getElementById('starInp');
  const hid  = document.getElementById('ratingVal');
  if (!inp) return;
  const stars = [...inp.querySelectorAll('.star')];
  let cur = parseInt(hid?.value || '0');

  const hl = n => stars.forEach((s, i) => s.classList.toggle('on', i < n));
  hl(cur);

  stars.forEach((s, i) => {
    s.addEventListener('click', () => {
      cur = i + 1;
      if (hid) hid.value = cur;
      hl(cur);
    });
    s.addEventListener('mouseover', () => hl(i + 1));
    s.addEventListener('mouseout',  () => hl(cur));
  });
}

/* ── Favorite toggle ────────────────────────────────────────── */
function toggleFav(btn, id, base) {
  const csrf = document.querySelector('[name=_csrf]')?.value || '';
  const fd = new FormData();
  fd.append('service_id', id);
  fd.append('_csrf', csrf);
  fetch(`${base}/favorites/toggle`, { method: 'POST', body: fd })
    .then(r => r.json())
    .then(d => {
      const on = d.status === 'added';
      btn.classList.toggle('on', on);
      // Update SVG fill
      const svg = btn.querySelector('svg');
      if (svg) {
        svg.style.fill = on ? 'var(--pr)' : 'none';
        svg.style.stroke = 'var(--pr)';
      }
    });
}

/* ── Viloyat → Tuman ────────────────────────────────────────── */
function loadTumans(viloyat, base, selected = '') {
  if (!viloyat) return;
  fetch(`${base}/api/tumans?viloyat=${encodeURIComponent(viloyat)}`)
    .then(r => r.json())
    .then(d => {
      const sel = document.getElementById('tumanSel');
      if (!sel) return;
      sel.innerHTML = '<option value="">Tanlang</option>';
      d.tumans.forEach(t => {
        const o = document.createElement('option');
        o.value = t; o.textContent = t;
        if (t === selected) o.selected = true;
        sel.appendChild(o);
      });
    });
}

/* ── Nearby map ─────────────────────────────────────────────── */
let nearMap;
function openNearby(base) {
  const modal = document.getElementById('nearModal');
  if (modal) modal.style.display = 'flex';
  const btn = document.getElementById('nearBtn');
  if (btn) btn.innerHTML = '<span class="spin" style="width:13px;height:13px"></span> Aniqlanmoqda...';

  navigator.geolocation?.getCurrentPosition(pos => {
    const { latitude: la, longitude: lo } = pos.coords;
    if (btn) btn.innerHTML = 'Yaqin servislar';

    if (!nearMap) {
      nearMap = L.map('nearMap').setView([la, lo], 12);
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        { attribution: '© OpenStreetMap' }).addTo(nearMap);
    } else nearMap.setView([la, lo], 12);

    const userIcon = L.divIcon({
      className: '',
      html: `<div style="width:14px;height:14px;background:var(--pr);border-radius:50%;border:3px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,.3)"></div>`,
      iconSize: [14, 14], iconAnchor: [7, 7]
    });
    L.marker([la, lo], { icon: userIcon }).addTo(nearMap).bindPopup('Siz shu yerdasiz');

    fetch(`${base}/api/nearby?lat=${la}&lng=${lo}`)
      .then(r => r.json())
      .then(d => {
        const list = document.getElementById('nearList');
        if (list) list.innerHTML = '';
        if (!d.services?.length) {
          if (list) list.innerHTML = '<p style="text-align:center;color:var(--muted);padding:16px;font-size:.84rem">10 km ichida servis topilmadi</p>';
          return;
        }
        d.services.forEach(s => {
          const icon = L.divIcon({
            className: '',
            html: `<div style="width:28px;height:28px;background:var(--pr);border-radius:50%;border:3px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,.3);display:flex;align-items:center;justify-content:center"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg></div>`,
            iconSize: [28, 28], iconAnchor: [14, 14]
          });
          const m = L.marker([+s.latitude, +s.longitude], { icon }).addTo(nearMap);
          m.bindPopup(`<strong style="font-size:.875rem">${s.name}</strong><br><span style="font-size:.78rem;color:#666">${s.address}</span><br><a href="${base}/services/${s.id}" style="color:var(--pr);font-size:.8rem">Ko'rish →</a>`);

          if (list) {
            const div = document.createElement('div');
            div.style.cssText = 'display:flex;justify-content:space-between;align-items:center;padding:9px 0;border-bottom:1px solid var(--bdrl)';
            div.innerHTML = `
              <div style="min-width:0">
               <div style="font-weight:600;font-size:.875rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">${s.name}</div>
               <div style="font-size:.75rem;color:var(--muted)">${s.address}</div>
              </div>
              <div style="display:flex;align-items:center;gap:8px;flex-shrink:0;margin-left:10px">
               <span class="dist">${s.km} km</span>
               <a href="${base}/services/${s.id}" class="btn btn-pr btn-sm">Ko'rish</a>
              </div>`;
            list.appendChild(div);
          }
        });
        const grp = L.featureGroup(d.services.map(s => L.marker([+s.latitude, +s.longitude])));
        nearMap.fitBounds(grp.getBounds().pad(0.2));
      });
  }, () => {
    if (btn) btn.innerHTML = 'Yaqin servislar';
    if (modal) modal.style.display = 'none';
    toast('Joylashuv aniqlanmadi. Ruxsat bering.', 'err');
  });
}

/* ── Service detail map ──────────────────────────────────────── */
function initDetailMap(lat, lng, name) {
  if (!window.L || !document.getElementById('map')) return;
  const map = L.map('map').setView([lat, lng], 15);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
    { attribution: '© OpenStreetMap' }).addTo(map);
  L.marker([lat, lng]).addTo(map).bindPopup(`<strong>${name}</strong>`).openPopup();
}

/* ── Map picker (edit form) ─────────────────────────────────── */
let pickMap, pickMarker;
function initPickMap(lat, lng) {
  if (!window.L || !document.getElementById('map-pick')) return;
  const cLat = lat || 41.2995, cLng = lng || 69.2401;
  pickMap = L.map('map-pick').setView([cLat, cLng], lat ? 14 : 11);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
    { attribution: '© OpenStreetMap' }).addTo(pickMap);
  if (lat && lng) {
    pickMarker = L.marker([lat, lng]).addTo(pickMap);
  }
  pickMap.on('click', e => {
    const { lat: la, lng: lo } = e.latlng;
    document.getElementById('latInp').value = la.toFixed(7);
    document.getElementById('lngInp').value = lo.toFixed(7);
    if (pickMarker) pickMarker.remove();
    pickMarker = L.marker([la, lo]).addTo(pickMap);
    toast('Joylashuv belgilandi', 'ok');
  });
}

function detectGps() {
  const btn = document.getElementById('gpsBtn');
  if (btn) { btn.disabled = true; btn.innerHTML = '<span class="spin" style="width:12px;height:12px"></span> Aniqlanmoqda...'; }
  navigator.geolocation?.getCurrentPosition(p => {
    const la = p.coords.latitude, lo = p.coords.longitude;
    document.getElementById('latInp').value = la.toFixed(7);
    document.getElementById('lngInp').value = lo.toFixed(7);
    if (pickMap) {
      pickMap.setView([la, lo], 16);
      if (pickMarker) pickMarker.remove();
      pickMarker = L.marker([la, lo]).addTo(pickMap);
    }
    if (btn) { btn.disabled = false; btn.innerHTML = '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3"/></svg> Aniqlandi'; }
    toast('GPS aniqlandi!', 'ok');
  }, () => {
    if (btn) { btn.disabled = false; btn.innerHTML = 'GPS aniqlash'; }
    toast('GPS aniqlanmadi', 'err');
  });
}

/* ── Image slider ───────────────────────────────────────────── */
function initSlider() {
  const slider = document.getElementById('imgSlider');
  if (!slider) return;
  const dots = [...document.querySelectorAll('.dot')];
  slider.addEventListener('scroll', () => {
    const idx = Math.round(slider.scrollLeft / slider.offsetWidth);
    dots.forEach((d, i) => d.classList.toggle('on', i === idx));
  }, { passive: true });
}
function goSlide(i) {
  const slider = document.getElementById('imgSlider');
  if (!slider) return;
  slider.children[i]?.scrollIntoView({ behavior: 'smooth', inline: 'start', block: 'nearest' });
  document.querySelectorAll('.dot').forEach((d, j) => d.classList.toggle('on', j === i));
}

/* ── Auto-dismiss alerts ────────────────────────────────────── */
function initAlerts() {
  document.querySelectorAll('.alert[data-ad]').forEach(el => {
    setTimeout(() => {
      el.style.transition = 'opacity .25s, transform .25s';
      el.style.opacity = '0'; el.style.transform = 'translateY(-6px)';
      setTimeout(() => el.remove(), 260);
    }, 4000);
  });
}

/* ── Spec checkbox highlight ────────────────────────────────── */
function initSpecBoxes() {
  document.querySelectorAll('.spec-lbl input').forEach(inp => {
    inp.addEventListener('change', () => {
      // CSS :has() handles visual, but fallback for older browsers:
      const lbl = inp.closest('.spec-lbl');
      if (!CSS.supports('selector(:has(*))')) {
        lbl.style.borderColor = inp.checked ? 'var(--pr)' : '';
        lbl.style.background  = inp.checked ? 'rgba(192,57,43,.05)' : '';
        lbl.style.color       = inp.checked ? 'var(--pr)' : '';
      }
    });
  });
}

/* ── 24h toggle ─────────────────────────────────────────────── */
function init24h() {
  const cb = document.getElementById('is24h');
  const tw = document.getElementById('timeWrap');
  if (!cb || !tw) return;
  const upd = () => tw.style.opacity = cb.checked ? '.3' : '1';
  cb.addEventListener('change', upd); upd();
}

/* ── Boot ───────────────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', () => {
  Theme.init();
  initOtp();
  startCountdown();
  initStars();
  initAlerts();
  initSpecBoxes();
  init24h();
  initSlider();
});
