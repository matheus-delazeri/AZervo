CREATE TABLE IF NOT EXISTS documents (
    `id` int NOT NULL AUTO_INCREMENT ,
    `doi` varchar(255),
    `links` varchar(255),
    PRIMARY KEY (`id`)
)