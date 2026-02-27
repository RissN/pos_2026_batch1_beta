<?php
function checkLogin()
{
    if (!isset($_SESSION['USERNAME'])) {
        header("Location: index.php");
        exit();
    }
}
function statusLogin()
{
    if (isset($_SESSION['error'])) {
        echo '<div class="alert alert-danger" id="loginAlert" role="alert">'
            . $_SESSION['error'] .
            '</div>';
        unset($_SESSION['error']);
        echo '<script>
            setTimeout(function(){
                var alertBox = document.getElementById("loginAlert");
                if(alertBox) { alertBox.style.display = "none"; }
            }, 5000); // 5 detik hilang
        </script>';
    }
}
