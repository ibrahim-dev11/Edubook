@extends('portal.layout')
@section('title', 'EduBook — پلاتفۆرمی پەروەردەیی کوردستان')

@section('styles')
<style>
/* ════════════ ANIMATIONS ════════════ */
@keyframes fadeUp { from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:none} }
@keyframes glow   { 0%,100%{opacity:.3}50%{opacity:.6} }
@keyframes ping   { 75%,100%{transform:scale(2.2);opacity:0} }

.fade-up  { animation:fadeUp .7s cubic-bezier(.16,1,.3,1) both; }
.delay-1  { animation-delay:.1s; } .delay-2 { animation-delay:.2s; }
.delay-3  { animation-delay:.3s; } .delay-4 { animation-delay:.4s; }

.sr { opacity:0;transform:translateY(16px);transition:opacity .6s cubic-bezier(.16,1,.3,1),transform .6s cubic-bezier(.16,1,.3,1); }
.sr.in { opacity:1;transform:none; }
.sr.d1{transition-delay:.08s}.sr.d2{transition-delay:.16s}.sr.d3{transition-delay:.24s}
.sr.d4{transition-delay:.32s}.sr.d5{transition-delay:.4s}.sr.d6{transition-delay:.48s}

/* ════════════ HERO ════════════ */
.hero {
    position:relative; padding:10rem 1.5rem 7rem;
    text-align:center; overflow:hidden;
    background:var(--bg);
}
/* gold radial glow */
.hero::before {
    content:''; position:absolute; inset:0; pointer-events:none;
    background:
        radial-gradient(ellipse 70% 50% at 50% -10%, rgba(226,176,66,.14) 0%, transparent 65%),
        radial-gradient(ellipse 40% 40% at 10% 95%, rgba(226,176,66,.04) 0%, transparent 60%),
        radial-gradient(ellipse 40% 40% at 90% 90%, rgba(226,176,66,.04) 0%, transparent 60%);
}
/* grid pattern */
.hero::after {
    content:''; position:absolute; inset:0; pointer-events:none;
    background-image:
        linear-gradient(rgba(226,176,66,.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(226,176,66,.03) 1px, transparent 1px);
    background-size:54px 54px;
    mask-image:radial-gradient(ellipse 80% 70% at 50% 50%, black 20%, transparent 100%);
}

.hero-inner { position:relative;z-index:1;max-width:760px;margin:0 auto; }

.hero-badge {
    display:inline-flex;align-items:center;gap:9px;
    background:rgba(226,176,66,.06); color:var(--gold-lt);
    border:1px solid var(--border2); border-radius:50px;
    padding:6px 18px; font-size:.8rem; font-weight:800;
    margin-bottom:2rem; letter-spacing:.3px;
    box-shadow: var(--shadow-sm);
}
.badge-dot { position:relative;width:8px;height:8px; }
.badge-dot span { display:block;width:8px;height:8px;border-radius:50%;background:var(--gold-lt); }
.badge-dot::after {
    content:'';position:absolute;inset:0;border-radius:50%;
    background:rgba(226,176,66,.6); animation:ping 2s cubic-bezier(0,0,.2,1) infinite;
}

.hero-title {
    font-size:clamp(2.2rem,6vw,4rem);
    font-weight:900;line-height:1.2;color:var(--txt);
    margin-bottom:1.5rem;letter-spacing:-1px;
}
.hero-title .hl {
    background:linear-gradient(135deg, var(--gold-lt) 20%, var(--gold) 60%, var(--gold-dk) 100%);
    -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
    text-shadow: 0 0 40px rgba(226, 176, 66, 0.1);
}

.hero-sub {
    font-size:1.1rem;color:var(--txt2);
    max-width:520px;margin:0 auto 2.75rem;line-height:1.9;
}

.hero-btns { display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;margin-bottom:4.5rem; }

.hbtn-primary {
    display:inline-flex;align-items:center;gap:8px;
    padding:14px 34px;border-radius:14px;
    background:var(--grad);color:#080c14;
    font-family:inherit;font-size:1rem;font-weight:800;
    text-decoration:none;border:1px solid var(--border2);
    box-shadow: 0 4px 18px rgba(226, 176, 66, 0.25);
    transition:all .25s cubic-bezier(0.4,0,0.2,1);
}
.hbtn-primary:hover { transform:translateY(-2px); box-shadow: 0 8px 24px rgba(226, 176, 66, 0.4); }

.hbtn-ghost {
    display:inline-flex;align-items:center;gap:8px;
    padding:14px 34px;border-radius:14px;
    background:rgba(255, 255, 255, 0.02);color:var(--txt2);
    font-family:inherit;font-size:1rem;font-weight:700;
    text-decoration:none;border:1px solid var(--border);
    transition:all .25s cubic-bezier(0.4,0,0.2,1);
}
.hbtn-ghost:hover { border-color:var(--gold-lt);color:var(--gold-lt);background:rgba(226, 176, 66, 0.05); transform:translateY(-2px); }

.stats-bar {
    display:flex;max-width:480px;margin:0 auto;
    border:1px solid var(--border);border-radius:20px;overflow:hidden;
    background:rgba(15, 23, 42, 0.4);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    box-shadow: var(--shadow-md);
}
.stat-cell { flex:1;padding:1.5rem .75rem;text-align:center; }
.stat-cell + .stat-cell { border-right:1px solid var(--border); }
.stat-n {
    font-size:1.75rem;font-weight:800;line-height:1;
    color:var(--gold-lt);
    text-shadow: 0 0 15px rgba(226, 176, 66, 0.1);
}
.stat-l { font-size:.78rem;color:var(--txt3);margin-top:7px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px; }

/* ════════════ SECTIONS ════════════ */
.sec     { padding:6rem 1.5rem;max-width:1120px;margin:0 auto; }
.sec-alt { border-top:1px solid var(--border);border-bottom:1px solid var(--border);background:rgba(15, 23, 42, 0.25); }
.sec-alt-inner { padding:6rem 1.5rem;max-width:1120px;margin:0 auto; }

.sec-head { margin-bottom:3.5rem; }
.sec-tag {
    display:inline-flex;align-items:center;gap:8px;
    font-size:.75rem;font-weight:800;letter-spacing:2px;
    text-transform:uppercase;color:var(--gold-lt);margin-bottom:.75rem;
}
.sec-tag::before { content:'';width:18px;height:2px;border-radius:2px;background:var(--gold-lt); }
.sec-title { font-size:clamp(1.6rem,3.5vw,2.35rem);font-weight:900;color:var(--txt);line-height:1.35; }
.sec-sub   { color:var(--txt2);margin-top:.6rem;font-size:.98rem;line-height:1.9; }

/* ════════════ FEATURE CARDS ════════════ */
.feat-grid { display:grid;grid-template-columns:repeat(3,1fr);gap:1.25rem; }
.feat-card {
    background:rgba(15, 23, 42, 0.45);
    border:1px solid var(--border);border-radius:20px;
    padding:2.25rem 1.75rem;
    box-shadow: var(--shadow-sm);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    transition:border-color .3s,transform .4s cubic-bezier(.16,1,.3,1),box-shadow .3s;
}
.feat-card:hover { border-color:var(--border2);transform:translateY(-5px); box-shadow: 0 12px 24px rgba(0,0,0,0.3), var(--shadow-glow); }
.feat-icon {
    width:52px;height:52px;border-radius:14px;
    background:rgba(226,176,66,.07);border:1px solid var(--border);
    display:flex;align-items:center;justify-content:center;
    font-size:1.5rem;margin-bottom:1.25rem;
    box-shadow: var(--shadow-sm);
}
.feat-name { font-size:1.05rem;font-weight:800;color:var(--txt);margin-bottom:.5rem; }
.feat-desc { font-size:.87rem;color:var(--txt2);line-height:1.85; }

/* ════════════ STEPS ════════════ */
.steps-grid { display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;position:relative; }
.steps-grid::before {
    content:'';position:absolute;top:35px;right:calc(16.6% + 14px);left:calc(16.6% + 14px);
    height:1px;background:linear-gradient(90deg,transparent,var(--border2),transparent);
}
.step-card {
    background:rgba(15, 23, 42, 0.3);border:1px solid var(--border);border-radius:20px;
    padding:2.25rem 1.75rem;text-align:center;
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    transition:border-color .3s,transform .4s cubic-bezier(.16,1,.3,1),box-shadow .3s;
}
.step-card:hover { border-color:var(--border2);transform:translateY(-5px); box-shadow: 0 12px 24px rgba(0,0,0,0.3); }
.step-num {
    width:48px;height:48px;border-radius:50%;
    background:var(--grad);color:#080c14;
    font-size:1.15rem;font-weight:900;
    display:flex;align-items:center;justify-content:center;
    margin:0 auto 1.25rem;position:relative;z-index:1;
    border:2.5px solid rgba(226,176,66,.25);
    box-shadow: var(--shadow-sm);
}
.step-name { font-size:1.05rem;font-weight:800;color:var(--txt);margin-bottom:.5rem; }
.step-desc { font-size:.87rem;color:var(--txt2);line-height:1.85; }

/* ════════════ CTA ════════════ */
.cta-section { padding:6rem 1.5rem; }
.cta-box {
    max-width:760px;margin:0 auto;
    background:rgba(15, 23, 42, 0.6);
    border:1px solid var(--border2);border-radius:28px;
    padding:5rem 3rem;text-align:center;
    position:relative;overflow:hidden;
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    box-shadow: var(--shadow-lg);
}
.cta-box::before {
    content:'';position:absolute;inset:0;pointer-events:none;
    background:
        radial-gradient(ellipse 70% 60% at 50% 0%, rgba(226,176,66,.12), transparent),
        radial-gradient(ellipse 40% 40% at 90% 100%, rgba(226,176,66,.05), transparent);
}
.cta-inner { position:relative;z-index:1; }
.cta-icon  { font-size:2.8rem;display:block;margin-bottom:1rem; }
.cta-title { font-size:clamp(1.5rem,3.5vw,2.15rem);font-weight:900;color:var(--txt);margin-bottom:.8rem; }
.cta-sub   { color:var(--txt2);font-size:1rem;line-height:1.85;margin-bottom:2.25rem; }
.btn-cta {
    display:inline-flex;align-items:center;gap:8px;
    background:var(--grad);color:#080c14;
    padding:14px 36px;border-radius:14px;
    font-family:inherit;font-size:1rem;font-weight:800;
    text-decoration:none;border:1px solid var(--border2);
    box-shadow: 0 4px 18px rgba(226, 176, 66, 0.25);
    transition:all .25s cubic-bezier(0.4,0,0.2,1);
}
.btn-cta:hover { transform:translateY(-2px); box-shadow: 0 8px 24px rgba(226, 176, 66, 0.4); }

/* ════════════ FOOTER ════════════ */
.site-footer {
    border-top:1px solid var(--border);
    padding:2.5rem 1.5rem;text-align:center;
    background:rgba(8, 12, 20, 0.95);color:var(--txt3);font-size:.85rem;
}
.site-footer a { color:var(--gold-lt);text-decoration:none;font-weight:700; }
.site-footer a:hover { color:var(--gold); }

/* ════════════ RESPONSIVE ════════════ */
@media (max-width:900px) { .feat-grid{grid-template-columns:repeat(2,1fr);} .steps-grid{grid-template-columns:1fr;} .steps-grid::before{display:none;} }
@media (max-width:580px) { .feat-grid{grid-template-columns:1fr;} .hbtn-primary,.hbtn-ghost{width:100%;justify-content:center;} .hero-btns{flex-direction:column;} .cta-box{padding:3.5rem 1.5rem;border-radius:20px;} .hero{padding:8rem 1.25rem 5rem;} }
</style>
@endsection

@section('content')

{{-- ═══════ HERO ═══════ --}}
<section class="hero">
    <div class="hero-inner">
        <div class="hero-badge fade-up">
            <div class="badge-dot"><span></span></div>
            پلاتفۆرمی ژمارە یەکی پەروەردەیی کوردستان
        </div>

        <h1 class="hero-title fade-up delay-1">
            دامەزراوەکەت<br>
            <span class="hl">بناسێنە</span> لەگەڵ EduBook
        </h1>

        <p class="hero-sub fade-up delay-2">
            پلاتفۆرمی پەروەردەیی بۆ خوێندنگا، کۆلێژ و ناوەندەکان —
            تۆمار بکە، بڵاوبکەرەوە
        </p>

        <div class="hero-btns fade-up delay-3">
            <a href="{{ route('portal.register') }}" class="hbtn-primary">🎓 تۆمارکردنی بەخۆڕایی</a>
            <a href="{{ route('portal.login') }}"    class="hbtn-ghost">چوونەژوورەوە ←</a>
        </div>

        <div class="stats-bar fade-up delay-4">
            <div class="stat-cell">
                <div class="stat-n">٥٠٠+</div>
                <div class="stat-l">دامەزراوە</div>
            </div>
            <div class="stat-cell">
                <div class="stat-n">١٢٠+</div>
                <div class="stat-l">شار</div>
            </div>
            <div class="stat-cell">
                <div class="stat-n">بەخۆڕایی</div>
                <div class="stat-l">تەواو</div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════ FEATURES ═══════ --}}
