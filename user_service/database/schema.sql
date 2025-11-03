CREATE DATABASE photo_storage;

USE photo_storage;

CREATE TABLE photos (
    id int NOT NULL AUTO_INCREMENT,
    file_name VARCHAR(255),
    metadata JSON,
    uploaded_at TIMESTAMP,
    PRIMARY KEY(id)
);
