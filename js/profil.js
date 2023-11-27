$(document).ready(function () {
    let videos = document.querySelectorAll("video");
    videos.forEach(video => {
        video.load();
        video.addEventListener("click", () => {
            videos.forEach(otherVideo => {
                if (otherVideo !== video && !otherVideo.paused) {
                    otherVideo.pause();
                }
            });
        });
    });

    $(document).scroll(function () {
        videos.forEach((video) => {
            if (!video.paused) {
                video.pause();
            }
        });
    });

    $(".titre").on("click", (e) => {
        window.location.href = "./index.php?id=" + e.target.getAttribute("idpub");
    });
});