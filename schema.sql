-- DENZIE STUDIO Database Schema
-- Version 1.0

-- Create database
CREATE DATABASE IF NOT EXISTS denzie_studio;

-- Use database
USE denzie_studio;

-- Create tools table
CREATE TABLE tools (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    icon VARCHAR(255),
    position INT DEFAULT 0
);

-- Create services table
CREATE TABLE services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    icon VARCHAR(255),
    position INT DEFAULT 0
);

-- Create projects table
CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    icon VARCHAR(255),
    position INT DEFAULT 0
);

-- Create social_links table
CREATE TABLE social_links (
    id INT AUTO_INCREMENT PRIMARY KEY,
    platform VARCHAR(100) NOT NULL,
    url VARCHAR(500) NOT NULL
);

-- Insert default tools
INSERT INTO tools (name, icon, position) VALUES
('Python', 'fab fa-python', 1),
('JavaScript', 'fab fa-js', 2),
('React', 'fab fa-react', 3),
('Docker', 'fab fa-docker', 4),
('Kubernetes', 'fas fa-server', 5),
('AWS/Azure', 'fas fa-cloud', 6),
('TensorFlow', 'fas fa-bolt', 7),
('OpenAI', 'fas fa-robot', 8);

-- Insert default services
INSERT INTO services (title, description, icon, position) VALUES
('AI-Powered Solutions', 'Leveraging artificial intelligence and machine learning to create intelligent software that adapts and learns from data.', 'fas fa-brain', 0),
('Process Automation', 'Streamlining workflows and automating repetitive tasks to increase efficiency and reduce operational overhead.', 'fas fa-cogs', 1),
('Custom Software', 'Tailor-made applications built specifically for your business needs, designed for scalability and performance.', 'fas fa-code', 2);

-- Insert default projects
INSERT INTO projects (title, description, icon, position) VALUES
('IntelliBot Platform', 'AI-powered customer service automation system with natural language processing capabilities.', 'fas fa-robot', 0),
('DataFlow Analytics', 'Real-time data processing pipeline for large-scale analytics and visualization.', 'fas fa-chart-line', 1),
('SecureVault', 'Enterprise-grade security solution for data encryption and access management.', 'fas fa-shield-alt', 2);

-- Insert default social links
INSERT INTO social_links (platform, url) VALUES
('GitLab', 'https://gitlab.com/denzie-studio'),
('GitHub', 'https://github.com/denzie-studio'),
('LinkedIn', 'https://linkedin.com/company/denzie-studio');

-- Create database user
CREATE USER IF NOT EXISTS 'denzie_user'@'localhost' IDENTIFIED BY 'StrongPassword123';

-- Grant privileges
GRANT ALL PRIVILEGES ON denzie_studio.* TO 'denzie_user'@'localhost';

-- Apply privileges
FLUSH PRIVILEGES;
