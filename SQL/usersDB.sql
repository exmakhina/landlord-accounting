USE landlor_usersDB;

CREATE TABLE IF NOT EXISTS users (
  id 			int(11) 	NOT NULL AUTO_INCREMENT,
  username 		varchar(50) NOT NULL,
  password 		varchar(50) NOT NULL,
  salt 			varchar(50) NOT NULL,
  role 			varchar(50) NOT NULL,
  date_created 	datetime NOT NULL,
  
  PRIMARY KEY (id),
  UNIQUE KEY username(username)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO users (username, password, salt, role, date_created) VALUES ('devDB', SHA1('adminadmince8d96d579d389e783f95b3772785783ea1a9854'), 'ce8d96d579d389e783f95b3772785783ea1a9854', 'administrateur', NOW());

INSERT INTO users (username, password, salt, role, date_created) VALUES ('CasaPorto', SHA1('CasaPorto2011ce8d96d579d389e783f95b3772785783ea1a9854'), 'ce8d96d579d389e783f95b3772785783ea1a9854', 'administrateur', NOW());
