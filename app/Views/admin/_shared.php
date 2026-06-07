<?php
/* ── Admin shared sidebar ─────────────────────────────────── */
function adminSidebar(string $active=''): string {
    $base = APP_URL;
    $items = [
        ['dashboard','grid','Dashboard',"$base/admin"],
        ['users','users','Foydalanuvchilar',"$base/admin/users"],
        ['services','tool','Servislar',"$base/admin/services"],
        ['reviews','chat','Sharhlar',"$base/admin/reviews"],
    ];
    $svgs=[
        'grid'=>'<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>',
        'users'=>'<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
        'tool'=>'<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>',
        'chat'=>'<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>',
        'logout'=>'<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>',
    ];
    $html='<aside class="sidebar"><div class="sb-label">Admin Panel</div>';
    foreach($items as [$k,$ico,$lbl,$url]) {
        $on=$active===$k?'on':'';
        $html.="<a href=\"$url\" class=\"$on\">{$svgs[$ico]} $lbl</a>";
    }
    $html.='<div class="sb-label" style="margin-top:auto">Hisob</div>';
    $html.="<a href=\"$base/services\">{$svgs['grid']} Saytga o'tish</a>";
    $html.="<a href=\"$base/logout\" style=\"color:var(--pr)!important\">{$svgs['logout']} Chiqish</a>";
    $html.='</aside>';
    return $html;
}
?>
