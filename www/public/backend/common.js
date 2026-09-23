$(document).ready(function () {

    $('#infoCategoryForm').on('submit', function (e) {
        e.preventDefault();

        let $form = $(this);
        let url   = $form.attr('action');
        let data  = $form.serialize();

        $('#catError').addClass('d-none').text('');

        $.post(url, data)
            .done(function (res) {
                if (res.success) {
                    $('#infoCategoryList').html(res.html);
                    $form[0].reset();
                }
            })
            .fail(function (xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors || {};
                    if (errors.name) {
                        $('#catError').removeClass('d-none').text(errors.name[0]);
                    }
                }
            });
    });

    document.getElementById('add-feature').addEventListener('click', function () {
        const wrapper = document.getElementById('features-wrapper');
        const row = document.createElement('div');
        row.classList.add('form-row', 'mb-2', 'feature-row');
        row.innerHTML = `
            <div class="col-md-4">
                <input type="text" name="features_keys[]" class="form-control" placeholder="Feature key">
            </div>
            <div class="col-md-6">
                <input type="text" name="features_values[]" class="form-control" placeholder="Value">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-sm remove-feature">X</button>
            </div>
        `;
        wrapper.appendChild(row);
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-feature')) {
            e.target.closest('.feature-row').remove();
        }
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