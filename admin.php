<?php
/**
 * Admin sederhana untuk mengedit konten portfolio (SQLite)
 * PENTING: Hapus atau password-protect file ini di production!
 */
require_once __DIR__ . '/includes/functions.php';

$db = getDB();
$msg = '';
$section = $_GET['section'] ?? 'profile';

// ── Handle POST ─────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'update_profile') {
        $stmt = $db->prepare("UPDATE profile SET name=?, tagline=?, bio=?, email=?, phone=?, location=?, linkedin=?, github=?, years_experience=? WHERE id=1");
        $stmt->execute([
            $_POST['name'], $_POST['tagline'], $_POST['bio'],
            $_POST['email'], $_POST['phone'], $_POST['location'],
            $_POST['linkedin'], $_POST['github'], (int)$_POST['years_experience']
        ]);
        $msg = 'Profil berhasil diperbarui.';
    }

    if ($action === 'add_project') {
        $stmt = $db->prepare("INSERT INTO projects (title, description, tools, outcome, category, featured, sort_order) VALUES (?,?,?,?,?,?,?)");
        $stmt->execute([
            $_POST['title'], $_POST['description'], $_POST['tools'],
            $_POST['outcome'], $_POST['category'], isset($_POST['featured']) ? 1 : 0,
            (int)$_POST['sort_order']
        ]);
        $msg = 'Proyek berhasil ditambahkan.';
        $section = 'projects';
    }

    if ($action === 'delete_project') {
        $db->prepare("DELETE FROM projects WHERE id=?")->execute([(int)$_POST['id']]);
        $msg = 'Proyek dihapus.';
        $section = 'projects';
    }

    if ($action === 'add_experience') {
        $stmt = $db->prepare("INSERT INTO experience (company, role, period, description, sort_order) VALUES (?,?,?,?,?)");
        $stmt->execute([$_POST['company'], $_POST['role'], $_POST['period'], $_POST['description'], (int)$_POST['sort_order']]);
        $msg = 'Pengalaman berhasil ditambahkan.';
        $section = 'experience';
    }

    if ($action === 'delete_experience') {
        $db->prepare("DELETE FROM experience WHERE id=?")->execute([(int)$_POST['id']]);
        $msg = 'Pengalaman dihapus.';
        $section = 'experience';
    }

    if ($action === 'add_skill') {
        $stmt = $db->prepare("INSERT INTO skills (category, name, proficiency, icon, sort_order) VALUES (?,?,?,?,?)");
        $stmt->execute([$_POST['category'], $_POST['name'], (int)$_POST['proficiency'], $_POST['icon'], (int)$_POST['sort_order']]);
        $msg = 'Skill berhasil ditambahkan.';
        $section = 'skills';
    }

    if ($action === 'delete_skill') {
        $db->prepare("DELETE FROM skills WHERE id=?")->execute([(int)$_POST['id']]);
        $msg = 'Skill dihapus.';
        $section = 'skills';
    }
}

