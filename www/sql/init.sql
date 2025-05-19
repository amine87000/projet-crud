CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) DEFAULT 'user'
);

INSERT INTO users (username, password, role) 
VALUES ('admin', '$2y$10$C3g.kZoKv0Rfh6/SiOBjXuFIU5KD68xvyEJpzZfrfh3rsFgqrBSla', 'admin');
