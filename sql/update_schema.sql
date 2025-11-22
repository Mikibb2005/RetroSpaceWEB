ALTER TABLE usuarios ADD COLUMN biografia TEXT AFTER rol;

ALTER TABLE usuarios ADD COLUMN avatar VARCHAR(255) AFTER biografia;

CREATE TABLE seguidores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    seguidor_id INT NOT NULL,
    seguido_id INT NOT NULL,
    fecha_seguimiento TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (seguidor_id) REFERENCES usuarios (id),
    FOREIGN KEY (seguido_id) REFERENCES usuarios (id),
    UNIQUE KEY unique_subscription (seguidor_id, seguido_id)
);