-- init.sql: create DB and tables (simplified)
CREATE DATABASE IF NOT EXISTS resep_nusantara CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE resep_nusantara;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  display_name VARCHAR(150) DEFAULT NULL,
  email VARCHAR(255) DEFAULT NULL,
  role ENUM('admin','editor') NOT NULL DEFAULT 'editor',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS recipes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  region VARCHAR(100) NOT NULL,
  image_url VARCHAR(1000) DEFAULT NULL,
  image_local VARCHAR(255) DEFAULT NULL,
  ingredients TEXT NOT NULL,
  steps TEXT NOT NULL,
  excerpt VARCHAR(512),
  slug VARCHAR(255),
  featured TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- add fulltext index if supported
ALTER TABLE recipes ADD FULLTEXT idx_fulltext_title_ingredients_steps (title, ingredients, steps);
