<?php
// src/EmailService.php

// Note: PHPMailer's 'use' statements are in index.php for global availability.

/**
 * Handles all application email sending logic using PHPMailer and secure .env config.
 */
class EmailService {

    // --- SETUP METHOD (No change needed) ---

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

    // --- TEMPLATING METHODS (Two new methods added for onboarding) ---

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
        // Template for the standard contact form
        $name     = htmlspecialchars($data['name'] ?? '', ENT_QUOTES);
        $email    = htmlspecialchars($data['email'] ?? '', ENT_QUOTES);
        $message  = nl2br(htmlspecialchars($data['message'] ?? '', ENT_QUOTES));
        $ip       = htmlspecialchars($_SERVER['REMOTE_ADDR'] ?? 'unknown', ENT_QUOTES);

        $html = "<h3 style='color:#222;'>New Website Inquiry (Contact Form)</h3>
                 <p><strong>Name:</strong> $name</p>
                 <p><strong>Email:</strong> $email</p>
                 <hr style='border-top:1px solid #eee;'>
                 <h4>Message:</h4><p style='line-height:1.6;'>$message</p>";
        
        $html .= "<p style='color:#999; font-size:12px;'>IP: $ip</p>";

        return $html;
    }

    private function clientAutoReplyTemplate(array $data): string
    {
        // Template for the standard contact form auto-reply
        $name     = htmlspecialchars($data['name'] ?? '', ENT_QUOTES);
        $message = nl2br(htmlspecialchars($data['message'] ?? '', ENT_QUOTES));

        $html = "<h3 style='color:#222;'>Hi $name,</h3>
                 <p>Thank you for contacting <strong>Cloud27</strong>! 🎉</p>
                 <p>We have received your message and our team is reviewing your request. Expect a response within <strong>1–2 business hours</strong>.</p>
                 <h4>Your Message:</h4><p style='line-height:1.6;'>$message</p>
                 <p style='margin-top:16px; font-weight:bold;'>— The Cloud27 Team</p>";

        return $html;
    }

    /**
     * @NEW: Builds the email content for the Admin team based on project onboarding data.
     */
    private function adminProjectTemplate(array $data): string
    {
        $companyName  = htmlspecialchars($data['company_name'] ?? 'N/A');
        $repName      = htmlspecialchars($data['rep_name'] ?? 'N/A');
        $repEmail     = htmlspecialchars($data['rep_email'] ?? 'N/A');
        $projectId    = $data['project_id'] ?? 'N/A';
        $quoteSummary = $data['quote'] ?? ['package_name' => 'N/A', 'total_once_off' => 0];
        $totalCost    = number_format($quoteSummary['total_once_off'], 2);
        $adminLink    = "http://yourdomain.com/admin/projects/{$projectId}"; // Replace with your actual domain/path

        $html = "<h2 style='color:#0d6efd;'>🚨 NEW PROJECT LEAD: {$companyName}</h2>
                 <p style='font-size:16px;'>A new project has been submitted via the Get Started form. **Action required.**</p>
                 <hr style='border-top:1px solid #eee;'>
                 
                 <h3>Quote Summary</h3>
                 <p><strong>Project ID:</strong> {$projectId}</p>
                 <p><strong>Package Estimated:</strong> {$quoteSummary['package_name']}</p>
                 <p style='font-size:18px; color:#28a745; font-weight:bold;'>Estimated Cost: R{$totalCost}</p>
                 
                 <h3>Client Details</h3>
                 <table style='width:100%; border-collapse: collapse;'>
                    <tr><td style='padding:5px; border-bottom:1px solid #eee;'><strong>Company:</strong></td><td style='padding:5px; border-bottom:1px solid #eee;'>{$companyName}</td></tr>
                    <tr><td style='padding:5px; border-bottom:1px solid #eee;'><strong>Contact:</strong></td><td style='padding:5px; border-bottom:1px solid #eee;'>{$repName} ({$data['rep_role'] ?? 'N/A'})</td></tr>
                    <tr><td style='padding:5px; border-bottom:1px solid #eee;'><strong>Email:</strong></td><td style='padding:5px; border-bottom:1px solid #eee;'><a href='mailto:{$repEmail}'>{$repEmail}</a></td></tr>
                 </table>

                 <h3 style='margin-top:20px;'>Next Steps</h3>
                 <p>Review the full submission details (including files uploaded) by clicking the link below:</p>
                 <p style='text-align:center;'>
                    <a href='{$adminLink}' style='background:#0d6efd; color:#fff; padding:10px 20px; text-decoration:none; border-radius:5px; display:inline-block;'>View Project #{$projectId} in Admin</a>
                 </p>";
                 
        return $html;
    }

    /**
     * @NEW: Builds the email content for the Client with their generated quote.
     */
    private function clientQuoteTemplate(array $data): string
    {
        $repName      = htmlspecialchars($data['rep_name'] ?? 'Client');
        $companyName  = htmlspecialchars($data['company_name'] ?? 'Your Company');
        $quoteSummary = $data['quote'] ?? ['package_name' => 'N/A', 'total_once_off' => 0];
        $totalCost    = number_format($quoteSummary['total_once_off'], 2);
        $projectId    = $data['project_id'] ?? 'N/A';

        $html = "<h3 style='color:#222;'>Hello {$repName},</h3>
                 <p>Thank you for submitting your project request to **Cloud27**! We're excited to help **{$companyName}** succeed.</p>
                 <p>Based on your requirements, here is your instant quote summary:</p>
                 
                 <div style='border:2px solid #0d6efd; padding:15px; border-radius:8px; margin:20px 0;'>
                    <p style='font-size:18px; font-weight:bold; color:#0d6efd; margin-top:0;'>Estimated Project Quote</p>
                    <p><strong>Package:</strong> {$quoteSummary['package_name']}</p>
                    <p style='font-size:22px; color:#dc3545; font-weight:bold;'>Total Estimated Cost: R{$totalCost}</p>
                 </div>
                 
                 <h4 style='margin-top:20px;'>What Happens Next?</h4>
                 <ol>
                    <li>Our team will review your **full submission and uploaded assets** (Ref: #{$projectId}).</li>
                    <li>We will contact you within **1 business day** to discuss the quote and finalize any remaining details.</li>
                    <li>If you wish to proceed immediately, please reply to this email or call us.</li>
                 </ol>
                 <p style='margin-top:16px; font-weight:bold;'>— The Cloud27 Project Team</p>";
                 
        return $html;
    }

    // --- SENDING METHODS (Two new methods added for onboarding) ---

    // (Original sendAdminEmail and sendClientAutoReply methods here...)

    public function sendAdminEmail(array $data): array { /* ... unchanged ... */ }
    public function sendClientAutoReply(array $data): array { /* ... unchanged ... */ }


    /**
     * @NEW: Sends a notification to the corporate email when a new project is submitted.
     */
    public function sendAdminNewProjectEmail(array $data): array
    {
        if (empty($data['rep_email'])) return [false, 'Representative email missing'];

        $mail = $this->setupMailer();

        try {
            $mail->setFrom($_ENV['SMTP_USER'], $_ENV['SMTP_FROM_NAME'] ?? 'Cloud27 Bot');
            $mail->addAddress($_ENV['SMTP_TO']); // Send to corporate email address
            $mail->addReplyTo($data['rep_email'], $data['rep_name'] ?? '');

            $mail->isHTML(true);
            $mail->Subject = "ACTION: NEW PROJECT LEAD - {$data['company_name']} (#{$data['project_id']})";
            $mail->Body    = $this->cloud27EmailWrapper($this->adminProjectTemplate($data));

            $mail->send();
            return [true, null];
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            error_log('sendAdminNewProjectEmail error: ' . $mail->ErrorInfo);
            return [false, $mail->ErrorInfo ?: $e->getMessage()];
        }
    }

    /**
     * @NEW: Sends the auto-reply confirmation and quote summary to the client.
     */
    public function sendClientQuoteConfirmation(array $data): array
    {
        if (empty($data['rep_email'])) return [false, 'Client email missing'];

        $mail = $this->setupMailer();

        try {
            $mail->setFrom($_ENV['SMTP_USER'], 'Cloud27 Project Management');
            $mail->addAddress($data['rep_email'], $data['rep_name'] ?? '');

            $mail->isHTML(true);
            $mail->Subject = "Project Quote & Confirmation - Ref: #{$data['project_id']}";
            $mail->Body    = $this->cloud27EmailWrapper($this->clientQuoteTemplate($data));

            $mail->send();
            return [true, null];
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            error_log('sendClientQuoteConfirmation error: ' . $mail->ErrorInfo);
            return [false, $mail->ErrorInfo ?: $e->getMessage()];
        }
    }
}