function resetFormFields() {
    $('#UserForm').find('input, input:password, select, textarea').val('');
    $('#UserForm').find('.invalid-feedback').remove();
    $('#UserForm').find('.is-invalid').removeClass().addClass('form-control');
}