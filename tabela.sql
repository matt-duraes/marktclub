CREATE DATABASE marktclub3;

CREATE TABLE users (
  id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(200) NULL,
  email VARCHAR(200) NULL,
  cpf VARCHAR(15) NULL,
  password VARCHAR(200) NULL,
  token VARCHAR(200) NULL,
  permissao VARCHAR(300) NULL,
  status INTEGER UNSIGNED NULL,
  PRIMARY KEY(id)
);
INSERT INTO `users` (`id`, `name`, `email`, `password`, `cpf`, `token`, `permissao`, `status`) VALUES (NULL, 'admin', 'marktclub@gmail.com', '$2y$10$ZdabkJ2nmCLChbs4SbNlkOoJlAoUa0MynaGL', '07385656165', '', 'login,usuario_add,usuario_editar,usuario_deletar', '1');

INSERT INTO `users` (`id`, `name`, `email`, `password`, `cpf`, `token`, `permissao`, `status`) VALUES (NULL, 'user', 'user@gmail.com', '$2y$10$nTqCaYNbdfMyd/DSCoZALeL6aqQGC6LkiJ20fAkr.WBVXY0ISkpZu', '08524968425', '', 'login,usuario_add,usuario_editar', '1');





