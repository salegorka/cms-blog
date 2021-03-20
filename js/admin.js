let adminPage = document.querySelector(".admin-panel")

if (adminPage) {
    let settingsButton = document.querySelector(".admin-panel-settings-button")

    if (settingsButton) {
        settingsButton.addEventListener("click", function () {

            let value = document.querySelector(".admin-panel-settings-select").value

            console.log(value)

            $.ajax({
                url: '/admin/settings?count=' + value,
                xhrFields: {
                    withCredentials: true
                },
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    if (data.result === 'success') {
                        location.assign('/')
                    }
                },
                error: function () {
                    console.log("Произошла ошибка удаления. Обратитесь к администратору")
                }
            })

        })
    }
}

let articleEditPanel = document.querySelector(".article-editor-page")

if (articleEditPanel) {

    function contentSwitcher(currentContent)
    {
        switch(currentContent) {
            case 'name':
                $('.article-content__name').html($('.article-editor').trumbowyg('html'))
                break
            case 'descr':
                $('.article-content__description').html($('.article-editor').trumbowyg('html'))
                break
            case 'text':
                $('.article-content__text').html($('.article-editor').trumbowyg('html'))
                break
        }
    }

    $(function() {
        $('.article-editor').trumbowyg({
            btns: [
                ['viewHTML'],
                ['undo', 'redo'], // Only supported in Blink browsers
                ['formatting'],
                ['strong', 'em', 'del'],
                ['superscript', 'subscript'],
                ['link'],
                ['insertImage'],
                ['upload'],
                ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
                ['unorderedList', 'orderedList'],
                ['horizontalRule'],
                ['removeformat'],
                ['fullscreen']
            ]
        });

        $('.article-editor').trumbowyg('html', $('.article-content__name').html());
        let currentContent = 'name';

        $('#editor-name').click(function () {
            contentSwitcher(currentContent);
            $('.article-editor').trumbowyg('html', $('.article-content__name').html());
            currentContent = 'name';
        })

        $('#editor-descr').click(function () {
            contentSwitcher(currentContent);
            $('.article-editor').trumbowyg('html', $('.article-content__description').html());
            currentContent = 'descr';
        })

        $('#editor-text').click(function () {
            contentSwitcher(currentContent);
            $('.article-editor').trumbowyg('html', $('.article-content__text').html());
            currentContent = 'text';
        })

        $('#editor-save').click(function () {

            contentSwitcher(currentContent);

            let data = {}

            data.id = $('#editor-save').data('id')
            data.name = $('.article-content__name').html()
            data.descr = $('.article-content__description').html()
            data.text = $('.article-content__text').html()

            console.log(data)

            $.ajax({
                url: '/admin/article/edit',
                xhrFields: {
                    withCredentials: true
                },
                method: 'POST',
                dataType: 'json',
                data: data,
                success: function(data) {
                    $('.editor-control__answer').removeClass('d-none').html(data.message)
                },
                error: function(jqXHR) {
                    $('.editor-control__answer').removeClass('d-none').html("Ошибка сохранения статьи. Обратитесь к администратору. Код ошибки: " + jqXHR.status)
                }
            })
        })

        $('#image-save').click(function () {

            let data = {}

            data.id = $('#editor-save').data('id')
            data.img = $('#article-image-link').val()

            console.log(data);

            $.ajax({
                url: '/admin/article/edit',
                xhrFields: {
                    withCredentials: true
                },
                method: 'POST',
                dataType: 'json',
                data: data,
                success: function(data) {
                    console.log(data);
                    $('.image-form-answer').removeClass('d-none').html(data.message)
                },
                error: function(jqXHR) {
                    $('.image-form-answer').removeClass('d-none').html("Ошибка сохранения статьи. Обратитесь к администратору. Код ошибки: " + jqXHR.status)
                }
            })

        })

    })

    let buttonShowOn = document.querySelector(".article-editor-button-show-on")

    if (buttonShowOn) {
        buttonShowOn.addEventListener("click", (evt) => {

            let id = buttonShowOn.dataset['id'];

            $.ajax({
                url: '/admin/article/show?id=' + id,
                method: 'GET',
                xhrFields: {
                    withCredentials: true
                },
                dataType: 'json',
                success: function(data) {
                    if (data.result === 'success') {
                        location.reload();
                    }
                },
                error: function(jqXHR) {
                    console.log('error')
                }
            })


        })
    }

    let buttonShowOff = document.querySelector(".article-editor-button-show-off")

    if (buttonShowOff) {
        buttonShowOff.addEventListener("click", (evt) => {

            let id = buttonShowOff.dataset['id'];

            $.ajax({
                url: '/admin/article/hide?id=' + id,
                method: 'GET',
                xhrFields: {
                    withCredentials: true
                },
                dataType: 'json',
                success: function(data) {
                    if (data.result === 'success') {
                        location.reload();
                    }
                },
                error: function(jqXHR) {
                    console.log('error')
                }
            })

        })
    }

    let buttonNotification = document.querySelector(".article-editor-notification-button")

    if (buttonNotification) {
        buttonNotification.addEventListener("click", function() {

            let id = buttonNotification.dataset['id']

            $.ajax({
                url: '/admin/article/notification?id=' + id,
                method: 'GET',
                xhrFields: {
                    withCredentials: true
                },
                dataType: 'json',
                success: function(data) {
                    if (data.result === 'success') {
                        let answer = document.querySelector(".article-editor-notification-answer")
                        answer.textContent = data.message;
                    } else {
                        let answer = document.querySelector(".article-editor-notification-answer")
                        answer.textContent = "Произошла неизвестная ошибка. Обратитесь к администратору."
                    }
                },
                error: function(jqXHR) {
                    console.log('error')
                }
            })

        })

    }
}

