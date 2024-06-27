'use strict';

// Show loading UI while page is loading
window.addEventListener('load', function () {
    document.querySelector("#loading-screen").classList.add('d-none')
});

const table = document.querySelector("table")

table.addEventListener("click", function(e) {
    //e.preventDefault()

    // EYE
    if(e.target.classList.contains("fa-eye") || e.target.classList.contains("fa-eye-slash")) {
        let currentTrans = e.target.closest("tr")
        let transaction_id = currentTrans.attributes['data-transaction-id'].value
        let eyeElement = currentTrans.querySelector(".col-eye i")
        let trans_inner = document.querySelectorAll(`[data-transaction-id="${transaction_id}"]`)[1]

        eyeElement.classList.toggle("fa-eye")
        eyeElement.classList.toggle("fa-eye-slash")

        trans_inner.classList.toggle("d-none")

        currentTrans.classList.toggle("open")
    }

    if(e.target.classList.contains("fa-square") || e.target.classList.contains("fa-square-check")) {
        let currentTrans = e.target.closest("tbody tr")

        if(!currentTrans) {
            let currentTrans = e.target.closest("tr")
            let squareElement = currentTrans.querySelector(".col-box i")
            squareElement.classList.toggle("fa-square")
            squareElement.classList.toggle("fa-square-check")

            document.querySelectorAll("tbody tr").forEach(trans => {
                if(!trans.classList.contains("transaction-info") && !trans.classList.contains("empty")) {
                    console.log(trans)

                    let squareElement = trans.querySelector(".col-box i")

                    //squareElement.classList.toggle("fa-square")
                    //squareElement.claswrrrrrsList.toggle("fa-square-check")

                    squareElement.click()
                }
            })
            return
        }

        let transaction_id = currentTrans.attributes['data-transaction-id'].value
        let squareElement = currentTrans.querySelector(".col-box i")
        let trans_inner = document.querySelectorAll(`[data-transaction-id="${transaction_id}"]`)[1]


        squareElement.classList.toggle("fa-square")
        squareElement.classList.toggle("fa-square-check")

        currentTrans.classList.toggle("selected")
        trans_inner.classList.toggle("selected")
    }
})

const formElement = document.querySelector("#transactionForm")
let typetype = document.querySelector('input[name="transaction_typetype"]:checked').value;

let transAmountElem = document.querySelector(".transaction_amount_element")
let breakdownParent = document.querySelector(".breakdown-parent")

const toastLiveExample = document.getElementById('liveToast')
const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample)

let breakItems = breakdownParent.querySelectorAll(".breakdown-item")

formElement.addEventListener("click", function(e) {
    typetype = document.querySelector('input[name="transaction_typetype"]:checked').value;

    switch(typetype) {
        case 'individual':
            breakItems = breakdownParent.querySelectorAll(".breakdown-item")

            transAmountElem.classList.remove("d-none")
            breakdownParent.classList.add("d-none")
            if(transAmountElem.querySelector("input").value == 0) {
                transAmountElem.querySelector("input").value = null
            }

            breakItems.forEach(item => {
                item.querySelectorAll('input').forEach(input => {
                    input.disabled = true;
                })
            })

            break
        case 'set':
            breakItems = breakdownParent.querySelectorAll(".breakdown-item")

            transAmountElem.classList.add("d-none")
            breakdownParent.classList.remove("d-none")
            transAmountElem.querySelector("input").value = 0

            breakItems.forEach(item => {
                item.querySelectorAll('input').forEach(input => {
                    input.disabled = false
                })
            })
            break
    }

    if(e.target.classList.contains("fa-plus")) {
        let number = getRandomNumber(1000, 10000)

        let markUp = `
        <div class="breakdown-item row g-2 border border-opacity-25 p-2 rounded bg-light-subtle">
            <div class="col-md-5">
                <label class="form-label" for="bd_name${number}">Name</label>
                <input class="form-control" type="text" id="bd_name${number}" name="bd_name[]" aria-describedby="bd_name${number}" required>
                <div id="bd_name${number}" class="invalid-feedback">
                    <p></p>
                </div>
            </div>
            <div class="col-md-5">
                <label class="form-label" for="bd_amount${number}">
                Amount
                </label>
                <input class="form-control" type="number" id="bd_amount${number}" name="bd_amount[]" aria-describedby="bd_amount${number}" required="required">
                <div id="bd_amount${number}" class="invalid-feedback">
                    <p></p>
                </div>
            </div>
            <div class="col-md-2 d-flex align-items-center justify-content-center p-2">
                <i class="fa-regular fa-trash-can" aria-hidden="true"></i>
            </div>
        </div>
        `

        let breakdownElementParent = document.querySelector(".breakdown-list")

        breakdownElementParent.insertAdjacentHTML('beforeend', markUp);
    }

    if(e.target.classList.contains("fa-trash-can")) {
        breakItems = breakdownParent.querySelectorAll(".breakdown-item")

        if(breakItems.length > 1) {
            e.target.closest(".breakdown-item").remove()
        } else {
            toastBootstrap.show()
        }
    }
})


let deleteMultipleForm = document.querySelector("#deleteTransactionsForm")

deleteMultipleForm.addEventListener('submit', function(e) {
    e.preventDefault();
    let selectedIds = document.querySelectorAll(".transaction-header.selected")

    selectedIds.forEach(transaction => {
        let trans_id = transaction.attributes['data-transaction-id'].value
        let markUp = `<input type="hidden" name="ids[]" value="${trans_id}">`
        deleteMultipleForm.insertAdjacentHTML('beforeend', markUp);
    })

    //document.getElementById('transactionIds').value = selectedIds.join(',');
    this.submit();
});


let userUpdateForm = document.querySelector("#user_update")
$("#user_update").on('submit', function(e) {
    e.preventDefault();

    userUpdateForm.querySelector("#loader").classList.toggle("d-none")
    userUpdateForm.querySelector(".form-body").classList.toggle("d-none")

    let saveBtn = document.querySelector("button[form='user_update'");
    saveBtn.textContent = "Saving..."
    saveBtn.disabled = true

    let formData = new FormData(this);
    let actionUrl = $(this).attr('action');

    $.ajax({
        type: 'POST',
        url: actionUrl,
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            let markUp = `
                <div class="toast align-items-center text-bg-success position-fixed border-0 fade bottom-0 end-0 m-5 show" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                    <div class="toast-body">
                        ${response.success}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            `;
            document.querySelector("body").insertAdjacentHTML('beforeend', markUp);

            setTimeout(function() {
                window.location = response.url;
            }, 2000);
        },
        error: function(xhr, status, error) {
            let obj = xhr.responseJSON.errors

            let inputs = userUpdateForm.querySelectorAll(`.is-invalid`)
            inputs.forEach(input => {
                input.classList.remove("is-invalid")
            })

            for (let key in obj) {
                if (obj.hasOwnProperty(key)) {
                    let input = userUpdateForm.querySelector(`input[name='${key}']`)
                    if(input) {
                        // console.log(`${key}: ${obj[key]}`);
                        let feeback = userUpdateForm.querySelector(`#${key} p`)
                        input.classList.add("is-invalid")
                        feeback.textContent = obj[key]
                    }
                }
            }

            userUpdateForm.querySelector("#old_password").value = "";
            saveBtn.textContent = "Save"
            saveBtn.disabled = false

            userUpdateForm.querySelector("#loader").classList.toggle("d-none")
            userUpdateForm.querySelector(".form-body").classList.toggle("d-none")
        }
    });
});




function getRandomNumber(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}
