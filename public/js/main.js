/**
 * General function to submit a form.
 * @param formData Object containing the key-value params.
 * @param doneCallback Callback function is request succeeded.
 * @param failCallback Callback function is request failed.
 * @param container Any parent element containing the form with the error and search spinner element.
 */
function submitForm(formData, doneCallback, failCallback, container) {
    let errorElement = $(container).find("[data-forms='error-msg']");
    errorElement.html("");

    let searchingElement = $(container).find("[data-forms='search-spinner']");
    searchingElement.css("display", "block");

    $.ajax({
        method: "POST",
        url: PUBLIC_DIR + "api.php",
        data: formData,
        dataType: "json"
    }).done(function(json) {
        if (!json.success) {
            errorElement.html(json.errorMessage);
        }
        doneCallback(json);
    }).fail(function() {
        errorElement.html("Unexpected error");
        failCallback();
    }).always(function() {
        searchingElement.css("display", "none");
    });
}