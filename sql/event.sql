CREATE TABLE events (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        name VARCHAR(255) NOT NULL,
                        bio TEXT,
                        images JSON,
                        guide_review TEXT,
                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
