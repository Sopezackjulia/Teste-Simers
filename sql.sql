CREATE TABLE users (id INT AUTO_INCREMENT PRIMARY KEY,
				   name VARCHAR(255) NOT NULL,
                   cpf CHAR(11) NOT NULL,
                   email VARCHAR(255) NOT NULL,
                   birth_date DATE NOT NULL,
                   phone CHAR(11) NOT NULL,
                   password VARCHAR(255) NOT NULL)