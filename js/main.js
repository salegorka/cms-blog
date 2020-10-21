
let userPanel = document.querySelector(".user-panel-container")

if (userPanel) {

    $(document).ready(function () {
        $(".user-panel-icon").click(function () {
            $(".user-panel-icon").toggleClass(["open"])
            $(".user-menu").slideToggle()
        })
    })
}

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

let articleEditPanel = document.querySelector(".article-editor-page");

if (articleEditPanel) {

    console.log("EditArticlePAgeStart")

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
            ],
            plugins: {
                upload: {
                    //Реализовать загрузку картинок
                }
            }
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
                    $('.editor-control__answer').removeClass('d-none')
                    $('.editor-control__answer').html(data.message)
                },
                error: function(jqXHR) {
                    $('.editor-control__answer').removeClass('d-none')
                    $('.editor-control__answer').html("Ошибка сохранения статьи. Обратитесь к администратору. Код ошибки: " + jqXHR.status)
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
                    $('.image-form-answer').removeClass('d-none')
                    $('.image-form-answer').html(data.message)
                },
                error: function(jqXHR) {
                    $('.image-form-answer').removeClass('d-none')
                    $('.image-form-answer').html("Ошибка сохранения статьи. Обратитесь к администратору. Код ошибки: " + jqXHR.status)
                }
            })

        })
    })
}

let articleManager = document.querySelector('.article-manager');

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
                success: function () {
                    location.reload();
                },
                error: function () {
                    console.log('Произошла ошибка удаления. Обратитесь к администратору');
                }
            })

            $('#deletingModal').modal('hide');
        })
    })
}