let articleManager = document.querySelector('.article-manager')

if (articleManager) {

    $(function () {
        $('.chunk__form').submit(function (evt) {
            evt.preventDefault()
            let chunk = $('.chunk__select').val()
            window.location = '/admin/article?chunk=' + chunk
        })

        let deletingArticleID = 0;

        $('.admin-article__delete').click(function (event) {
            $('#deletingArticleName').html($('#nameArticle' + this.dataset.id).html())
            deletingArticleID = this.dataset.id
        })

        $('#buttonDeleteArticle').click(function () {

            $.ajax({
                url: '/admin/article/delete?id=' + deletingArticleID,
                xhrFields: {
                    withCredentials: true
                },
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    if (data.result === 'success') {
                        location.reload();
                    }
                },
                error: function () {
                    console.log('Произошла ошибка удаления. Обратитесь к администратору');
                }
            })

            $('#deletingModal').modal('hide');
        })
    })
}

let pageEditor = document.querySelector(".page-editor")

if (pageEditor) {

    $(function () {
        $('.page-editor__editor').trumbowyg({
            btns: [
                ['viewHTML'],
                ['undo', 'redo'], // Only supported in Blink browsers
                ['formatting'],
                ['strong', 'em', 'del'],
                ['superscript', 'subscript'],
                ['link'],
                ['insertImage'],
                ['upload'],
                ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
                ['unorderedList', 'orderedList'],
                ['horizontalRule'],
                ['removeformat'],
                ['fullscreen']
            ],
            plugins: {
                upload: {
                    //Реализовать загрузку картинок
                }
            }
        })
        $('.page-editor-control__save').click(function (event) {

            let data = {}

            data.id = $('.page-editor__page-id').text();
            data.name = $('.page-editor-control__name-input').val();
            data.link = $('.page-editor-control__link-input').val();
            data.content = $('.page-editor__editor').html();

            console.log(data);

            $.ajax({
                url: '/admin/pages/edit',
                method: 'POST',
                xhrFields: {
                    withCredentials: true
                },
                dataType: 'json',
                data: data,
                success: function(data) {
                    console.log(data);
                    $('.page-editor-control__answer').removeClass('d-none').html(data.message)
                },
                error: function(jqXHR) {
                    $('.page-editor-control__answer').removeClass('d-none').html("Ошибка сохранения статьи. Обратитесь к администратору. Код ошибки: " + jqXHR.status)
                }
            })

        })

    })

}

