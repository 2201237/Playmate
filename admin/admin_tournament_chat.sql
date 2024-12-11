CREATE TABLE admin_tournament_chat (
admin_tournament_chat_id int NOT NULL AUTO_INCREMENT,
chat VARCHAR(300) DEFAULT NULL,
admin_id int NOT NULL,
created_at timestamp NOT NULL,
tournament_id int NOT NULL,
round int NOT NULL,
image_path VARCHAR(255) DEFAULT NULL,
PRIMARY KEY(admin_tournament_chat_id),
FOREIGN KEY(admin_id) REFERENCES admins(admin_id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;