<div class="sec">
    <div class="sec-head">
        <div class="sec-tag sr">تایبەتمەندییەکان</div>
        <h2 class="sec-title sr d1">هەموو ئەوەی دامەزراوەکەت پێویستیەتی</h2>
        <p  class="sec-sub  sr d2">ئامرازی کامل بۆ بەڕێوەبردنی ئۆنلاینی دامەزراوەکەت</p>
    </div>
    <div class="feat-grid">
        <div class="feat-card sr d1">
            <div class="feat-icon">🏫</div>
            <div class="feat-name">پرۆفایلی دامەزراوە</div>
            <div class="feat-desc">ناو، جۆر، ناونیشان، پەیوەندی و لینکی کۆمەڵایەتی تۆمار بکە بە شێوەیەکی پیشەیی</div>
        </div>
        <div class="feat-card sr d2">
            <div class="feat-icon">📝</div>
            <div class="feat-name">بەڕێوەبردنی پۆست</div>
            <div class="feat-desc">هەواڵ، ئیلان و بابەتەکانت بڵاوبکەرەوە بۆ خوێندکارانت لە هەر کاتێک</div>
        </div>
        <div class="feat-card sr d3">
            <div class="feat-icon">📱</div>
            <div class="feat-name">ئەپی موبایل</div>
            <div class="feat-desc">دامەزراوەکەت لە ئەپی EduBook دیار دەبێت بۆ ملیۆنان خوێندکاری کوردستان</div>
        </div>
        <div class="feat-card sr d4">
            <div class="feat-icon">✅</div>
            <div class="feat-name">سیستەمی پەسەندکردن</div>
            <div class="feat-desc">تیمی ئەدمین زانیارییەکانت پشتڕاست دەکاتەوە پێش دیارکردن لە ئەپەکەدا</div>
        </div>
        <div class="feat-card sr d5">
            <div class="feat-icon">🔍</div>
            <div class="feat-name">گەڕان و دیتن</div>
            <div class="feat-desc">خوێندکاران بە ئاسانی دامەزراوەکەت دۆزنەوە لە نێو گەڕانی زیرەک</div>
        </div>
        <div class="feat-card sr d6">
            <div class="feat-icon">🆓</div>
            <div class="feat-name">بەخۆڕایی تەواو</div>
            <div class="feat-desc">هیچ کرێیەک نییە — تۆمارکردن و بەکارهێنان بەتەواوی بەخۆڕایی و بەردەوامە</div>
        </div>
    </div>
