<?php
require_once __DIR__ . '/includes/functions.php';

$profile   = getProfile();
$skills    = getSkills();
$projects  = getProjects();
$featured  = getProjects(true);
$experience = getExperience();
$education = getEducation();
$certs     = getCertifications();

$skillCategoryIcons = [
    'Analytics'       => 'bi-graph-up',
    'Visualization'   => 'bi-pie-chart',
    'Tools'           => 'bi-tools',
    'Machine Learning'=> 'bi-cpu',
];

$projectCategoryColors = [
    'Machine Learning'   => '#00D4FF',
    'Business Intelligence' => '#7C3AED',
    'Analytics'          => '#10B981',
    'Public Data'        => '#F59E0B',
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= SITE_TITLE ?></title>
    <meta name="description" content="<?= SITE_DESCRIPTION ?>">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">

    <style>
        /* ── Design tokens ── */
        :root {
            --navy:       #0A1628;
            --navy-mid:   #1E3A5F;
            --navy-light: #2A4A72;
            --cyan:       #00D4FF;
            --cyan-dim:   rgba(0,212,255,.12);
            --slate:      #8BA4C0;
            --off-white:  #F0F4F8;
            --body-bg:    #060E1C;
            --sidebar-w:  280px;
            --transition: .25s ease;
        }

        /* ── Base ── */
        *, *::before, *::after { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            background: var(--body-bg);
            color: var(--off-white);
            font-family: 'Inter', sans-serif;
            font-weight: 300;
            line-height: 1.7;
            min-height: 100vh;
        }
        h1,h2,h3,h4,h5,h6 {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 600;
        }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: var(--navy); }
        ::-webkit-scrollbar-thumb { background: var(--navy-light); border-radius: 3px; }

        /* ── Sidebar ── */
        #sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: var(--navy);
            border-right: 1px solid rgba(0,212,255,.1);
            display: flex;
            flex-direction: column;
            padding: 2.5rem 1.75rem;
            overflow-y: auto;
            z-index: 100;
        }
        .sidebar-avatar {
            width: 80px; height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--cyan), var(--navy-light));
            display: flex; align-items: center; justify-content: center;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 1rem;
            flex-shrink: 0;
        }
        .sidebar-name {
            font-size: .95rem;
            font-weight: 600;
            line-height: 1.3;
            color: var(--off-white);
            font-family: 'Space Grotesk', sans-serif;
            margin-bottom: .25rem;
        }
        .sidebar-role {
            font-size: .75rem;
            color: var(--cyan);
            letter-spacing: .04em;
            margin-bottom: 1.5rem;
        }
        .sidebar-divider {
            height: 1px;
            background: linear-gradient(to right, var(--cyan), transparent);
            margin-bottom: 1.5rem;
            opacity: .3;
        }
        .sidebar-nav { list-style: none; padding: 0; margin: 0; flex-grow: 1; }
        .sidebar-nav li { margin-bottom: .25rem; }
        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: .6rem;
            padding: .55rem .75rem;
            border-radius: 6px;
            color: var(--slate);
            text-decoration: none;
            font-size: .82rem;
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 500;
            transition: all var(--transition);
        }
        .sidebar-nav a i { font-size: .9rem; }
        .sidebar-nav a:hover,
        .sidebar-nav a.active {
            color: var(--cyan);
            background: var(--cyan-dim);
        }
        .sidebar-contact { margin-top: auto; padding-top: 1.5rem; }
        .sidebar-contact a {
            display: flex; align-items: center; gap: .5rem;
            color: var(--slate); font-size: .75rem;
            text-decoration: none; margin-bottom: .4rem;
            transition: color var(--transition);
        }
        .sidebar-contact a:hover { color: var(--cyan); }

        /* ── Main content ── */
        #main {
            margin-left: var(--sidebar-w);
            padding: 3rem 3.5rem;
            max-width: 960px;
        }

        /* ── Section ── */
        .section { margin-bottom: 5rem; }
        .section-label {
            display: inline-block;
            font-family: 'Space Grotesk', sans-serif;
            font-size: .7rem;
            font-weight: 600;
            letter-spacing: .12em;
            color: var(--cyan);
            background: var(--cyan-dim);
            padding: .25rem .75rem;
            border-radius: 3px;
            margin-bottom: .75rem;
        }
        .section-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--off-white);
            line-height: 1.2;
            margin-bottom: 2rem;
        }
        .section-title span { color: var(--cyan); }

        /* ── Hero ── */
        #hero { padding-top: .5rem; }
        .hero-greeting {
            font-size: .85rem;
            color: var(--slate);
            font-family: 'Space Grotesk', sans-serif;
            margin-bottom: .5rem;
        }
        .hero-name {
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 700;
            line-height: 1.1;
            margin-bottom: .75rem;
        }
        .hero-tagline {
            font-size: 1.05rem;
            color: var(--slate);
            margin-bottom: 2rem;
            max-width: 540px;
        }
        .hero-stats {
            display: flex;
            gap: 2.5rem;
            margin-bottom: 2.5rem;
            flex-wrap: wrap;
        }
        .hero-stat-num {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--cyan);
            line-height: 1;
        }
        .hero-stat-label {
            font-size: .75rem;
            color: var(--slate);
            margin-top: .2rem;
        }
        .hero-chart {
            height: 3px;
            background: linear-gradient(to right, var(--cyan), var(--navy-light), transparent);
            margin-bottom: 2.5rem;
            border-radius: 2px;
        }
        .btn-primary-cyan {
            background: var(--cyan);
            color: var(--navy);
            border: none;
            padding: .65rem 1.5rem;
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 600;
            font-size: .85rem;
            border-radius: 6px;
            text-decoration: none;
            display: inline-flex; align-items: center; gap: .4rem;
            transition: all var(--transition);
        }
        .btn-primary-cyan:hover { background: #00b8e6; color: var(--navy); transform: translateY(-1px); }
        .btn-outline-cyan {
            border: 1px solid var(--cyan);
            color: var(--cyan);
            padding: .65rem 1.5rem;
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 600;
            font-size: .85rem;
            border-radius: 6px;
            text-decoration: none;
            display: inline-flex; align-items: center; gap: .4rem;
            transition: all var(--transition);
        }
        .btn-outline-cyan:hover { background: var(--cyan-dim); color: var(--cyan); }

        /* ── About ── */
        .about-text {
            font-size: 1rem;
            line-height: 1.85;
            color: #B8CCD8;
            max-width: 62ch;
        }

        /* ── Skills ── */
        .skill-category-title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: .8rem;
            font-weight: 600;
            color: var(--cyan);
            text-transform: uppercase;
            letter-spacing: .08em;
            margin-bottom: 1rem;
            display: flex; align-items: center; gap: .5rem;
        }
        .skill-item { margin-bottom: 1rem; }
        .skill-name {
            font-size: .82rem;
            color: var(--off-white);
            display: flex; justify-content: space-between;
            margin-bottom: .35rem;
        }
        .skill-pct { color: var(--slate); font-size: .75rem; }
        .skill-bar-track {
            height: 4px;
            background: var(--navy-mid);
            border-radius: 2px;
            overflow: hidden;
        }
        .skill-bar-fill {
            height: 100%;
            background: linear-gradient(to right, var(--cyan), #7C3AED);
            border-radius: 2px;
            width: 0;
            transition: width 1s ease;
        }

        /* ── Projects ── */
        .project-card {
            background: var(--navy);
            border: 1px solid rgba(0,212,255,.08);
            border-radius: 10px;
            padding: 1.75rem;
            margin-bottom: 1.25rem;
            transition: border-color var(--transition), transform var(--transition);
            position: relative;
            overflow: hidden;
        }
        .project-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 3px; height: 100%;
            border-radius: 2px 0 0 2px;
        }
        .project-card:hover {
            border-color: rgba(0,212,255,.25);
            transform: translateX(4px);
        }
        .project-title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            color: var(--off-white);
            margin-bottom: .5rem;
        }
        .project-desc {
            font-size: .85rem;
            color: var(--slate);
            margin-bottom: .85rem;
            line-height: 1.65;
        }
        .project-tools { margin-bottom: .75rem; }
        .badge-tool {
            display: inline-block;
            font-size: .7rem;
            font-family: 'Space Grotesk', sans-serif;
            background: rgba(0,212,255,.08);
            color: var(--cyan);
            border: 1px solid rgba(0,212,255,.2);
            padding: .2rem .55rem;
            border-radius: 4px;
            margin: .2rem .15rem .2rem 0;
        }
        .project-outcome {
            font-size: .78rem;
            color: #10B981;
            display: flex; align-items: center; gap: .4rem;
        }
        .project-category-badge {
            position: absolute;
            top: 1.2rem; right: 1.2rem;
            font-size: .65rem;
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 600;
            padding: .2rem .55rem;
            border-radius: 3px;
            background: rgba(255,255,255,.05);
            color: var(--slate);
        }

        /* ── Experience ── */
        .exp-item { position: relative; padding-left: 1.5rem; margin-bottom: 2.5rem; }
        .exp-item::before {
            content: '';
            position: absolute;
            left: 0; top: 6px;
            width: 8px; height: 8px;
            background: var(--cyan);
            border-radius: 50%;
        }
        .exp-item::after {
            content: '';
            position: absolute;
            left: 3.5px; top: 18px;
            width: 1px;
            height: calc(100% + 10px);
            background: linear-gradient(to bottom, var(--cyan-dim), transparent);
        }
        .exp-item:last-child::after { display: none; }
        .exp-role {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            color: var(--off-white);
        }
        .exp-company { color: var(--cyan); font-size: .85rem; margin-bottom: .25rem; }
        .exp-period {
            font-size: .75rem;
            color: var(--slate);
            margin-bottom: .6rem;
            display: flex; align-items: center; gap: .4rem;
        }
        .exp-desc { font-size: .85rem; color: #9BB0C4; line-height: 1.7; }

        /* ── Education & Certs ── */
        .edu-card, .cert-card {
            background: var(--navy);
            border: 1px solid rgba(0,212,255,.08);
            border-radius: 8px;
            padding: 1.25rem 1.5rem;
            margin-bottom: .75rem;
        }
        .edu-degree {
            font-family: 'Space Grotesk', sans-serif;
            font-size: .95rem;
            font-weight: 600;
        }
        .edu-institution { color: var(--cyan); font-size: .82rem; }
        .cert-name {
            font-family: 'Space Grotesk', sans-serif;
            font-size: .88rem;
            font-weight: 600;
        }
        .cert-issuer { color: var(--slate); font-size: .78rem; }
        .cert-icon { color: var(--cyan); font-size: 1.3rem; margin-right: .75rem; }

        /* ── Contact ── */
        .contact-item {
            display: flex; align-items: center; gap: 1rem;
            padding: 1rem 1.25rem;
            background: var(--navy);
            border: 1px solid rgba(0,212,255,.08);
            border-radius: 8px;
            margin-bottom: .75rem;
            text-decoration: none;
            color: var(--off-white);
            transition: border-color var(--transition);
        }
        .contact-item:hover { border-color: var(--cyan); color: var(--off-white); }
        .contact-item i { color: var(--cyan); font-size: 1.2rem; }
        .contact-label { font-size: .72rem; color: var(--slate); }
        .contact-value { font-size: .88rem; font-family: 'Space Grotesk', sans-serif; }

        /* ── Navbar mobile ── */
        #mobileNav {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 200;
            background: var(--navy);
            border-bottom: 1px solid rgba(0,212,255,.1);
            padding: .75rem 1.25rem;
        }
        .mobile-menu {
            position: fixed;
            inset: 0;
            background: var(--body-bg);
            z-index: 300;
            padding: 2rem;
            display: none;
            flex-direction: column;
            gap: 1rem;
        }
        .mobile-menu a {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.2rem;
            color: var(--off-white);
            text-decoration: none;
            padding: .75rem 0;
            border-bottom: 1px solid rgba(255,255,255,.05);
        }
        .mobile-menu.open { display: flex; }

        /* ── Responsive ── */
        @media (max-width: 900px) {
            #sidebar { display: none; }
            #mobileNav { display: flex; align-items: center; justify-content: space-between; }
            #main { margin-left: 0; padding: 5rem 1.5rem 2rem; }
        }
        @media (max-width: 576px) {
            .hero-name { font-size: 1.8rem; }
            .hero-stats { gap: 1.5rem; }
        }

        /* ── Animations ── */
        .fade-in {
            opacity: 0;
            transform: translateY(16px);
            transition: opacity .6s ease, transform .6s ease;
        }
        .fade-in.visible { opacity: 1; transform: translateY(0); }

        /* ── Footer ── */
        footer {
            margin-top: 5rem;
            padding: 2rem 0;
            border-top: 1px solid rgba(0,212,255,.08);
            color: var(--slate);
            font-size: .78rem;
        }
    </style>
