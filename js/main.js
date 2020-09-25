
let userPanel = document.querySelector(".user-panel-container");

if (userPanel) {
    console.log("Start");

    $(document).ready(function () {
        $(".user-panel-icon").click(function () {
            $(".user-panel-icon").toggleClass(["open"]);
            $(".user-menu").slideToggle();
        })
    })
}