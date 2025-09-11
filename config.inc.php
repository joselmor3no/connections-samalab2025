<?php

// Servidor PostgreSQL
$conf['servers'][0]['host'] = 'localhost';  // Cambia localhost por la dirección del servidor PostgreSQL si es diferente
$conf['servers'][0]['port'] = 5432;          // Puerto de PostgreSQL (por defecto es 5432)
$conf['servers'][0]['sslmode'] = 'allow';    // Modo de SSL (puede ser allow, prefer, require, disable)
$conf['servers'][0]['defaultdb'] = 'admin_connections'; // Nombre de la base de datos predeterminada
$conf['servers'][0]['pg_dump_path'] = '/usr/bin/pg_dump'; // Ruta al comando pg_dump

// Opciones de autenticación
$conf['servers'][0]['auth'] = 'config';
$conf['servers'][0]['user'] = 'admin';       // Usuario de PostgreSQL
$conf['servers'][0]['password'] = 'conn123'; // Contraseña de PostgreSQL

// Opciones de diseño y tema
$conf['left_width'] = 200; // Ancho del panel izquierdo (en píxeles)
$conf['theme'] = 'default'; // Tema de phpPgAdmin (puede ser default, metro, etc.)

// Otras configuraciones
$conf['extra_login_security'] = false; // Habilitar seguridad adicional (desactivada por defecto)

?>
