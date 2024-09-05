document.addEventListener("DOMContentLoaded", function() {
    const menuIcon = document.querySelector('.menu-icon');
    const closeIcon = document.querySelector('.close-icon');
    const navLinks = document.querySelector('.nav-links');

    menuIcon.addEventListener('click', function() {
        navLinks.classList.toggle('active');
        menuIcon.style.display = 'none'; // Hide the hamburger icon
        closeIcon.style.display = 'flex'; // Show the close icon
    });

    closeIcon.addEventListener('click', function() {
        navLinks.classList.toggle('active');
        closeIcon.style.display = 'none'; // Hide the close icon
        menuIcon.style.display = 'flex'; // Show the hamburger icon
    });
});

document.getElementById('contact-form').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission

    var form = this;
    var responseMessage = document.getElementById('response-message');

    var formData = new FormData(form); // Create a FormData object from the form

    var xhr = new XMLHttpRequest(); // Create a new XMLHttpRequest object
    xhr.open('POST', '/submit_form.php', true); // Open a new POST request

    xhr.onload = function() {
        if (xhr.status === 200) {
            // Hide the form and show the response message
            form.style.display = 'none';
            responseMessage.innerText = 'Thank you! Your message has been sent.';
            responseMessage.style.color = 'white';
        } else {
            // Show error message
            form.style.display = 'none';
            responseMessage.innerText = 'Sorry, something went wrong. Please try again later.';
            responseMessage.style.color = 'red';
        }
        responseMessage.style.display = 'block'; // Make the response message visible
    };

    xhr.send(formData); // Send the FormData object
});