$profile  = getProfile();
$projects = getProjects();
$exp      = getExperience();
$allSkills = $db->query("SELECT * FROM skills ORDER BY category, sort_order")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Portfolio Dimas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background: #0f172a; color: #e2e8f0; font-family: system-ui, sans-serif; }
        .sidebar { background: #1e293b; min-height: 100vh; padding: 1.5rem 1rem; }
        .nav-link { color: #94a3b8; border-radius: 6px; padding: .5rem .75rem; font-size: .85rem; }
        .nav-link:hover, .nav-link.active { background: rgba(0,212,255,.12); color: #00d4ff; }
        .card { background: #1e293b; border: 1px solid rgba(255,255,255,.06); color: #e2e8f0; }
        .form-control, .form-select { background: #0f172a; border-color: #334155; color: #e2e8f0; }
        .form-control:focus, .form-select:focus { background: #0f172a; border-color: #00d4ff; color: #e2e8f0; box-shadow: 0 0 0 .2rem rgba(0,212,255,.15); }
        .table { color: #e2e8f0; }
        .table > :not(caption) > * > * { background: transparent; }
        h5 { color: #00d4ff; }
        .badge-cyan { background: rgba(0,212,255,.12); color: #00d4ff; border: 1px solid rgba(0,212,255,.2); }
    </style>
</head>
<body>
<div class="container-fluid">
<div class="row">
    <!-- Sidebar -->
    <div class="col-md-2 sidebar">
        <div class="mb-4">
            <div style="color:#00d4ff;font-weight:700;font-size:.9rem;">⚙ Admin Panel</div>
            <div style="color:#64748b;font-size:.75rem;">Portfolio Dimas</div>
        </div>
        <nav class="nav flex-column gap-1">
            <a href="?section=profile"    class="nav-link <?= $section==='profile'?'active':'' ?>"><i class="bi bi-person me-2"></i>Profil</a>
            <a href="?section=projects"   class="nav-link <?= $section==='projects'?'active':'' ?>"><i class="bi bi-folder2 me-2"></i>Proyek</a>
            <a href="?section=experience" class="nav-link <?= $section==='experience'?'active':'' ?>"><i class="bi bi-briefcase me-2"></i>Pengalaman</a>
            <a href="?section=skills"     class="nav-link <?= $section==='skills'?'active':'' ?>"><i class="bi bi-bar-chart me-2"></i>Skills</a>
        </nav>
        <hr style="border-color:#334155">
        <a href="index.php" class="nav-link" target="_blank"><i class="bi bi-eye me-2"></i>Lihat Portfolio</a>
    </div>

    <!-- Content -->
    <div class="col-md-10 p-4">
        <a href="index.php" class="btn btn-sm mb-3" style="background:rgba(0,212,255,.1);color:#00d4ff;border:1px solid rgba(0,212,255,.2);">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Portfolio
        </a>

        <?php if ($msg): ?>
        <div class="alert alert-success alert-dismissible fade show" style="background:rgba(16,185,129,.1);border-color:rgba(16,185,129,.3);color:#34d399;" role="alert">
            <i class="bi bi-check-circle me-2"></i><?= h($msg) ?>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- ── PROFILE ── -->
        <?php if ($section === 'profile'): ?>
        <h5 class="mb-3"><i class="bi bi-person-fill me-2"></i>Edit Profil</h5>
        <div class="card p-4">
            <form method="POST">
                <input type="hidden" name="action" value="update_profile">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label small">Nama</label>
                        <input class="form-control" name="name" value="<?= h($profile['name'] ?? '') ?>" required></div>
                    <div class="col-md-6"><label class="form-label small">Tagline</label>
                        <input class="form-control" name="tagline" value="<?= h($profile['tagline'] ?? '') ?>"></div>
                    <div class="col-12"><label class="form-label small">Bio</label>
                        <textarea class="form-control" name="bio" rows="4"><?= h($profile['bio'] ?? '') ?></textarea></div>
                    <div class="col-md-6"><label class="form-label small">Email</label>
                        <input class="form-control" name="email" type="email" value="<?= h($profile['email'] ?? '') ?>"></div>
                    <div class="col-md-6"><label class="form-label small">Telepon</label>
                        <input class="form-control" name="phone" value="<?= h($profile['phone'] ?? '') ?>"></div>
                    <div class="col-md-4"><label class="form-label small">Lokasi</label>
                        <input class="form-control" name="location" value="<?= h($profile['location'] ?? '') ?>"></div>
                    <div class="col-md-4"><label class="form-label small">LinkedIn URL</label>
                        <input class="form-control" name="linkedin" value="<?= h($profile['linkedin'] ?? '') ?>"></div>
                    <div class="col-md-4"><label class="form-label small">GitHub URL</label>
                        <input class="form-control" name="github" value="<?= h($profile['github'] ?? '') ?>"></div>
                    <div class="col-md-3"><label class="form-label small">Tahun Pengalaman</label>
                        <input class="form-control" name="years_experience" type="number" value="<?= h((string)($profile['years_experience'] ?? 0)) ?>"></div>
                </div>
                <button type="submit" class="btn mt-3" style="background:#00d4ff;color:#0a1628;font-weight:600;">
                    <i class="bi bi-save me-1"></i> Simpan Perubahan
                </button>
            </form>
        </div>

        <!-- ── PROJECTS ── -->
        <?php elseif ($section === 'projects'): ?>
        <h5 class="mb-3"><i class="bi bi-folder2-open me-2"></i>Kelola Proyek</h5>
        <div class="card p-4 mb-4">
            <h6 class="mb-3" style="color:#94a3b8">Tambah Proyek Baru</h6>
            <form method="POST">
                <input type="hidden" name="action" value="add_project">
                <div class="row g-3">
                    <div class="col-md-8"><label class="form-label small">Judul</label>
                        <input class="form-control" name="title" required></div>
                    <div class="col-md-4"><label class="form-label small">Kategori</label>
                        <select class="form-select" name="category">
                            <option>Machine Learning</option><option>Business Intelligence</option>
                            <option>Analytics</option><option>Public Data</option>
                        </select></div>
                    <div class="col-12"><label class="form-label small">Deskripsi</label>
                        <textarea class="form-control" name="description" rows="3" required></textarea></div>
                    <div class="col-md-6"><label class="form-label small">Tools (pisah koma)</label>
                        <input class="form-control" name="tools"></div>
                    <div class="col-md-4"><label class="form-label small">Outcome / Hasil</label>
                        <input class="form-control" name="outcome"></div>
                    <div class="col-md-1"><label class="form-label small">Urutan</label>
                        <input class="form-control" name="sort_order" type="number" value="99"></div>
                    <div class="col-md-1 d-flex align-items-end">
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="featured" id="feat">
                        <label class="form-check-label small" for="feat">Featured</label></div>
                    </div>
                </div>
                <button type="submit" class="btn mt-3 btn-sm" style="background:#00d4ff;color:#0a1628;font-weight:600;">
                    <i class="bi bi-plus-lg me-1"></i>Tambah
                </button>
            </form>
        </div>
        <div class="card p-0">
            <table class="table table-sm mb-0">
                <thead style="background:#0f172a"><tr>
                    <th class="ps-3 py-2" style="color:#64748b;font-size:.75rem;">JUDUL</th>
                    <th style="color:#64748b;font-size:.75rem;">KATEGORI</th>
                    <th style="color:#64748b;font-size:.75rem;">TOOLS</th>
                    <th style="color:#64748b;font-size:.75rem;">FEATURED</th>
                    <th></th>
                </tr></thead>
                <tbody>
                <?php foreach ($projects as $p): ?>
                <tr style="border-color:#1e293b">
                    <td class="ps-3 py-2" style="font-size:.85rem;"><?= h($p['title']) ?></td>
                    <td><span class="badge badge-cyan"><?= h($p['category']) ?></span></td>
                    <td style="font-size:.78rem;color:#64748b"><?= h($p['tools']) ?></td>
                    <td><?= $p['featured'] ? '<i class="bi bi-check-circle-fill" style="color:#10b981"></i>' : '—' ?></td>
                    <td>
                        <form method="POST" onsubmit="return confirm('Hapus proyek ini?')">
                            <input type="hidden" name="action" value="delete_project">
                            <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                            <button type="submit" class="btn btn-sm" style="background:rgba(239,68,68,.1);color:#ef4444;border:none;font-size:.75rem;">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- ── EXPERIENCE ── -->
        <?php elseif ($section === 'experience'): ?>
        <h5 class="mb-3"><i class="bi bi-briefcase-fill me-2"></i>Kelola Pengalaman Kerja</h5>
        <div class="card p-4 mb-4">
            <h6 class="mb-3" style="color:#94a3b8">Tambah Pengalaman</h6>
            <form method="POST">
                <input type="hidden" name="action" value="add_experience">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label small">Perusahaan</label>
                        <input class="form-control" name="company" required></div>
                    <div class="col-md-6"><label class="form-label small">Jabatan / Role</label>
                        <input class="form-control" name="role" required></div>
                    <div class="col-md-4"><label class="form-label small">Periode</label>
                        <input class="form-control" name="period" placeholder="Jan 2023 – Sekarang"></div>
                    <div class="col-md-2"><label class="form-label small">Urutan</label>
                        <input class="form-control" name="sort_order" type="number" value="99"></div>
                    <div class="col-12"><label class="form-label small">Deskripsi</label>
                        <textarea class="form-control" name="description" rows="3"></textarea></div>
                </div>
                <button type="submit" class="btn mt-3 btn-sm" style="background:#00d4ff;color:#0a1628;font-weight:600;">
                    <i class="bi bi-plus-lg me-1"></i>Tambah
                </button>
            </form>
        </div>
        <div class="card p-0">
            <table class="table table-sm mb-0">
                <thead style="background:#0f172a"><tr>
                    <th class="ps-3 py-2" style="color:#64748b;font-size:.75rem;">PERUSAHAAN</th>
                    <th style="color:#64748b;font-size:.75rem;">JABATAN</th>
                    <th style="color:#64748b;font-size:.75rem;">PERIODE</th>
                    <th></th>
                </tr></thead>
                <tbody>
                <?php foreach ($exp as $e): ?>
                <tr style="border-color:#1e293b">
                    <td class="ps-3 py-2" style="font-size:.85rem;"><?= h($e['company']) ?></td>
                    <td style="font-size:.85rem;"><?= h($e['role']) ?></td>
                    <td style="font-size:.78rem;color:#64748b"><?= h($e['period']) ?></td>
                    <td>
                        <form method="POST" onsubmit="return confirm('Hapus pengalaman ini?')">
                            <input type="hidden" name="action" value="delete_experience">
                            <input type="hidden" name="id" value="<?= (int)$e['id'] ?>">
                            <button type="submit" class="btn btn-sm" style="background:rgba(239,68,68,.1);color:#ef4444;border:none;font-size:.75rem;">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- ── SKILLS ── -->
        <?php elseif ($section === 'skills'): ?>
        <h5 class="mb-3"><i class="bi bi-bar-chart-fill me-2"></i>Kelola Skills</h5>
        <div class="card p-4 mb-4">
            <h6 class="mb-3" style="color:#94a3b8">Tambah Skill</h6>
            <form method="POST">
                <input type="hidden" name="action" value="add_skill">
                <div class="row g-3">
                    <div class="col-md-3"><label class="form-label small">Kategori</label>
                        <select class="form-select" name="category">
                            <option>Analytics</option><option>Visualization</option>
                            <option>Tools</option><option>Machine Learning</option>
                        </select></div>
                    <div class="col-md-4"><label class="form-label small">Nama Skill</label>
                        <input class="form-control" name="name" required></div>
                    <div class="col-md-2"><label class="form-label small">Profisiensi (%)</label>
                        <input class="form-control" name="proficiency" type="number" min="0" max="100" value="80"></div>
                    <div class="col-md-2"><label class="form-label small">Icon (Bootstrap)</label>
                        <input class="form-control" name="icon" placeholder="bi-code-slash"></div>
                    <div class="col-md-1"><label class="form-label small">Urutan</label>
                        <input class="form-control" name="sort_order" type="number" value="99"></div>
                </div>
                <button type="submit" class="btn mt-3 btn-sm" style="background:#00d4ff;color:#0a1628;font-weight:600;">
                    <i class="bi bi-plus-lg me-1"></i>Tambah
                </button>
            </form>
        </div>
        <div class="card p-0">
            <table class="table table-sm mb-0">
                <thead style="background:#0f172a"><tr>
                    <th class="ps-3 py-2" style="color:#64748b;font-size:.75rem;">SKILL</th>
                    <th style="color:#64748b;font-size:.75rem;">KATEGORI</th>
                    <th style="color:#64748b;font-size:.75rem;">PROFISIENSI</th>
                    <th></th>
                </tr></thead>
                <tbody>
                <?php foreach ($allSkills as $sk): ?>
                <tr style="border-color:#1e293b">
                    <td class="ps-3 py-2" style="font-size:.85rem;">
                        <i class="bi <?= h($sk['icon']) ?> me-1" style="color:#00d4ff"></i><?= h($sk['name']) ?>
                    </td>
                    <td><span class="badge badge-cyan"><?= h($sk['category']) ?></span></td>
                    <td>
                        <div style="display:flex;align-items:center;gap:.5rem">
                            <div style="width:80px;height:4px;background:#0f172a;border-radius:2px">
                                <div style="width:<?= (int)$sk['proficiency'] ?>%;height:100%;background:#00d4ff;border-radius:2px"></div>
                            </div>
                            <span style="font-size:.75rem;color:#64748b"><?= (int)$sk['proficiency'] ?>%</span>
                        </div>
                    </td>
                    <td>
                        <form method="POST" onsubmit="return confirm('Hapus skill ini?')">
                            <input type="hidden" name="action" value="delete_skill">
                            <input type="hidden" name="id" value="<?= (int)$sk['id'] ?>">
                            <button type="submit" class="btn btn-sm" style="background:rgba(239,68,68,.1);color:#ef4444;border:none;font-size:.75rem;">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>

    </div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