</head>
<body>

<!-- Mobile Navbar -->
<nav id="mobileNav">
    <span class="fw-bold" style="font-family:'Space Grotesk',sans-serif;color:var(--cyan);font-size:.9rem;">
        <?= h($profile['name'] ?? 'Portfolio') ?>
    </span>
    <button onclick="toggleMenu()" style="background:none;border:none;color:var(--off-white);font-size:1.4rem;">
        <i class="bi bi-list" id="menuIcon"></i>
    </button>
</nav>

<!-- Mobile Menu Overlay -->
<div class="mobile-menu" id="mobileMenu">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <span style="font-family:'Space Grotesk',sans-serif;color:var(--cyan);">Menu</span>
        <button onclick="toggleMenu()" style="background:none;border:none;color:var(--off-white);font-size:1.4rem;"><i class="bi bi-x-lg"></i></button>
    </div>
    <?php foreach (['hero'=>'Beranda','about'=>'Tentang','skills'=>'Keahlian','projects'=>'Proyek','contact'=>'Kontak'] as $id => $label): ?>
    <a href="#<?= $id ?>" onclick="toggleMenu()"><?= $label ?></a>
    <?php endforeach; ?>
</div>

<!-- Sidebar -->
<aside id="sidebar">
    <div class="sidebar-avatar">DJ</div>
    <div class="sidebar-name"><?= h($profile['name'] ?? '') ?></div>
    <div class="sidebar-role">Data Analyst</div>
    <div class="sidebar-divider"></div>

    <ul class="sidebar-nav">
        <li><a href="#hero" class="active"><i class="bi bi-house"></i> Beranda</a></li>
        <li><a href="#about"><i class="bi bi-person"></i> Tentang Saya</a></li>
        <li><a href="#skills"><i class="bi bi-bar-chart"></i> Keahlian</a></li>
        <li><a href="#projects"><i class="bi bi-folder2-open"></i> Proyek</a></li>
        <li><a href="#contact"><i class="bi bi-envelope"></i> Kontak</a></li>
    </ul>

    <div class="sidebar-contact">
        <a href="mailto:<?= h($profile['email'] ?? '') ?>"><i class="bi bi-envelope-fill"></i> <?= h($profile['email'] ?? '') ?></a>
        <a href="#"><i class="bi bi-geo-alt-fill"></i> <?= h($profile['location'] ?? '') ?></a>
        <a href="https://<?= h($profile['linkedin'] ?? '') ?>" target="_blank"><i class="bi bi-linkedin"></i> LinkedIn</a>
        <a href="https://<?= h($profile['github'] ?? '') ?>" target="_blank"><i class="bi bi-github"></i> GitHub</a>
    </div>
