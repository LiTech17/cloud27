<?php
// src/EmailService.php

// Note: PHPMailer's 'use' statements are in index.php for global availability.

/**
 * Handles all application email sending logic using PHPMailer and secure .env config.
 */
class EmailService {

    // --- FIX APPLIED HERE ---
    // Change PHPMailer to the Fully Qualified Name (FQN) \PHPMailer\PHPMailer\PHPMailer
    private function setupMailer(): \PHPMailer\PHPMailer\PHPMailer {
        // Instantiate a new PHPMailer object
        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

        // Server settings retrieved from the secure environment variables
        $mail->isSMTP();
        $mail->Host       = $_ENV['SMTP_HOST'];
        $mail->SMTPAuth   = (bool)($_ENV['SMTP_AUTH'] ?? true);
        $mail->Username   = $_ENV['SMTP_USER'];
        $mail->Password   = $_ENV['SMTP_PASS'];
        $mail->SMTPSecure = $_ENV['SMTP_SECURE'] === 'ssl' ? \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS : \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = (int)($_ENV['SMTP_PORT'] ?? 465);
        $mail->SMTPDebug  = 0; // Set to 2 for development debugging

        return $mail;
    }

    // --- TEMPLATING METHODS (Adapted from your snippet) ---

    private function cloud27EmailWrapper(string $content): string
    {
        $year = date('Y');

        // Simple table wrapper for email branding (compatible with most clients)
        return <<<HTML
        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4; padding:25px; font-family:Arial, sans-serif;">
            <tr>
                <td align="center">
                    <table width="650" cellpadding="0" cellspacing="0" style="background:#fff; border-radius:10px; overflow:hidden;">
                        <tr>
                            <td style="background:#0d6efd; padding:20px; text-align:center;">
                                <h2 style="color:#fff; font-size:20px; margin:0;">Cloud27 Web Solutions</h2>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:28px;">$content</td>
                        </tr>
                        <tr>
                            <td style="background:#f0f0f0; text-align:center; padding:18px; color:#666; font-size:12px;">
                                &copy; $year Cloud27. All rights reserved.<br>
                                This is an automated message from Cloud27.
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        HTML;
    }
    
    private function adminEmailTemplate(array $data): string
    {
        // Simple HTML template for admin notification
        $name     = htmlspecialchars($data['name'] ?? '', ENT_QUOTES);
        $email    = htmlspecialchars($data['email'] ?? '', ENT_QUOTES);
        $message  = nl2br(htmlspecialchars($data['message'] ?? '', ENT_QUOTES));
        $ip       = htmlspecialchars($_SERVER['REMOTE_ADDR'] ?? 'unknown', ENT_QUOTES);

        $html = "<h3 style='color:#222;'>New Website Inquiry</h3>
                 <p><strong>Name:</strong> $name</p>
                 <p><strong>Email:</strong> $email</p>
                 <hr style='border-top:1px solid #eee;'>
                 <h4>Message:</h4><p style='line-height:1.6;'>$message</p>";
        
        $html .= "<p style='color:#999; font-size:12px;'>IP: $ip</p>";

        return $html;
    }

    private function clientAutoReplyTemplate(array $data): string
    {
        // Simple HTML template for client auto-reply
        $name     = htmlspecialchars($data['name'] ?? '', ENT_QUOTES);
        $message = nl2br(htmlspecialchars($data['message'] ?? '', ENT_QUOTES));

        $html = "<h3 style='color:#222;'>Hi $name,</h3>
                 <p>Thank you for contacting <strong>Cloud27</strong>! 🎉</p>
                 <p>We have received your message and our team is reviewing your request. Expect a response within <strong>1–2 business hours</strong>.</p>
                 <h4>Your Message:</h4><p style='line-height:1.6;'>$message</p>
                 <p style='margin-top:16px; font-weight:bold;'>— The Cloud27 Team</p>";

        return $html;
    }

    // --- SENDING METHODS ---

    public function sendAdminEmail(array $data): array
    {
        $mail = $this->setupMailer();

        try {
            $mail->setFrom($_ENV['SMTP_USER'], $_ENV['SMTP_FROM_NAME'] ?? 'Cloud27');
            $mail->addAddress($_ENV['SMTP_TO']);
            if (!empty($data['email'])) $mail->addReplyTo($data['email'], $data['name'] ?? '');

            $mail->isHTML(true);
            $mail->Subject = 'New Inquiry from ' . ($data['name'] ?? 'website');
            $mail->Body    = $this->cloud27EmailWrapper($this->adminEmailTemplate($data));

            $mail->send();
            return [true, null];
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            error_log('sendAdminEmail error: ' . $mail->ErrorInfo);
            return [false, $mail->ErrorInfo ?: $e->getMessage()];
        }
    }

    public function sendClientAutoReply(array $data): array
    {
        if (empty($data['email'])) return [false, 'Client email missing'];

        $mail = $this->setupMailer();

        try {
            $mail->setFrom($_ENV['SMTP_USER'], 'Cloud27 Support');
            $mail->addAddress($data['email'], $data['name'] ?? '');

            $mail->isHTML(true);
            $mail->Subject = "We've received your request – Cloud27";
            $mail->Body    = $this->cloud27EmailWrapper($this->clientAutoReplyTemplate($data));

            $mail->send();
            return [true, null];
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            error_log('sendClientAutoReply error: ' . $mail->ErrorInfo);
            return [false, $mail->ErrorInfo ?: $e->getMessage()];
        }
    }
}