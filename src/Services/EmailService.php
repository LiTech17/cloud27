<?php
// src/Services/EmailService.php

namespace Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as MailException;

/**
 * Central email service. Uses small template classes to build email HTML bodies.
 * Designed for clarity, testability, and to avoid large inline heredocs.
 */
class EmailService
{
    private string $fromAddress;
    private string $fromName;

    public function __construct(?string $fromAddress = null, ?string $fromName = null)
    {
        $this->fromAddress = $fromAddress ?? ($_ENV['SMTP_USER'] ?? 'no-reply@localhost');
        $this->fromName = $fromName ?? ($_ENV['SMTP_FROM_NAME'] ?? 'Cloud27');
    }

    private function setupMailer(): PHPMailer
    {
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host       = $_ENV['SMTP_HOST'] ?? '127.0.0.1';
        $mail->SMTPAuth   = filter_var($_ENV['SMTP_AUTH'] ?? 'true', FILTER_VALIDATE_BOOLEAN);
        $mail->Username   = $_ENV['SMTP_USER'] ?? '';
        $mail->Password   = $_ENV['SMTP_PASS'] ?? '';
        $secure = strtolower($_ENV['SMTP_SECURE'] ?? 'ssl');
        $mail->SMTPSecure = $secure === 'ssl' ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = (int)($_ENV['SMTP_PORT'] ?? 465);
        $mail->SMTPDebug  = 0;

        return $mail;
    }

    private function wrapInLayout(string $innerHtml): string
    {
        $year = date('Y');

        // small, single-line strings and concatenation avoids heredoc pitfalls
        $layout = '<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4; padding:25px; font-family:Arial, sans-serif;">'
                . '<tr><td align="center">'
                . '<table width="650" cellpadding="0" cellspacing="0" style="background:#fff; border-radius:10px; overflow:hidden;">'
                . '<tr><td style="background:#0d6efd; padding:20px; text-align:center;">'
                . '<h2 style="color:#fff; font-size:20px; margin:0;">Cloud27 Web Solutions</h2>'
                . '</td></tr>'
                . '<tr><td style="padding:28px;">' . $innerHtml . '</td></tr>'
                . '<tr><td style="background:#f0f0f0; text-align:center; padding:18px; color:#666; font-size:12px;">'
                . '&copy; ' . $year . ' Cloud27. All rights reserved.<br>This is an automated message from Cloud27.'
                . '</td></tr>'
                . '</table></td></tr></table>';

        return $layout;
    }

    /**
     * Send an admin notification about a newly created project
     * Returns [bool $ok, ?string $errorMessage]
     */
    public function sendAdminNotification(array $data): array
    {
        // build template
        $template = new Templates\AdminProjectEmail();
        $html = $template->render($data);
        $body = $this->wrapInLayout($html);

        try {
            $mail = $this->setupMailer();
            $mail->setFrom($this->fromAddress, $this->fromName);

            // primary admin recipient from env
            if (empty($_ENV['SMTP_TO'])) {
                throw new \RuntimeException('SMTP_TO (admin recipient) not configured');
            }

            $mail->addAddress($_ENV['SMTP_TO']);
            if (!empty($data['rep_email'])) {
                $mail->addReplyTo($data['rep_email'], $data['rep_name'] ?? '');
            }

            $mail->isHTML(true);
            $mail->Subject = 'ACTION: NEW PROJECT LEAD - ' . ($data['company_name'] ?? 'Unknown');
            $mail->Body = $body;
            $mail->send();

            return [true, null];
        } catch (MailException | \Throwable $e) {
            error_log('[EmailService] sendAdminNotification error: ' . $e->getMessage());
            return [false, $e->getMessage()];
        }
    }

    /**
     * Send quote confirmation to client
     */
    public function sendClientQuoteConfirmation(array $data): array
    {
        if (empty($data['rep_email'])) {
            return [false, 'Client email missing'];
        }

        $template = new Templates\ClientQuoteEmail();
        $html = $template->render($data);
        $body = $this->wrapInLayout($html);

        try {
            $mail = $this->setupMailer();
            $mail->setFrom($this->fromAddress, $this->fromName);
            $mail->addAddress($data['rep_email'], $data['rep_name'] ?? '');

            $mail->isHTML(true);
            $mail->Subject = 'Project Quote & Confirmation - Ref: #' . ($data['project_id'] ?? 'N/A');
            $mail->Body = $body;
            $mail->send();

            return [true, null];
        } catch (MailException | \Throwable $e) {
            error_log('[EmailService] sendClientQuoteConfirmation error: ' . $e->getMessage());
            return [false, $e->getMessage()];
        }
    }

    /**
     * Optional: sends a simple auto-reply to client
     */
    public function sendClientAutoReply(array $data): array
    {
        if (empty($data['rep_email'])) {
            return [false, 'Client email missing'];
        }

        $template = new Templates\ClientAutoReplyEmail();
        $html = $template->render($data);
        $body = $this->wrapInLayout($html);

        try {
            $mail = $this->setupMailer();
            $mail->setFrom($this->fromAddress, $this->fromName);
            $mail->addAddress($data['rep_email'], $data['rep_name'] ?? '');

            $mail->isHTML(true);
            $mail->Subject = 'Thanks for contacting Cloud27';
            $mail->Body = $body;
            $mail->send();

            return [true, null];
        } catch (MailException | \Throwable $e) {
            error_log('[EmailService] sendClientAutoReply error: ' . $e->getMessage());
            return [false, $e->getMessage()];
        }
    }
}


// ----------------------
// Template classes below
// ----------------------

namespace Services\Templates;

/**
 * Simple template interface
 */
interface EmailTemplateInterface
{
    public function render(array $data): string;
}

