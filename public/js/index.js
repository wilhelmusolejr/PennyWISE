'use strict';

// Show loading UI while page is loading
window.addEventListener('load', function () {
    const loadingScreen = document.getElementById('loading-screen');
    const modals = document.querySelectorAll(".modal")
    const modalButton = document.querySelector(".btn-login")

    modals.forEach(modal => {
        modal.classList.remove("d-none")
    });

    modalButton.click()

    document.title = "Login"
});

$(document).ready(function() {

    const registerForm = document.querySelector("#register_form")
    $('#register_form').on('submit', function(e) {
        e.preventDefault();

        registerForm.querySelector("#loader").classList.toggle("d-none")
        registerForm.querySelector(".form-body").classList.toggle("d-none")

        let btnSubmit = document.querySelector("button[form='register_form'");
        let initialBtn = btnSubmit.textContent
        btnSubmit.textContent = "Signing up..."
        btnSubmit.disabled = true


        let formData = new FormData(this);
        let actionUrl = $(this).attr('action');

        $.ajax({
            type: 'POST',
            url: actionUrl, // Replace with your Laravel route URL
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                window.location.href = response.url;
            },
            error: function(xhr, status, error) {
                let obj = xhr.responseJSON.errors

                let inputs = registerForm.querySelectorAll(`.is-invalid`)
                inputs.forEach(input => {
                    input.classList.remove("is-invalid")
                })

                for (let key in obj) {
                    if (obj.hasOwnProperty(key)) {
                        // console.log(`${key}: ${obj[key]}`);
                        let input = registerForm.querySelector(`input[name='${key}']`)
                        if(input) {
                            let feeback = registerForm.querySelector(`#${key} p`)
                            input.classList.add("is-invalid")
                            feeback.textContent = obj[key]
                        }
                    }
                }
                registerForm.querySelector("#password").value = "";
                registerForm.querySelector("#password_confirmation").value = "";
                btnSubmit.textContent = initialBtn
                btnSubmit.disabled = false

                registerForm.querySelector("#loader").classList.toggle("d-none")
                registerForm.querySelector(".form-body").classList.toggle("d-none")
            }
        });

    });

    const loginForm = document.querySelector("#login_form")
    $('#login_form').on('submit', function(e) {
        e.preventDefault();

        loginForm.querySelector("#loader").classList.toggle("d-none")
        loginForm.querySelector(".form-body").classList.toggle("d-none")

        let btnSubmit = document.querySelector("button[form='login_form'");
        let initialBtn = btnSubmit.textContent
        btnSubmit.textContent = "Logging in..."
        btnSubmit.disabled = true

        let formData = new FormData(this);
        let actionUrl = $(this).attr('action');

        $.ajax({
            type: 'POST',
            url: actionUrl, // Replace with your Laravel route URL
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                window.location.href = response.url;
            },
            error: function(xhr, status, error) {
                let obj = xhr.responseJSON.errors

                for (let key in obj) {
                    if (obj.hasOwnProperty(key)) {
                        let input = document.querySelector(`#login_form input[name='${key}']`)
                        if(input) {
                            let feeback = loginForm.querySelector(`#${key} p`)
                            input.classList.add("is-invalid")
                            feeback.textContent = obj[key]
                        }
                    }
                }

                loginForm.querySelector("#login_password").value = "";
                btnSubmit.textContent = initialBtn
                btnSubmit.disabled = false

                loginForm.querySelector("#loader").classList.toggle("d-none")
                loginForm.querySelector(".form-body").classList.toggle("d-none")
            }
        });

    });
});
