$(document).ready(function() {
    $('#scheduleForm').on('submit', function(e) {
        e.preventDefault(); // Prevent traditional form submission

        // Collect form data
        var formData = $(this).serialize();

        // AJAX submission
        $.ajax({
            type: 'POST',
            url: 'save_schedule.php', // Make sure this matches your PHP file name
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Show success message
                    alert(response.message);
                    
                    // Reload the page
                    location.reload();
                } else {
                    // Show error message
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                // Parse the JSON error response
                try {
                    var errorResponse = JSON.parse(xhr.responseText);
                    alert(errorResponse.message || 'An error occurred');
                } catch(e) {
                    alert('An unexpected error occurred');
                }
            }
        });
    });
});