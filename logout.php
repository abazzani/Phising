<?php
setcookie (hackme, "", time() - 3600);
setcookie(hackme_pass, "", time() - 3600);
setcookie(hackme_username, "", time() - 3600);
session_destroy();
header("Location: index.php");
?>