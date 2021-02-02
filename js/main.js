
let userPanel = document.querySelector(".user-panel-container")

if (userPanel) {

    $(document).ready(function () {
        $(".user-panel-icon").click(function () {
            $(".user-panel-icon").toggleClass(["open"])
            $(".user-menu").slideToggle()
        })
    })
}

let profileEdit = document.querySelector(".profile-edit-form")

if (profileEdit) {
    console.log("Профиль");

    profileEdit.addEventListener('submit', function (evt) {
        evt.preventDefault()
        let formData = new FormData(profileEdit);

        $.ajax({
            url: '/profile/edit',
            method: 'POST',
            xhrFields: {
                withCredentials: true
            },
            dataType: 'json',
            processData: false,
            contentType: false,
            data: formData,
            success: function(data) {
                if (data.ok) {
                    location.reload();
                } else if (!(data.error === undefined)) {
                    if (!(data.error.errorAbout === undefined)) {
                        let formAnswer = document.querySelector(".profile-edit-form-about-answer")
                        formAnswer.classList.remove("d-none")
                        formAnswer.textContent = data.error.errorAbout
                    }
                    if(!(data.error.errorAvatar === undefined)) {
                        let formAnswer = document.querySelector(".profile-edit-form-avatar-answer")
                        formAnswer.classList.remove("d-none")
                        formAnswer.textContent = data.error.errorAvatar
                    }

                }
            },
            error: function(jqXHR) {
                console.log('error')
            }
        })
    })
}

let profileSubscribe = document.querySelector(".profile-subscribe")

if (profileSubscribe) {

    let buttonSubscribeOn = document.querySelector(".profile-subscribe-button-on")

    if (buttonSubscribeOn) {

        buttonSubscribeOn.addEventListener("click", (evt) => {

            $.ajax({
                url: '/profile/subscribe/on',
                method: 'GET',
                xhrFields: {
                    withCredentials: true
                },
                success: function(data) {
                    location.reload();
                },
                error: function(jqXHR) {
                    console.log('error')
                }
            })

        })

    }

    let buttonSubscribeOff = document.querySelector(".profile-subscribe-button-off")

    if (buttonSubscribeOff) {

        buttonSubscribeOff.addEventListener("click", (evt) => {

            $.ajax({
                url: '/profile/subscribe/off',
                method: 'GET',
                xhrFields: {
                    withCredentials: true
                },
                success: function(data) {
                    location.reload()
                },
                error: function(jqXHR) {
                    console.log('error')
                }
            })

        })

    }


}

let subscribeContent = document.querySelector(".subscribe-content")

if (subscribeContent) {

    let subscribeUserButton = document.querySelector(".subscribe-user-button")

    if (subscribeUserButton) {

        subscribeUserButton.addEventListener("click", (evt) => {

            $.ajax({
                url: '/profile/subscribe/on',
                method: 'GET',
                xhrFields: {
                    withCredentials: true
                },
                success: function(data) {
                    location.reload()
                },
                error: function(jqXHR) {
                    console.log('error')
                }
            })

        })

    }

    let subscribeGuestButton = document.querySelector(".subscribe-guest-button")

    if (subscribeGuestButton) {

        subscribeGuestButton.addEventListener("click", (evt) => {

            let emailInput = document.querySelector(".subscribe-email-input")

            let email = emailInput.value
            let regExp = /[a-z0-9]+@[a-z0-9]+\.[a-z0-9]+/i

            if (regExp.test(email)) {

                let inputAnswer = document.querySelector(".subscribe-answer")
                inputAnswer.classList.toggle("d-none", true)

                let data = {
                    email: email,
                }

                $.ajax({
                    url: '/subscriber/new',
                    method: 'POST',
                    dataType: 'json',
                    data: data,
                    success: function(data) {
                        if (data.ok) {
                            let inputAnswer = document.querySelector(".subscribe-answer")
                            inputAnswer.classList.toggle("d-none", false)
                            inputAnswer.textContent = "Вы успешно подписались на рассылку"
                        } else {
                            let inputAnswer = document.querySelector(".subscribe-answer")
                            inputAnswer.classList.toggle("d-none", false)
                            inputAnswer.textContent = data.error;
                        }
                    },
                    error: function(jqXHR) {
                        console.log('error')
                    }
                })

            } else {
                let inputAnswer = document.querySelector(".subscribe-answer")
                inputAnswer.classList.toggle("d-none", false)
                inputAnswer.textContent = "Введенный email не соотвествует формату email."
            }

        })

    }

}

let comments = document.querySelector(".comment")

if (comments) {

    let buttonApproveArr = document.querySelectorAll(".comment-button-approve")

    buttonApproveArr.forEach(function (button) {
        button.addEventListener("click", function (e) {

            let id = e.currentTarget.dataset['id']

            $.ajax({
                url: '/admin/comments/new/check?id=' + id,
                method: 'GET',
                xhrFields: {
                    withCredentials: true
                },
                success: function(data) {
                    location.reload()
                },
                error: function(jqXHR) {
                    console.log('error')
                }
            })

        })
    })


    let buttonDeleteArr = document.querySelectorAll(".comment-button-delete")

    buttonDeleteArr.forEach(function(button) {
        button.addEventListener("click", function (e) {

            let id = e.currentTarget.dataset['id']

            $.ajax({
                url: '/admin/comments/new/delete?id=' + id,
                method: 'GET',
                xhrFields: {
                    withCredentials: true
                },
                success: function(data) {
                    location.reload()
                },
                error: function(jqXHR) {
                    console.log('error')
                }
            })

        })
    })


}