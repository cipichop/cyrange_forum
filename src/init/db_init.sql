CREATE DATABASE IF NOT EXISTS forum_db;

USE forum_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
);

INSERT INTO users (username, password) VALUES
    ('all_might', '404df3545bdb5a19f3afa6f533fa6efc'),
    ('hengker', '9035906fc4134a19e83136bbb98733d8'),
    ('john_doe', '88773a5342684a9223538352aac9add9'),
    ('jane_smith', 'f16028a4a6942edd36a10924effff5bd'),
    ('mizzuk', 'dd945044674d49867a57b8a85ec2ae2d');

CREATE TABLE IF NOT EXISTS discussions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    file_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    topic VARCHAR(255),
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

INSERT INTO discussions (title, content, topic, user_id) VALUES
    ('Healthcare Costs', 'The rising cost of healthcare is becoming a major concern for many families. What are some ways to manage these expenses?', 'Cost', 1),
    ('Cheapest Food Nearby', 'I found a great place that sells delicious sandwiches for just $5!', 'Food', 2),
    ('New Job Opportunities', 'I recently heard about a new job opening in the tech industry. Does anyone have more information about this?', 'Jobs', 3),
    ('Am I Hacked??', 'I recently noticed some unusual activity on my account. Has anyone else experienced this?', 'Security', 4),
    ('Mental Health Awareness', 'Mental health is just as important as physical health. What are some resources or practices that have helped you maintain your mental well-being?', 'Health', 4),
    ('Need Money', 'I have been freelancing for a while now and it has been a great way to earn extra income. Does anyone have tips on finding more freelance gigs?', 'Jobs', 3);
INSERT INTO discussions (title, content, topic, user_id, file_path) VALUES
    ('WHO IS SUCIPTO?', 'Have you ever seen that ugly shark called sucipto? And his friends like reza (shark), daniel (surfing cow), and many more..?', 'Random', 5, 'uploads/e9674e417a6ad8650fcd66b6b57d1776.jpg');