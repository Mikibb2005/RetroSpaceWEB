-- PostgreSQL data import
-- Disable triggers temporarily to allow data insertion
SET session_replication_role = 'replica';

INSERT INTO foro_comentarios VALUES (1,'hola, primer coment','2025-11-22 20:35:08',3,1,NULL),
(2,'Y se puede responder de tranqui','2025-11-22 20:35:20',3,1,1),
(3,'probando interaccion entre dos cuentas','2025-11-22 20:41:45',4,1,NULL);
INSERT INTO foro_hilos VALUES (1,'Primer Hilo creado','Hola, soy Miki es desarrollador de esta pagina web, esto solo es el primer hilo que creo y lo voy a utilizar para saludar a todo el que entre nuevo en este foro.\r\nUn saludo.','general','2025-11-22 20:12:19',3,0,NULL);
INSERT INTO posts_diario VALUES (1,'Primer post en RetroSpace','¡Bienvenidos a mi diario! Este es el primer post.\r\n\r\nAqui como su propio nombre indica voy a estar subiendo un diario de los temas tecnologicos, tales como mis estudios (Ingenieria Informatica), mi canal de Youtube (@Bloop434) y mods o proyectos que vaya haciendo, que por cierto, tambien podreis ver en el apartado de Proyectos que ahi ire subiendo todos los que voy haciendo y como evolucionan.\r\n\r\nEn este foro se baneara y bloqueara el acceso a los usuarios que falten el respeto a quien sea, y queremos que no haya ningun tipo de odio, posiciones politicas, ni nada de eso.\r\n\r\nY sin mucho mas que decir espero que os mole la idea, y que hagais muchos amigos, voy a seguir desarrollando cosas para la web.','','','2025-11-22 14:04:13',1,'[\"\\/uploads\\/diario\\/6922e12b12190_1763893547.jpg\"]');
INSERT INTO proyectos VALUES (1,'RetroSpace web','Este proyecto está hecho para hacer pruebas de funcionamiento de la web. También quiero que se use para yo subir los logs de actualizaciones que iré haciendo. Y en los comentarios quiero que me dejéis las funcionalidades que queréis que mejore/añada.','Programacion','','','','','2025-11-22 14:04:13',1,'[\"\\/uploads\\/proyectos\\/6922f45f6c28b_1763898463.png\"]','2025-11-23 12:48:08');
INSERT INTO seguidores VALUES (1,4,3,'2025-11-22 20:42:01'),
(2,1,3,'2025-11-23 11:22:40');
INSERT INTO usuarios VALUES (1,'admin','admin@retrospace.local','Administrador Supremo','$2y$10$hScxccQaMSUwlJ5Ax/0qvueUdk21.oH.YbeutECieXd.e7uASUVpC','admin','2025-11-22 14:04:13',0,'Soy el admin del sitio.','data:image/svg+xml;

-- Re-enable triggers
SET session_replication_role = 'origin';

-- Update sequences
SELECT setval('usuarios_id_seq', (SELECT MAX(id) FROM usuarios));
SELECT setval('posts_diario_id_seq', (SELECT MAX(id) FROM posts_diario));
SELECT setval('proyectos_id_seq', (SELECT MAX(id) FROM proyectos));
SELECT setval('foro_hilos_id_seq', (SELECT MAX(id) FROM foro_hilos));
SELECT setval('foro_comentarios_id_seq', (SELECT MAX(id) FROM foro_comentarios));
SELECT setval('seguidores_id_seq', (SELECT MAX(id) FROM seguidores));
SELECT setval('proyecto_actualizaciones_id_seq', (SELECT MAX(id) FROM proyecto_actualizaciones));
SELECT setval('proyecto_comentarios_id_seq', (SELECT MAX(id) FROM proyecto_comentarios));
SELECT setval('estadisticas_id_seq', (SELECT MAX(id) FROM estadisticas));
