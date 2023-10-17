  $(document).ready(function() { 
    $('#kelompok').change(function() {
      $.ajax({
        type: "POST",
        url: "pendaftar/fakultas",
        data: {
          kelompok: $('#kelompok').val()
        }, 
        success: function(response) {
          $('#fakultas').empty()
          $('#fakultas').append(response);
        }
      });
    });
  });