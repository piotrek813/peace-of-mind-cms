document.addEventListener('DOMContentLoaded', function() {
    // Add click handlers to all group field headers
    document.querySelectorAll('.group-field-header').forEach(header => {
        header.addEventListener('click', function() {
            const content = this.nextElementSibling;
            const icon = this.querySelector('.collapse-icon');
            
            // Toggle the content visibility
            content.classList.toggle('collapsed');
            
            // Rotate the icon
            icon.classList.toggle('rotate-180');
        });
    });
}); 