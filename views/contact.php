<h2>Get in Touch</h2>
<p>We'd love to hear about your project. Fill out the form below and we'll get back to you shortly.</p>

<div style="max-width: 600px; margin-top: 30px;">
    <form id="contactForm" action="/contact" method="POST">
        
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
        
        <button type="submit" class="btn-primary">Send Inquiry</button>
    </form>
    
    <p style="margin-top: 20px; padding: 10px; background-color: #fff3cd; border: 1px solid #ffeeba; color: #856404;">
        *Note: In Phase 1, this form visually exists. The submission logic (validation, email sending) will be fully implemented in **Phase 2**.*
    </p>
</div>