<?php
/**
 * Enrollment helper functions
 *
 * Provides simple JSON-based persistence for course enrollments.
 * File locking is used to avoid race conditions when writing.
 */

declare(strict_types=1);

function get_enrollments_file_path(): string
{
    return __DIR__ . '/data/enrollments.json';
}

/**
 * Save an enrollment (append) to the JSON storage.
 * Returns true on success or false on failure.
 */
function save_enrollment(string $name, string $email, string $courseId): bool
{
    $file = get_enrollments_file_path();

    // Ensure file exists
    if (!file_exists($file)) {
        file_put_contents($file, json_encode([]));
    }

    $fp = fopen($file, 'c+');
    if (!$fp) {
        return false;
    }

    // Acquire exclusive lock
    if (!flock($fp, LOCK_EX)) {
        fclose($fp);
        return false;
    }

    // Read current data
    clearstatcache(true, $file);
    $contents = stream_get_contents($fp);
    $data = [];
    if ($contents !== false && trim($contents) !== '') {
        $decoded = json_decode($contents, true);
        if (is_array($decoded)) {
            $data = $decoded;
        }
    }

    // Append new enrollment
    $entry = [
        'id' => uniqid('enr_', true),
        'name' => $name,
        'email' => $email,
        'course_id' => $courseId,
        'created_at' => date('c')
    ];

    $data[] = $entry;

    // Rewind, truncate and write
    ftruncate($fp, 0);
    rewind($fp);
    $written = fwrite($fp, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    fflush($fp);
    flock($fp, LOCK_UN);
    fclose($fp);

    return $written !== false;
}

/**
 * Get all enrollments (returns array)
 */
function get_all_enrollments(): array
{
    $file = get_enrollments_file_path();
    if (!file_exists($file)) {
        return [];
    }
    $contents = file_get_contents($file);
    if ($contents === false || trim($contents) === '') {
        return [];
    }
    $decoded = json_decode($contents, true);
    return is_array($decoded) ? $decoded : [];
}

/**
 * Get enrollments by email (case-insensitive)
 */
function get_enrollments_by_email(string $email): array
{
    $email = trim(strtolower($email));
    $all = get_all_enrollments();
    $result = [];
    foreach ($all as $e) {
        if (isset($e['email']) && strtolower($e['email']) === $email) {
            $result[] = $e;
        }
    }
    return $result;
}

/**
 * Get enrollments by course id
 */
function get_enrollments_by_course(string $courseId): array
{
    $all = get_all_enrollments();
    $result = [];
    foreach ($all as $e) {
        if (isset($e['course_id']) && $e['course_id'] === $courseId) {
            $result[] = $e;
        }
    }
    return $result;
}

?>