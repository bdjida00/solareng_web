$(document).ready(function() {
    // When the form is submitted
    $('#upitnik').submit(function(event) {
      // Prevent the form from submitting normally
      event.preventDefault();
      
      // Get the form data
      var formData = $(this).serialize();
      
      // Send an AJAX request to the server
      $.ajax({
        type: 'POST',
        url: 'php/upitnik.php',
        data: formData,
        success: function(response) {
          // Clear the form
          $('#name').val('');
          $('#email').val('');
          $('#broj').val('');
          $('#lokacija').val('');
          $('#potrosnja').val('');
          $('#krov').val('');
          $('#dimenzije').val('');
          $('#orijentacija').val('');
          $('#napomena').val('');
          
          // Show a success message
          $('#status2').html('Vaša poruka je uspješno poslana!');
        },
        error: function(xhr, status, error) {
          // Show an error message
          $('#status').html('Dogodila se greška: ' + error);
        }
      });
    });
  });