let pageManager = document.querySelector(".admin-page")

if (pageManager) {

    let deletingPageId = 0;

    $('.page-admin__delete').click( function (event) {
        deletingPageId = this.dataset.id
        $('#deletingPageName').html($('#deletingPageNameId' + deletingPageId).html())
        console.log($('#deletingPageNameId' + deletingPageId).html())
    })

    $('#buttonDeletePage').click( function () {

        $.ajax({
            url: '/admin/page/delete?id=' + deletingPageId,
            xhrFields: {
                withCredentials: true
            },
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                if (data.result === 'success') {
                    location.reload();
                }
            },
            error: function () {
                console.log("Произошла ошибка удаления. Обратитесь к администратору")
            }
        })
    })

}

let commentList = document.querySelector(".old-comments-list")

if (commentList) {

    let deletingCommentId = 0;

    let buttonsDelete = document.querySelectorAll(".old-comments-comment-delete");

    buttonsDelete.forEach((button) => {
        button.addEventListener('click', function(evt) {
            deletingCommentId = evt.currentTarget.dataset['id']
        })
    })

    document.querySelector("#deleteCommentButton").addEventListener('click', function (evt) {
        if (deletingCommentId != 0) {

            $.ajax({
                url: '/admin/comments/delete?id=' + deletingCommentId,
                xhrFields: {
                    withCredentials: true
                },
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    if (data.result === 'success') {
                        location.reload()
                    }
                },
                error: function () {
                    console.log("Произошла ошибка удаления. Обратитесь к администратору")
                }
            })

            deletingCommentId = 0;
        }
    })



}

let newComments = document.querySelector(".new-comments-comment")

if (newComments) {

    let buttonsCheck = document.querySelectorAll(".new-comments-comment-check")

    buttonsCheck.forEach((button) => {
        button.addEventListener('click', function(evt) {

            $.ajax({
                url: '/admin/comments/new/check?id=' + evt.currentTarget.dataset['id'],
                xhrFields: {
                    withCredentials: true
                },
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    if (data.result === 'success') {
                        location.reload()
                    }
                },
                error: function () {
                    console.log("Произошла ошибка запроса. Обратитесь к администратору")
                }
            })


        })
    })

    let buttonsDelete = document.querySelectorAll(".new-comments-comment-delete")

    buttonsDelete.forEach((button) => {
        button.addEventListener('click', function(evt) {

            $.ajax({
                url: '/admin/comments/new/delete?id=' + evt.currentTarget.dataset['id'],
                xhrFields: {
                    withCredentials: true
                },
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    if (data.result === 'success') {
                        location.reload()
                    }
                },
                error: function () {
                    console.log("Произошла ошибка удаления. Обратитесь к администратору")
                }
            })

        })
    })

}

let userInfo = document.querySelector(".user-info-panel")

if (userInfo) {

    let buttonDelete = document.querySelector("#deleteUserButton")

    buttonDelete.addEventListener('click', (evt) => {

        $.ajax({
            url: '/admin/users/delete?id=' + buttonDelete.dataset['id'],
            xhrFields: {
                withCredentials: true
            },
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                if (data.result === 'success') {
                    location.assign('/admin/users')
                }
            },
            error: function () {
                console.log("Произошла ошибка удаления. Обратитесь к администратору")
            }
        })

    })

    let buttonSelectRole = document.querySelector(".user-info-select-button")

    buttonSelectRole.addEventListener("click", function () {

        let selectedValue = document.querySelector(".user-info-select").value

        $.ajax({
            url: '/admin/users/role?id=' + buttonSelectRole.dataset['id'] + "&role=" + selectedValue,
            xhrFields: {
                withCredentials: true
            },
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                if (data.result === 'success') {
                    location.reload()
                }
            },
            error: function () {
                console.log("Произошла обновления роли. Обратитесь к администратору")
            }
        })


    })

}
