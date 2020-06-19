<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= $pageTitle; ?> - Мій блог</title>
    <link rel="stylesheet" href="Styles/style.css">
</head>
<body>
    <div class="container">
        <header class="headerInMain">
            <nav>
                <ul class="navTop">
                    <li <?php if ('home' === $currentPage) { ?>class="current"<?php } ?>><a href="index.php">HOME</a></li>
                    <li <?php if ('about' === $currentPage) { ?>class="current"<?php } ?>><a href="aboutme.php">ABOUT ME</a></li>
                    <li <?php if ('contact' === $currentPage) { ?>class="current"<?php } ?>><a href="contact.php">CONTACT ME</a></li>
                </ul>
            </nav>
        </header>
    </div>
    <div class="imgDiv">
        <img class="logo" src="Pictures/logo.png" alt="site logo">
    </div>
    <div class="container1">