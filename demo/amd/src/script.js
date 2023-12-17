define([], function () {
  return {
    user: function () {
      $(document).ready(function () {
        $('#sendmail').on('click', function (e) {
          e.preventDefault();
          var value = $(this).attr('id');
          if (confirm('Do you want to send mail to users.')) {
            $.ajax({
              type: "POST",
              url: "ajax.php",
              data: {
                value: value,
              },
              success: function (result) {
                alert('Mail has been sent successfully.');
              },
              error: function () {
                console.error(result);
              }
            });
          }
        });
      });
    }
  };
});