</aside>

<!-- Main Content -->
<main id="main">

    <!-- ── HERO ── -->
    <section class="section" id="hero">
        <p class="hero-greeting">Halo, selamat datang di portfolio saya</p>
        <h1 class="hero-name">
            <?php
            $parts = explode(' ', $profile['name'] ?? 'Dimas Jordi');
            $last = array_pop($parts);
            echo h(implode(' ', $parts)) . ' <span style="color:var(--cyan)">' . h($last) . '</span>';
            ?>
        </h1>
        <p class="hero-tagline"><?= h($profile['tagline'] ?? '') ?></p>

        <div class="hero-stats">
            <div>
                <div class="hero-stat-num"><?= h((string)($profile['years_experience'] ?? 4)) ?>+</div>
                <div class="hero-stat-label">Tahun Pengalaman</div>
            </div>
            <div>
                <div class="hero-stat-num"><?= count($projects) ?></div>
                <div class="hero-stat-label">Proyek Selesai</div>
            </div>
            <div>
                <div class="hero-stat-num"><?= array_sum(array_map('count', $skills)) ?></div>
                <div class="hero-stat-label">Teknologi Dikuasai</div>
            </div>
        </div>

        <div class="hero-chart"></div>

        <div class="d-flex gap-3 flex-wrap">
            <a href="#projects" class="btn-primary-cyan"><i class="bi bi-folder2-open"></i> Lihat Proyek</a>
            <a href="#contact" class="btn-outline-cyan"><i class="bi bi-send"></i> Hubungi Saya</a>
        </div>
    </section>

    <!-- ── ABOUT ── -->
    <section class="section fade-in" id="about">
        <div class="section-label">TENTANG SAYA</div>
        <h2 class="section-title">Siapa <span>Dimas</span>?</h2>
        <p class="about-text"><?= h($profile['bio'] ?? '') ?></p>
    </section>

    <!-- ── SKILLS ── -->
    <section class="section fade-in" id="skills">
        <div class="section-label">KEAHLIAN</div>
        <h2 class="section-title">Tech <span>Stack</span></h2>
        <div class="row g-4">
            <?php foreach ($skills as $category => $items): ?>
            <div class="col-md-6">
                <div class="skill-category-title">
                    <i class="bi <?= h($skillCategoryIcons[$category] ?? 'bi-star') ?>"></i>
                    <?= h($category) ?>
                </div>
                <?php foreach ($items as $skill): ?>
                <div class="skill-item">
                    <div class="skill-name">
                        <span><i class="bi <?= h($skill['icon']) ?> me-1"></i><?= h($skill['name']) ?></span>
                        <span class="skill-pct"><?= (int)$skill['proficiency'] ?>%</span>
                    </div>
                    <div class="skill-bar-track">
                        <div class="skill-bar-fill" data-width="<?= (int)$skill['proficiency'] ?>"></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ── PROJECTS ── -->
    <section class="section fade-in" id="projects">
        <div class="section-label">PORTOFOLIO</div>
        <h2 class="section-title">Proyek <span>Unggulan</span></h2>
        <?php foreach ($projects as $p):
            $accentColor = $projectCategoryColors[$p['category']] ?? '#00D4FF';
            $tools = array_map('trim', explode(',', $p['tools']));
        ?>
        <div class="project-card" style="--accent:<?= $accentColor ?>">
            <style>.project-card[style*="--accent:<?= $accentColor ?>"]::before { background: <?= $accentColor ?>; }</style>
            <div class="project-category-badge"><?= h($p['category']) ?></div>
            <div class="project-title"><?= h($p['title']) ?></div>
            <div class="project-desc"><?= h($p['description']) ?></div>
            <div class="project-tools">
                <?php foreach ($tools as $tool): ?>
                <span class="badge-tool"><?= h($tool) ?></span>
                <?php endforeach; ?>
            </div>
            <div class="project-outcome">
                <i class="bi bi-arrow-up-right-circle-fill"></i>
                <?= h($p['outcome']) ?>
            </div>
        </div>
        <?php endforeach; ?>
    </section>



    <!-- ── CONTACT ── -->
    <section class="section fade-in" id="contact">
        <div class="section-label">KONTAK</div>
        <h2 class="section-title">Mari <span>Berkolaborasi</span></h2>
        <p style="color:var(--slate);margin-bottom:2rem;font-size:.9rem;">
            Saya terbuka untuk proyek freelance, konsultasi data, maupun peluang karir baru.
        </p>
        <a href="mailto:<?= h($profile['email'] ?? '') ?>" class="contact-item">
            <i class="bi bi-envelope-fill"></i>
            <div><div class="contact-label">Email</div><div class="contact-value"><?= h($profile['email'] ?? '') ?></div></div>
        </a>
        <a href="tel:<?= h($profile['phone'] ?? '') ?>" class="contact-item">
            <i class="bi bi-telephone-fill"></i>
            <div><div class="contact-label">Telepon / WhatsApp</div><div class="contact-value"><?= h($profile['phone'] ?? '') ?></div></div>
        </a>
        <a href="https://<?= h($profile['linkedin'] ?? '') ?>" target="_blank" class="contact-item">
            <i class="bi bi-linkedin"></i>
            <div><div class="contact-label">LinkedIn</div><div class="contact-value"><?= h($profile['linkedin'] ?? '') ?></div></div>
        </a>
        <a href="https://<?= h($profile['github'] ?? '') ?>" target="_blank" class="contact-item">
            <i class="bi bi-github"></i>
            <div><div class="contact-label">GitHub</div><div class="contact-value"><?= h($profile['github'] ?? '') ?></div></div>
        </a>
    </section>

    <footer>
        <div class="d-flex justify-content-between flex-wrap gap-2">
            <span>© <?= date('Y') ?> <?= h($profile['name'] ?? '') ?>. All rights reserved.</span>
            <span>Built with PHP · MySQL · Bootstrap 5</span>
        </div>
    </footer>

