-- Database creation and setup for Adhyatmik Web Development Assignment
-- Task 1: PHP + MySQL Product Catalog

-- Create database
CREATE DATABASE IF NOT EXISTS adhyatmik_products;
USE adhyatmik_products;


-- Create products tabless
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    category VARCHAR(100) NOT NULL,
    description TEXT,
    image_url VARCHAR(500),
    stock_quantity INT DEFAULT 100o,
    is_featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert sample products (at least 5 dummy entries11)
INSERT INTO products (name, price, category, description, image_url, stock_quantity, is_featured) VALUES
('Wireless Bluetooth Headphones', 89.99, 'Electronics', 'Premium wireless headphones with noise cancellation and 30-hour battery life.', 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&h=400&fit=crop', 50, TRUE),
('Organic Cotton T-Shirt', 24.99, 'Clothing', 'Comfortable organic cotton t-shirt made from sustainable materials.', 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=400&h=400&fit=crop', 100, FALSE),
('Stainless Steel Water Bottle', 19.99, 'Accessories', 'Insulated stainless steel water bottle that keeps drinks cold for 24 hours.', 'https://images.unsplash.com/photo-1602143407151-7111542de6e8?w=400&h=400&fit=crop', 75, FALSE),
('LED Desk Lamp', 45.50, 'Electronics', 'Adjustable LED desk lamp with multiple brightness levels and USB charging port.', 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&h=400&fit=crop', 30, TRUE),
('Leather Wallet', 35.00, 'Accessories', 'Genuine leather wallet with RFID blocking technology and multiple card slots.', 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=400&h=400&fit=crop', 60, FALSE),
('Running Shoes', 120.00, 'Footwear', 'Lightweight running shoes with advanced cushioning and breathable mesh upper.', 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400&h=400&fit=crop', 40, TRUE),
('Smartphone Case', 15.99, 'Accessories', 'Protective smartphone case with shock absorption and wireless charging compatibility.', 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=400&h=400&fit=crop', 120, FALSE),
('Coffee Mug Set', 28.75, 'Home & Kitchen', 'Set of 4 ceramic coffee mugs with ergonomic design and dishwasher safe.', 'https://images.unsplash.com/photo-1514228742587-6b1558fcf93a?w=400&h=400&fit=crop', 80, FALSE),
('Bluetooth Speaker', 65.00, 'Electronics', 'Portable Bluetooth speaker with 360-degree sound and waterproof design.', 'https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?w=400&h=400&fit=crop', 25, TRUE),
('Denim Jeans', 79.99, 'Clothing', 'Classic denim jeans made from premium cotton with a comfortable fit.', 'https://images.unsplash.com/photo-1542272604-787c3835535d?w=400&h=400&fit=crop', 90, FALSE);

-- Create index on category for better performance
CREATE INDEX idx_category ON products(category);
