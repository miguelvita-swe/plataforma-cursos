<?php
/**
 * Functions to read and filter courses from JSON
 */

declare(strict_types=1);

function getAllCourses(): array
{
    $file = __DIR__ . '/../assets/courses.json';
    if (!file_exists($file)) {
        return [];
    }

    $json = file_get_contents($file);
    $data = json_decode($json, true);
    return is_array($data) ? $data : [];
}

function getCoursesByArea(string $area): array
{
    $area = trim(strtolower($area));
    if ($area === '') {
        return getAllCourses();
    }

    $all = getAllCourses();
    $filtered = [];
    foreach ($all as $course) {
        if (!isset($course['area'])) {
            continue;
        }
        if (strtolower($course['area']) === $area) {
            $filtered[] = $course;
        }
    }

    return $filtered;
}

function getCourseById(string $id): ?array
{
    $all = getAllCourses();
    foreach ($all as $course) {
        if (isset($course['id']) && $course['id'] === $id) {
            return $course;
        }
    }
    return null;
}
