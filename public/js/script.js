$(document).ready(function(){

    // JS SOCIAL BUBBLE
    $('.block-icones').click(function(){
        $(this).toggleClass('open');
        if(!$(this).hasClass('open')){
            $('.social-button').css('animation','flip-back  0.5s')
        }else{
            $('.social-button').css('animation','flip  0.5s')
        }
    });
});