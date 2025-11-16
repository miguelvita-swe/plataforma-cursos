<?php
/**
 * Mail helper functions (simple wrapper using mail()).
 */

declare(strict_types=1);

function send_enrollment_confirmation(string $toEmail, string $toName, string $courseTitle): bool
{
    $subject = "Confirmação de inscrição: " . $courseTitle;
    $message = "Olá " . $toName . ",\n\n" .
        "Sua inscrição no curso '" . $courseTitle . "' foi recebida com sucesso.\n" .
        "Obrigado por se inscrever!\n\n" .
        "— Plataforma UniFio\n";

    $headers = [];
    $headers[] = 'From: no-reply@localhost';
    $headers[] = 'Content-Type: text/plain; charset=UTF-8';

    // Try to send via mail()
    $ok = false;
    try {
        $ok = mail($toEmail, $subject, $message, implode("\r\n", $headers));
    } catch (\Throwable $e) {
        $ok = false;
    }

    if (!$ok) {
        // Fallback: write to a simple log for debugging if mail() not available
        $logDir = __DIR__ . '/data';
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0755, true);
        }
        $logFile = $logDir . '/email_log.txt';
        $entry = sprintf("%s | TO: %s | NAME: %s | COURSE: %s | STATUS: failed\n", date('c'), $toEmail, $toName, $courseTitle);
        @file_put_contents($logFile, $entry, FILE_APPEND | LOCK_EX);
    } else {
        // Log success
        $logDir = __DIR__ . '/data';
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0755, true);
        }
        $logFile = $logDir . '/email_log.txt';
        $entry = sprintf("%s | TO: %s | NAME: %s | COURSE: %s | STATUS: sent\n", date('c'), $toEmail, $toName, $courseTitle);
        @file_put_contents($logFile, $entry, FILE_APPEND | LOCK_EX);
    }

    return (bool)$ok;
}

?>