</main>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// ── Skill bars animation
function animateBars() {
    document.querySelectorAll('.skill-bar-fill').forEach(bar => {
        const target = bar.getAttribute('data-width');
        bar.style.width = target + '%';
    });
}

// ── Intersection Observer for fade-in & skill bars
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
            if (entry.target.id === 'skills') animateBars();
        }
    });
}, { threshold: 0.1 });

document.querySelectorAll('.fade-in, #skills').forEach(el => observer.observe(el));

// Trigger hero visible on load
document.querySelector('#hero').style.opacity = 1;

// ── Active sidebar nav on scroll
const sections = document.querySelectorAll('section[id]');
const navLinks = document.querySelectorAll('.sidebar-nav a');

window.addEventListener('scroll', () => {
    let current = '';
    sections.forEach(sec => {
        if (window.scrollY >= sec.offsetTop - 120) current = sec.id;
    });
    navLinks.forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href') === '#' + current) link.classList.add('active');
    });
});

// ── Mobile menu
function toggleMenu() {
    const menu = document.getElementById('mobileMenu');
    const icon = document.getElementById('menuIcon');
    const open = menu.classList.toggle('open');
    icon.className = open ? 'bi bi-x-lg' : 'bi bi-list';
}

// ── Smooth scroll for sidebar links
document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
        const target = document.querySelector(a.getAttribute('href'));
        if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth' }); }
    });
});
</script>
</body>
</html>
