$(document).ready(function () {
  $('#signupForm').submit(function(e){
    e.preventDefault();
    $('#formErrorBox').addClass('d-none').empty();

    var formData = $(this).serialize();
    $.ajax({
      url: '/api/sp/signup',
      type: 'POST',
      data: formData,
      success: function(response){
        if (response && response.success) {
            var successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
            $('#signupForm')[0].reset();
            $('#formErrorBox').addClass('d-none').html('');
        }
        else {
            let errorMsg = response.message || 'Signup failed.';
            $('#formErrorBox').html(errorMsg).removeClass('d-none');
        }
      },
      error: function(xhr){
        let errorMsg = 'Signup failed.';
        if (xhr.responseJSON && xhr.responseJSON.errors) {
            errorMsg = '';
            $.each(xhr.responseJSON.errors, function(field, messages){
                $.each(messages, function(i, msg){
                    errorMsg += '<div>' + msg + '</div>';
                });
            });
        } else if(xhr.responseJSON && xhr.responseJSON.message){
            errorMsg = xhr.responseJSON.message;
        }
        $('#formErrorBox').html(errorMsg).removeClass('d-none');
      }
    });
  });

  $('#stateSelect').on('change', function(){
      loadCities($(this).val(), null);
  });
});

function loadCities(stateId, selectedCityId = null) {
    var citySelect = $('#citySelect');
    citySelect.html('<option value="">Select City</option>');
    if(stateId) {
        $.ajax({
            url: '/admin/get-cities-by-state',
            type: 'GET',
            data: { state_id: stateId },
            success: function(res){
                citySelect.empty();
                citySelect.append('<option value="">Select City</option>');
                if(res.cities){
                    res.cities.forEach(function(city){
                        var selected = (selectedCityId == city.id) ? 'selected' : '';
                        citySelect.append('<option value="'+city.id+'" '+selected+'>'+city.name+'</option>');
                    });
                }
            }
        });
    } else {
        citySelect.html('<option value="">Select City</option>');
    }
}