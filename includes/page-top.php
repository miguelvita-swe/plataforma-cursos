<?php
// Minimal page-top include with navigation
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link href="/css/default.css" rel="stylesheet" />
    <title>Plataforma de Cursos</title>
</head>
<body>
    <header>
        <nav class="navbar">
            <a class="brand" href="/">Plataforma</a>
            <ul class="nav">
                <li class="nav-item"><a href="/">Home</a></li>
                <li class="nav-item dropdown">
                    <a href="#">Cursos</a>
                    <ul class="dropdown-menu">
                        <li><a href="/cursos.php?area=programming">Programação</a></li>
                        <li><a href="/cursos.php?area=design">Design</a></li>
                        <li><a href="/cursos.php?area=business">Negócios</a></li>
                        <li><a href="/cursos.php">Todos</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </header>

    <main class="container">
