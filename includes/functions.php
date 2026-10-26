<?php
require_once __DIR__ . '/config.php';

function getProfile(): array {
    return getDB()->query("SELECT * FROM profile LIMIT 1")->fetch() ?: [];
}

function getSkills(): array {
    $rows = getDB()->query("SELECT * FROM skills ORDER BY category, sort_order")->fetchAll();
    $grouped = [];
    foreach ($rows as $row) {
        $grouped[$row['category']][] = $row;
    }
    return $grouped;
}

function getProjects(bool $featuredOnly = false): array {
    $sql = "SELECT * FROM projects" . ($featuredOnly ? " WHERE featured = 1" : "") . " ORDER BY sort_order";
    return getDB()->query($sql)->fetchAll();
}

function getExperience(): array {
    return getDB()->query("SELECT * FROM experience ORDER BY sort_order")->fetchAll();
}

function getEducation(): array {
    return getDB()->query("SELECT * FROM education")->fetchAll();
}

function getCertifications(): array {
    return getDB()->query("SELECT * FROM certifications ORDER BY year DESC")->fetchAll();
}

function h(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
