isMobile = false;
$(document).ready(function(e){
    ismobile();
    if(typeof $('.sel_status').select2 === 'function' ){
        $('.sel_status').select2();
    }

    function ismobile(){
        if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) 
    || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) { 
            $("#sidebarToggleTop").trigger("click");
            isMobile = true;
            return true;
        }
    }

    window.onresize = (function(e){
        if(ismobile() || window.innerWidth<798){
            $("#sidebarToggleTop").trigger("click");
        }
    });
    

    // var circle = $(".circle.per-25");
    // for(var i=0; i<circle.length; i++){
    //     var p = $($($(".circle.per-25")[i]).children()).html().trim();
    //     //$($(".circle.per-25")[i]).css("background-image", 'conic-gradient(#5F33A7 0%, #d4cfdb 0%)')
    //     $($(".circle.per-25")[i]).animate({
    //         "background-image" : "conic-gradient(#5F33A7 "+ p +", #d4cfdb 0%)",
    //     }, 60000 );
    // }

    // //GET PROSENTASE INNER
    // var numbersAngka = [];
    // var numbersDonat = [];
    // var inner = $(".inner");
    // for(var i=0; i<inner.length; i++){
    //     numbersAngka.push(0);
    //     numbersDonat.push(0);
    //     animateNumberAndDonat(inner[i], prosentase[i], i);
    // }

    // //UPDATE DATA PER 60 DETIK
    // //updateData(url);
    // var intervalUpdateData = setInterval(function() {
    //     updateData(url);
    // }, 120000);

    // $(window).resize(function(e){
    //     wScroll = $(this).scrollTop();
    //     scrollResizeEffect(wScroll)
    // });

    // //PARALLAX EFFECT
    // $(window).scroll(function(e){
    //     wScroll = $(this).scrollTop();
    //     scrollResizeEffect(wScroll)
    // });

    // function scrollResizeEffect(wScroll){
    //     if(is_portrait()){
    //         $(".navbar-top > .container > *").css({
    //             "transform"     : "translate(0px, " + wScroll/15 + "%)",
    //             "text-shadow"   : "0 0 "+ wScroll/20 +"px #fff",
    //             "color"         : "transparent"
    //         });

    //         $(".navbar-top > .container > .logo").css({
    //             "filter": "blur("+(wScroll/30)+"px)"
    //         });
    //     }
    // }

    // $(".navbar-top .realisasi-pajak-daerah-4nsxCa, .navbar-top .logo").click(function(e){
    //     window.location.href = base_url;
    // });

    // function animateNumberAndDonat(element, target, index){
    //     var intervalAngka = setInterval(function() {
    //         $(element).text(numbersAngka[index].toFixed(2) + "%");
    //         if (numbersAngka[index] >= target) {
    //             $(element).text(target + "%");
    //             clearInterval(intervalAngka)
    //         };
    //         numbersAngka[index]+=0.10;
    //     }, 5);

    //     var intervalDonat = setInterval(function() {
    //         $($(".circle.per-25")[index]).css("background-image", "conic-gradient("+ color[index] +" "+ numbersDonat[index] +"%, #d4cfdb 0%)");
    //         if (numbersDonat[index] >= target) {
    //             $($(".circle.per-25")[index]).css("background-image", "conic-gradient("+ color[index] +" "+ target +"%, #d4cfdb 0%)");
    //             clearInterval(intervalDonat)
    //         };
    //         numbersDonat[index]+=0.10;
    //     }, 5);
    // }

    // function animate(elSection, elParent, elTop, elDelay){
    //     if($(elSection).length>0){
    //         if(wScroll > $(elSection).offset().top-elTop){
    //             $(elParent).addClass("animate");
    //             var el = $(elParent + ".animate").children();
                
    //             $(el).each(function(e){
    //                 setTimeout(function(){ 
    //                     $(el).eq(e).addClass("show");
    //                     if(e==el.length-1){
    //                         $(elParent).removeClass("animate");
    //                     }
    //                 }, elDelay * e);
    //             })
    //         }
    //     }
    // }

    // function is_portrait(){
    //     if(screen.availHeight > screen.availWidth || window.innerHeight > window.innerWidth) return true;
    //     return false;
    // }

    // function updateData(url){
    //     maskText();
    //     $.ajax({
    //         url         : url,
    //         method      : "post",
    //         data        : {},
    //         dataType    : "json",
    //         success     : function(e){
    //             //KONDISI UNTUK HALAMAN DASHBOARD
    //             if($(".realisasi-total")[0]) {
    //                 var targetTotal = 0;
    //                 var realisasiTotal = 0;
    //                 var prosentaseTotal = 0;
    //                 var txtTargetTotal = '<small>Target '+ new Date().getFullYear(); +'</small><br>Rp. '+ targetTotal.toFixed(2);

    //                 for(var key in e){
    //                     prosentase[0] = e[key].total.Prosentase.number;
    //                     targetTotal = e[key].total.Target.formatted;
    //                     realisasiTotal = e[key].total.Realisasi.formatted;
    //                     prosentaseTotal = e[key].total.Prosentase.formatted;
    //                     txtTargetTotal = '<small>Target '+ new Date().getFullYear() +'</small><br>Rp. '+ targetTotal;
    //                 }
                
    //                 $(".realisasi-total .rp-1000000000-4nsxCa").html(realisasiTotal);
    //                 $(".realisasi-total .circle > .inner").html(prosentaseTotal + '%');
    //                 $(".realisasi-total .target-rp-10000000000-qxpxEG").html(txtTargetTotal);

    //                 numbersAngka[0] = 0;
    //                 numbersDonat[0] = 0;
    //                 animateNumberAndDonat($(".inner")[0], prosentase[0], 0);
    //             }else{//KONDISI UNTUK HALAMAN DETAIL PAJAK DAERAH
    //                 numbersAngka = [];
    //                 numbersDonat = [];
    //                 prosentase   = [];
    //                 var i = 0;
    //                 for(var key in e){
    //                     numbersAngka.push(0);
    //                     numbersDonat.push(0);
    //                     prosentase.push(e[key].Prosentase.number);
    //                     element = ".item-" + key.replaceAll(" ", "-").toLowerCase();
    //                     txtTargetTotal = '<small>Target '+ new Date().getFullYear() +'</small><br>Rp. '+ e[key].Target.formatted;
    //                     $(element + " .rp-1000000000-4nsxCa").html("Rp. " + e[key].Realisasi.formatted);
    //                     $(element + " .circle > .inner").html("Rp. " + e[key].Prosentase.formatted);
    //                     $(element + " .target-rp-10000000000-qxpxEG").html(txtTargetTotal);

                        
    //                     animateNumberAndDonat($(".inner")[i], prosentase[i], i);
    //                     i++;
    //                 }
    //             }

    //             maskTextRemove();
    //         }
    //     });
    // }

    // function maskText(){
    //     $(".total-realisasi-pbb-4nsxCa").addClass("loading");
    //     $(".rp-1000000000-4nsxCa").addClass("loading");
    //     $(".circle.per-25 > .inner").addClass("loading");
    //     $(".target-rp-10000000000-qxpxEG").addClass("loading");
    // }

    // function maskTextRemove(){
    //     $(".total-realisasi-pbb-4nsxCa").removeClass("loading");
    //     $(".rp-1000000000-4nsxCa").removeClass("loading");
    //     $(".circle.per-25 > .inner").removeClass("loading");
    //     $(".target-rp-10000000000-qxpxEG").removeClass("loading");
    // }

    // function updateView(data){

    // }
})