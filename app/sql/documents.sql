CREATE TABLE IF NOT EXISTS documents (
    `id` int NOT NULL AUTO_INCREMENT ,
    `doc_id` varchar(255),
    `title` varchar(255),
    `author` varchar(255),
    `links` varchar(255),
    PRIMARY KEY (`id`)
)