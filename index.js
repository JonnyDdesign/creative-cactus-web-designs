document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('contact-form');
    var responseMessage = document.getElementById('response-message');

    if (form && responseMessage) {
        form.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            var formData = new FormData(form); // Create a FormData object from the form
            var xhr = new XMLHttpRequest(); // Create a new XMLHttpRequest object
            xhr.open('POST', '/submit_form.php', true); // Open a new POST request

            xhr.onload = function() {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);

                    // Show success or failure message below the form
                    responseMessage.innerText = response.message;
                    responseMessage.style.color = (response.status === 'success') ? 'white' : 'red';
                } else {
                    // Show generic error message in case of network failure
                    responseMessage.innerText = 'Sorry, something went wrong.Please try again later.';
                    responseMessage.style.color = 'red';
                }
                responseMessage.style.display = 'block';
            };
            
            xhr.send(formData); // Send the FormData object
        });
    } else {
        console.error('Form or response message not found');
    }
});