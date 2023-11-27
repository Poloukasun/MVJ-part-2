import {viderContainer} from "./functions.js";

const pages = {
    "0" : () => {
        changeTxt("À propos de");

        $('.photo-grid').hide();

        $('.desc').hide();
        $(".name").text("");
        $('.container').show();
    },
    "1" : () => {
        changeTxt("Description MVJ");
        $('.container').hide();
        $('.membre').hide();
        $('.desc').show();
    },
    "2" : () => {

        $('.desc').hide();
        $('.membre').show();

        changeTxt("Realisé par");
        $(".name").text("Mikaël Lévesque");

        changeImg("./aboutImg/6542a1e72afff-Lvesque----------.jpeg");
    },
    "3" : () => {
        $(".name").text("Vlad Lukyanov");
        changeImg("./aboutImg/6542a04696801-Lukyanov.jpg");
    },
    "4" : () => {
        changeTxt("Realisé par");
        $('.photo-grid').hide();    
        $('.membre').show();
        $(".name").text("Jean-Sébastien Labrie");
        changeImg("./aboutImg/6542ade03f625-Labrie.jpeg");
    },
    "5" : () => {
        changeTxt("Quelques images");
        $('.container').hide();
        $('.membre').hide();
        $('.photo-grid').show();
    }
}

function changeImg(src) {
    $(".img").css('background-image', 'url(' + src + ')');
}

function changeTxt(txt) {
    $('.titre-desc').text(txt);
}

$(() => {
    $('.desc').hide();
    $('.membre').hide();
    $('.photo-grid').hide();    
    
    $('#btnR').click(() => {
        incPage();
    });

    $("#btnG").click(() => {
        decPage()
    });

    function incPage() {
        let num = getPage();
        if(num + 1 > 5) {
            num = 0;
            $('.nb-p').html(`<i>${num}</i>`);
        }
        else {
            $('.nb-p').html(`<i>${num+=1}</i>`);
        }
        pages[num]();
    }

    function decPage() {
        let num = getPage();
        if(num - 1 === - 1) {
            num = 5;
            $('.nb-p').html(`<i>${num}</i>`);
        } else {
            $('.nb-p').html(`<i>${num-=1}</i>`);
        }
        pages[num]();


    }
    
    function getPage () {
        return parseInt($('.nb-p').text());
    }


});