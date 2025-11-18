<h2>Get in Touch</h2>
<p>We'd love to hear about your project. Fill out the form below and we'll get back to you shortly.</p>

<div style="max-width: 600px; margin-top: 30px;">
    
    <div id="form-status-message" style="display: none; padding: 15px; margin-bottom: 20px; border-radius: 4px; font-weight: bold;">
        </div>

    <form id="contactForm" action="<?= BASE_PATH ?>/contact" method="POST"> 
        
        <div style="margin-bottom: 15px;">
            <label for="name" style="display: block; margin-bottom: 5px;">Your Name:</label>
            <input type="text" id="name" name="name" required 
                   style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
        </div>
        
        <div style="margin-bottom: 15px;">
            <label for="email" style="display: block; margin-bottom: 5px;">Your Email:</label>
            <input type="email" id="email" name="email" required 
                   style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
        </div>
        
        <div style="margin-bottom: 15px;">
            <label for="message" style="display: block; margin-bottom: 5px;">Project Details:</label>
            <textarea id="message" name="message" rows="5" required 
                      style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;"></textarea>
        </div>
        
        <button type="submit" class="btn-primary" id="submit-btn">Send Inquiry</button>
    </form>
    
    <p style="margin-top: 20px; padding: 10px; background-color: #e9ecef; border: 1px solid #ccc; color: #6c757d;">
        ✅ **Phase 2 Complete:** Submission logic is now active and uses **AJAX** for a non-refreshing user experience.
    </p>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contactForm');
    const statusDiv = document.getElementById('form-status-message');
    const submitBtn = document.getElementById('submit-btn');

    // Helper function to show status messages
    function showStatus(message, success) {
        statusDiv.textContent = message;
        statusDiv.style.display = 'block';
        
        // Apply inline styling based on success/failure
        if (success) {
            statusDiv.style.backgroundColor = '#d4edda'; // Light green
            statusDiv.style.color = '#155724';           // Dark green text
            statusDiv.style.border = '1px solid #c3e6cb';
        } else {
            statusDiv.style.backgroundColor = '#f8d7da'; // Light red
            statusDiv.style.color = '#721c24';           // Dark red text
            statusDiv.style.border = '1px solid #f5c6cb';
        }
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault(); // STOP the page refresh!
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.textContent = 'Sending...';
        statusDiv.style.display = 'none'; // Hide previous messages

        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            // Restore proper JSON parsing check
            const contentType = response.headers.get("content-type");
            if (contentType && contentType.indexOf("application/json") !== -1) {
                return response.json();
            } else {
                // Should no longer happen, but good practice to keep the error check
                throw new Error("Server response was not JSON.");
            }
        })
        .then(data => {
            if (data.success) {
                showStatus(data.message, true);
                form.reset(); // Clear the form on success
            } else {
                showStatus(data.message, false);
            }
        })
        .catch(error => {
            // This catches network issues or the error thrown above
            console.error('Submission Error:', error);
            showStatus('A critical error occurred. Please check the browser console and server logs.', false);
        })
        .finally(() => {
            // Always restore button state
            submitBtn.disabled = false;
            submitBtn.textContent = 'Send Inquiry';
        });
    });
});
</script>