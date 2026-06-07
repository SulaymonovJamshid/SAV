    <?php ob_start();
    /* Helper: star HTML */
    function renderStars(float $avg): string {
        $r=round($avg); $h='<span class="stars">';
        for($i=1;$i<=5;$i++) $h.='<span class="'.($i<=$r?'s-on':'s-off').'">★</span>';
        return $h.'</span>';
    }
    ?>
    <div style="max-width:1300px;margin:0 auto;padding:20px 24px">
    <div style="display:grid;grid-template-columns:230px 1fr;gap:20px;align-items:start">

    <!-- FILTER SIDEBAR -->
    <aside>
    <div class="filter-box">
        <div class="filter-ttl">Qidirish va Filtrlash</div>
        <form method="GET" action="<?= APP_URL ?>/services" id="ff">

        <div class="fg">
        <label class="lbl">Kalit so'z</label>
        <input type="text" name="search" class="inp" placeholder="Servis nomi..." value="<?= e($f['search']) ?>">
        </div>

        <div class="fg">
        <label class="lbl">Viloyat</label>
        <select name="viloyat" class="sel" id="vilSel" onchange="loadTumans(this.value,'<?= APP_URL ?>','<?= e($f['tuman']) ?>')">
        <option value="">Barcha viloyatlar</option>
        <?php foreach(viloyatlar() as $k=>$v): ?><option value="<?= e($k) ?>" <?= $f['viloyat']===$k?'selected':'' ?>><?= e($v) ?></option><?php endforeach; ?>
        </select>
        </div>

        <div class="fg">
        <label class="lbl">Tuman</label>
        <select name="tuman" class="sel" id="tumanSel">
        <option value="">Barcha tumanlar</option>
        <?php if($f['tuman']): ?><option value="<?= e($f['tuman']) ?>" selected><?= e($f['tuman']) ?></option><?php endif; ?>
        </select>
        </div>

        <div class="fg">
        <label class="lbl">Mutaxassislik</label>
        <select name="spec" class="sel">
        <option value="">Barchasi</option>
        <?php foreach(specializations() as $s): ?><option value="<?= e($s) ?>" <?= $f['spec']===$s?'selected':'' ?>><?= t('s_'.$s) ?></option><?php endforeach; ?>
        </select>
        </div>

        <div class="fg">
        <label class="lbl">Min reyting</label>
        <select name="rating_min" class="sel">
        <option value="">Barchasi</option>
        <?php foreach([1,2,3,4,5] as $r): ?><option value="<?= $r ?>" <?= $f['rating_min']==(string)$r?'selected':'' ?>><?= $r ?>+ yulduz</option><?php endforeach; ?>
        </select>
        </div>

        <div class="fg">
        <label class="lbl">Tartiblash</label>
        <select name="sort" class="sel">
        <option value="newest" <?= $f['sort']==='newest'?'selected':'' ?>>Yangiligi bo'yicha</option>
        <option value="rating" <?= $f['sort']==='rating'?'selected':'' ?>>Reyting bo'yicha</option>
        <option value="price"  <?= $f['sort']==='price'?'selected':'' ?>>Narx bo'yicha</option>
        </select>
        </div>

        <button type="submit" class="btn btn-pr btn-bl">Qidirish</button>
        <a href="<?= APP_URL ?>/services" class="btn btn-ghost btn-bl mt2" style="font-size:.8rem">Tozalash</a>
        </form>

        <div class="hr"></div>
        <button class="btn btn-ghost btn-bl" id="nearBtn" onclick="openNearby('<?= APP_URL ?>')">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3"/></svg>
        Yaqin servislar
        </button>
    </div>
    </aside>

    <!-- MAIN -->
    <div>
    <div class="sh" style="margin-bottom:16px">
        <div>
        <h1 style="font-weight:700;font-size:1.15rem;color:var(--text);letter-spacing:-.02em">Servislar</h1>
        <p style="font-size:.8rem;color:var(--muted);margin-top:2px"><?= $pag['total'] ?> ta natija</p>
        </div>
    </div>

    <?php if(empty($svcs)): ?>
    <div class="empty" style="background:var(--card);border:1px solid var(--bdr);border-radius:var(--r)">
        <div class="empty-ico">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        </div>
        <h3>Servis topilmadi</h3>
        <p style="font-size:.84rem">Filtrni o'zgartiring yoki boshqa so'z kiriting</p>
        <a href="<?= APP_URL ?>/services" class="btn btn-ghost mt3">Hammasini ko'rish</a>
    </div>
    <?php else: ?>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:14px">
        <?php foreach($svcs as $s):
        $imgs=$s['images']??[]; $cover=$imgs[0]['filename']??null; ?>
        <div class="card svc-card">
        <!-- Image -->
        <div style="position:relative">
        <?php if($cover): ?>
        <img src="<?= svc_img($cover) ?>" class="svc-img" alt="<?= e($s['name']) ?>" loading="lazy">
        <?php else: ?>
        <div class="svc-img-ph">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
        </div>
        <?php endif; ?>
        <?php if(user()['role']==='user'): ?>
        <button class="fav <?= in_array($s['id'],$favIds)?'on':'' ?>"
                onclick="toggleFav(this,<?= (int)$s['id'] ?>,'<?= APP_URL ?>')"
                style="position:absolute;top:8px;right:8px;background:var(--card);box-shadow:var(--sh1)">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
        </button>
        <?php endif; ?>
        <?php if(!$s['is_approved']): ?>
        <div style="position:absolute;top:8px;left:8px"><span class="badge b-warn">Kutilmoqda</span></div>
        <?php endif; ?>
        </div>

        <!-- Body -->
        <div class="svc-body">
        <h3 class="svc-name"><?= e($s['name']) ?></h3>
        <p class="svc-addr" style="display:flex;align-items:center;gap:4px">
        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
        <?= e(viloyatlar()[$s['viloyat']]??$s['viloyat']) ?><?= $s['tuman']?', '.e($s['tuman']):'' ?>
        </p>
        <div class="tags">
        <?php foreach(array_slice($s['specializations'],0,3) as $sp): ?>
            <span class="tag"><?= t('s_'.$sp) ?></span>
        <?php endforeach; ?>
        <?php if(count($s['specializations'])>3): ?><span class="tag">+<?= count($s['specializations'])-3 ?></span><?php endif; ?>
        </div>
        <div style="display:flex;align-items:center;gap:6px">
        <?= renderStars((float)$s['avg_rating']) ?>
        <span style="font-size:.77rem;color:var(--muted)"><?= round((float)$s['avg_rating'],1) ?> (<?= (int)$s['review_count'] ?>)</span>
        <?php if(isset($s['km'])): ?><span class="dist"><?= $s['km'] ?> km</span><?php endif; ?>
        </div>
        </div>

        <!-- Footer -->
        <div class="svc-foot">
        <div>
        <?php if($s['price_from']||$s['price_to']): ?>
            <div class="price"><?= number_format((int)$s['price_from'],0,'',' ') ?> — <?= number_format((int)$s['price_to'],0,'',' ') ?> so'm</div>
        <?php else: ?><div style="font-size:.78rem;color:var(--muted)">Narx kelishiladi</div><?php endif; ?>
        <div style="font-size:.72rem;color:<?= $s['is_24h']?'var(--ok)':'var(--muted)' ?>;margin-top:2px;font-weight:500">
            <?= $s['is_24h']?'24/7':substr($s['work_start'],0,5).'–'.substr($s['work_end'],0,5) ?>
        </div>
        </div>
        <a href="<?= APP_URL ?>/services/<?= (int)$s['id'] ?>" class="btn btn-pr btn-sm">Ko'rish</a>
        </div>
        </div>
        <?php endforeach; ?>
    </div>

    <?php if($pag['pages']>1): ?>
    <div class="pag">
        <?php if($pag['page']>1): ?><a href="?<?= http_build_query(array_merge($_GET,['page'=>$pag['page']-1])) ?>">←</a><?php endif; ?>
        <?php for($p=max(1,$pag['page']-2);$p<=min($pag['pages'],$pag['page']+2);$p++): ?>
        <?= $p===$pag['page']?"<span class=\"on\">$p</span>":"<a href=\"?".http_build_query(array_merge($_GET,['page'=>$p]))."\">$p</a>" ?>
        <?php endfor; ?>
        <?php if($pag['page']<$pag['pages']): ?><a href="?<?= http_build_query(array_merge($_GET,['page'=>$pag['page']+1])) ?>">→</a><?php endif; ?>
    </div>
    <?php endif; ?>
    <?php endif; ?>
    </div>
    </div>
    </div>

    <!-- Nearby Modal -->
    <div id="nearModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.55);z-index:3000;align-items:center;justify-content:center;padding:20px">
    <div style="background:var(--card);border-radius:var(--r);padding:20px;width:100%;max-width:680px;position:relative;max-height:90vh;overflow-y:auto;box-shadow:var(--sh3)">
    <div class="sh mb3">
    <span class="sh-t">Yaqin servislar</span>
    <button onclick="document.getElementById('nearModal').style.display='none'" style="background:none;border:none;cursor:pointer;color:var(--muted);font-size:1.1rem;line-height:1">✕</button>
    </div>
    <div id="nearMap" style="height:320px;border-radius:var(--rs);border:1px solid var(--bdr);overflow:hidden"></div>
    <div id="nearList" style="margin-top:12px"></div>
    </div>
    </div>

    <?php
    $extraJs='<script>document.addEventListener("DOMContentLoaded",()=>{const v="'.e($f['viloyat']).'",t="'.e($f['tuman']).'";if(v)loadTumans(v,"'.APP_URL.'",t);});</script>';
    $pageContent=ob_get_clean();
    ?>