</div>

{{-- ═══════ HOW IT WORKS ═══════ --}}
<div class="sec-alt">
    <div class="sec-alt-inner">
        <div class="sec-head">
            <div class="sec-tag sr">چۆن کار دەکات</div>
            <h2 class="sec-title sr d1">سێ هەنگاوی سادە</h2>
            <p  class="sec-sub  sr d2">لە کەمتر لە ٥ خولەک دامەزراوەکەت ئۆنلاین بکە</p>
        </div>
        <div class="steps-grid">
            <div class="step-card sr d1">
                <div class="step-num">١</div>
                <div class="step-name">هەژمار دروستبکە</div>
                <div class="step-desc">ناو، ئیمەیڵ و وشەی نهێنیت داخڵ بکە — تەنها ٣٠ چرکە دەکات</div>
            </div>
            <div class="step-card sr d2">
                <div class="step-num">٢</div>
                <div class="step-name">دامەزراوەکەت تۆمار بکە</div>
                <div class="step-desc">زانیارییەکانی دامەزراوەکەت پڕ بکەرەوە و پرۆفایلت ئامادە بکە</div>
            </div>
            <div class="step-card sr d3">
                <div class="step-num">٣</div>
                <div class="step-name">بڵاوکردنەوە دەستبکە</div>
                <div class="step-desc">هەواڵ، ئیلان و پۆستەکانت بڵاوبکەرەوە — خوێندکارانت لە ئەپەکەدا دەتبیننەوە</div>
            </div>
        </div>
    </div>
