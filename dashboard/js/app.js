$(document).ready(function(){
    
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
    
    if(window.location == "http://localhost/tugasAkhir3/dashboard/beranda.php"){
        $("aside li:nth-child(1)").addClass("active");
        $("title").html("Beranda | Admin Dashboard");
    }else if(window.location == "http://localhost/tugasAkhir3/dashboard/berita.php"){
        $("aside li:nth-child(2)").addClass("active");
        $("title").html("Data Berita | Admin Dashboard");
    }else if(window.location == "http://localhost/tugasAkhir3/dashboard/user.php"){
        $("aside li:nth-child(3)").addClass("active");
        $("title").html("Data User | Admin Dashboard");
    }else if(window.location == "http://localhost/tugasAkhir3/dashboard/kategori.php"){
        $("aside li:nth-child(4)").addClass("active");
        $("title").html("Data Kategori | Admin Dashboard");
    }
    
    // Halaman cari berita dengan ajax
    function cariBeritaAJAX(e){
        var kolomCari = $("#cariBerita").val();
        e.preventDefault();
        $.ajax({
            url: "cari-berita.php",
            data: "c="+kolomCari,
            cache: false,
            type: "GET",
            success: function(data){
                $("#inner-main").html(data);
            }
        })
    }
    // Jalankan fungsi cariAJAX saat:
    // 1. tombol cari ditekan
    // 2. form pencarian diketik
    $("#tombolCari").click(cariBeritaAJAX);
    $("#cariBerita").keyup(cariBeritaAJAX);
    
    // Halaman cari user dengan ajax
    function cariUserAJAX(e){
        var kolomCari = $("#cariUser").val();
        e.preventDefault();
        $.ajax({
            url: "cari-user.php",
            data: "c="+kolomCari,
            cache: false,
            type: "GET",
            success: function(data){
                $("#inner-main").html(data);
            }
        })
    }
    // Jalankan fungsi cariAJAX saat:
    // 1. tombol cari ditekan
    // 2. form pencarian diketik
    $("#tombolCari").click(cariUserAJAX);
    $("#cariUser").keyup(cariUserAJAX);
})