/**
 * Base helper with small escape helpers
 */
abstract class BaseEmailTemplate implements EmailTemplateInterface
{
    protected function esc(string $s): string
    {
        return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    protected function escnl(string $s): string
    {
        return nl2br($this->esc($s));
    }
}

class AdminProjectEmail extends BaseEmailTemplate
{
    public function render(array $data): string
    {
        $company = $this->esc($data['company_name'] ?? 'N/A');
        $repName = $this->esc($data['rep_name'] ?? 'N/A');
        $repRole = $this->esc($data['rep_role'] ?? 'N/A');
        $repEmail = $this->esc($data['rep_email'] ?? 'N/A');
        $projectId = $data['project_id'] ?? 'N/A';
        $quote = $data['quote'] ?? ['package_name' => 'N/A', 'total_once_off' => 0];
        $total = number_format($quote['total_once_off'] ?? 0, 2);
        $adminLink = $this->esc($data['admin_link'] ?? ("http://yourdomain.com/admin/projects/{$projectId}"));

        $html = "<h2 style='color:#0d6efd;'>NEW PROJECT LEAD: {$company}</h2>";
        $html .= "<p style='font-size:16px;'>A new project has been submitted via the Get Started form. Action required.</p>";
        $html .= "<hr style='border-top:1px solid #eee;'>";
        $html .= "<h3>Quote Summary</h3>";
        $html .= "<p><strong>Project ID:</strong> {$projectId}</p>";
        $html .= "<p><strong>Package Estimated:</strong> " . $this->esc($quote['package_name']) . "</p>";
        $html .= "<p style='font-size:18px; color:#28a745; font-weight:bold;'>Estimated Cost: R{$total}</p>";

        $html .= "<h3>Client Details</h3>";
        $html .= "<table style='width:100%; border-collapse: collapse;'>";
        $html .= "<tr><td style='padding:5px; border-bottom:1px solid #eee;'><strong>Company:</strong></td>";
        $html .= "<td style='padding:5px; border-bottom:1px solid #eee;'>{$company}</td></tr>";
        $html .= "<tr><td style='padding:5px; border-bottom:1px solid #eee;'><strong>Contact:</strong></td>";
        $html .= "<td style='padding:5px; border-bottom:1px solid #eee;'>{$repName} ({$repRole})</td></tr>";
        $html .= "<tr><td style='padding:5px; border-bottom:1px solid #eee;'><strong>Email:</strong></td>";
        $html .= "<td style='padding:5px; border-bottom:1px solid #eee;'><a href='mailto:{$repEmail}'>{$repEmail}</a></td></tr>";
        $html .= "</table>";

        $html .= "<h3 style='margin-top:20px;'>Next Steps</h3>";
        $html .= "<p>Review the full submission details (including uploaded files) using the link below:</p>";
        $html .= "<p style='text-align:center;'><a href='{$adminLink}' style='background:#0d6efd; color:#fff; padding:10px 20px; text-decoration:none; border-radius:5px; display:inline-block;'>View Project #{$projectId} in Admin</a></p>";

        return $html;
    }
}

class ClientQuoteEmail extends BaseEmailTemplate
{
    public function render(array $data): string
    {
        $repName = $this->esc($data['rep_name'] ?? 'Client');
        $company = $this->esc($data['company_name'] ?? 'Your Company');
        $quote = $data['quote'] ?? ['package_name' => 'N/A', 'total_once_off' => 0];
        $total = number_format($quote['total_once_off'] ?? 0, 2);
        $projectId = $data['project_id'] ?? 'N/A';

        $html = "<h3 style='color:#222;'>Hello {$repName},</h3>";
        $html .= "<p>Thank you for submitting your project request to Cloud27! We're excited to help {$company} succeed.</p>";
        $html .= "<p>Here is your instant quote summary:</p>";
        $html .= "<div style='border:2px solid #0d6efd; padding:15px; border-radius:8px; margin:20px 0;'>";
        $html .= "<p style='font-size:18px; font-weight:bold; color:#0d6efd; margin-top:0;'>Estimated Project Quote</p>";
        $html .= "<p><strong>Package:</strong> " . $this->esc($quote['package_name']) . "</p>";
        $html .= "<p style='font-size:22px; color:#dc3545; font-weight:bold;'>Total Estimated Cost: R{$total}</p>";
        $html .= "</div>";
        $html .= "<h4 style='margin-top:20px;'>What Happens Next?</h4>";
        $html .= "<ol>";
        $html .= "<li>Our team will review your full submission and uploaded assets (Ref: #{$projectId}).</li>";
        $html .= "<li>You will be contacted within 1 business day to discuss your quote.</li>";
        $html .= "<li>You may reply directly to this email if you'd like to proceed immediately.</li>";
        $html .= "</ol>";
        $html .= "<p style='margin-top:16px; font-weight:bold;'>— The Cloud27 Project Team</p>";

        return $html;
    }
}

class ClientAutoReplyEmail extends BaseEmailTemplate
{
    public function render(array $data): string
    {
        $name = $this->esc($data['rep_name'] ?? 'there');
        $message = $this->escnl($data['message'] ?? '');

        $html = "<h3 style='color:#222;'>Hi {$name},</h3>";
        $html .= "<p>Thank you for contacting <strong>Cloud27</strong>!</p>";
        $html .= "<p>We have received your message and our team is reviewing your request. Expect a response within <strong>1–2 business hours</strong>.</p>";
        $html .= "<h4>Your Message:</h4><p style='line-height:1.6;'>{$message}</p>";
        $html .= "<p style='margin-top:16px; font-weight:bold;'>— The Cloud27 Team</p>";

        return $html;
    }
}
