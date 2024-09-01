document.getElementById('contact-form').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission

    var formData = new FormData(this); // Create a FormData object from the form

    var xhr = new XMLHttpRequest(); // Create a new XMLHttpRequest object
    xhr.open('POST', '/submit_form.php', true); // Open a new POST request

    xhr.onload = function() {
        var responseMessage = document.getElementById('response-message');
        if (xhr.status === 200) {
            responseMessage.innerText = 'Thank you! Your message has been sent.';
            responseMessage.style.color = 'white';
        } else {
            responseMessage.innerText = 'Sorry, something went wrong. Please try again later.';
            responseMessage.style.color = 'red';
        }
    };

    xhr.send(formData); // Send the FormData object
});