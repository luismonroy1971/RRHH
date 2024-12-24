<?php ; 
// /Views/Layouts/Header.php
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Gestor Documental' ?></title>
    <link rel="stylesheet" href="/Css/Styles.css">
</head>
<body>
    <header>
        <h1>Gestor Documental RRHH</h1>
        <nav>
            <ul>
                <li><a href="/colaboradores">Colaboradores</a></li>
                <li><a href="/documentos">Documentos</a></li>
                <li><a href="/legajo">Legajo</a></li>
                <li><a href="/usuarios">Usuarios</a></li>
                <li><a href="/logout">Salir</a></li>
            </ul>
        </nav>
    </header>
    <main>