</div>

{{-- ═══════ CTA ═══════ --}}
<div class="cta-section">
    <div class="cta-box sr">
        <div class="cta-inner">
            <span class="cta-icon">🎓</span>
            <h2 class="cta-title">ئامادەیت؟ ئێستا دەستپێبکە!</h2>
            <p class="cta-sub">بە بەخۆڕایی تۆمار بکە و دامەزراوەکەت لە ئەپی EduBook<br>دیار بکە بۆ ملیۆنان خوێندکاری کوردستان</p>
            <a href="{{ route('portal.register') }}" class="btn-cta">🎓 تۆمارکردنی بەخۆڕایی</a>
        </div>
    </div>
</div>

{{-- ═══════ FOOTER ═══════ --}}
<footer class="site-footer">
    <p>© {{ date('Y') }} <strong style="color:var(--gold)">EduBook</strong> — هەموو مافەکان پارێزراون &nbsp;·&nbsp;
       <a href="{{ route('portal.login') }}">چوونەژوورەوە</a></p>
</footer>

@endsection

@section('scripts')
<script>
(function(){
    const io = new IntersectionObserver(entries=>{
        entries.forEach(e=>{ if(e.isIntersecting){ e.target.classList.add('in'); io.unobserve(e.target); } });
    }, { threshold:0.1 });
    document.querySelectorAll('.sr').forEach(el=>io.observe(el));
})();
</script>
@endsection
