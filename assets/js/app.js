$(document).ready(function(){
/*
    var loading = document.querySelector(".loading");
      window.addEventListener("load",function(){
      $(loading).css("display","none");
    })
*/
    // Halaman Edit Berita
    $("#edit-gambar").hide();
    $("#eg").click(function(){
        $("#edit-berita").animate({
            height: "toggle"
        }, 500, "linear", function(){
            $("#eg2").hide("fast");
            $("#edit-gambar").show("fast");
        })
    })


    // Halaman Search dengan AJAX
    function cariAJAX(e){
        var kolomCari = $("#kolomCari").val();
        e.preventDefault();
        $.ajax({
            url: "search.php",
            data: "c="+kolomCari,
            cache: false,
            type: "GET",
            success: function(data){
                $("#main").html(data);
            }
        })
    }
    // Jalankan fungsi cariAJAX saat:
    // 1. tombol cari ditekan
    // 2. form pencarian diketik
    $("#tombolCari").click(cariAJAX);
    $("#kolomCari").keyup(cariAJAX);

})
