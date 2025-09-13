# Adhyatmik Web Development Assignment

This repository contains the complete implementation of the Adhyatmik Web Development Internship assignment, featuring both backend (PHP + MySQL) and frontend (Shopify Liquid) components.

## 📋 Assignment Overview

### Task 1: PHP + MySQL Mini Task (Backend)
- ✅ Simple product catalog page using PHP + MySQL
- ✅ Database table: products (id, name, price, category)
- ✅ Product listing with at least 5 dummy entries
- ✅ Form to insert new products with validation
- ✅ Prepared statements for database queries
- ✅ Bonus: Dropdown filter by category

### Task 2: Shopify / Liquid Simulation (Frontend)
- ✅ Custom Shopify Liquid section
- ✅ Featured product display with image, name, price, and 'Buy Now' button
- ✅ Responsive design (works on desktop & mobile)
- ✅ Bonus: Hover effects on product image
- ✅ HTML mockup version for non-Shopify users

## 🚀 Quick Start

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx) or PHP built-in server

### Installation

1. **Clone or download the project files**

2. **Set up the database:**
   ```bash
   # Import the database schema
   mysql -u root -p < database.sql
   ```

3. **Configure database connection:**
   - Edit `config/database.php` to match your MySQL credentials:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'adhyatmik_products');
   define('DB_USER', 'your_username');
   define('DB_PASS', 'your_password');
   ```

4. **Start the application:**
   ```bash
   # Using PHP built-in server
   php -S localhost:8000
   
   # Or place files in your web server directory
   ```

5. **Access the application:**
   - Open `http://localhost:8000` in your browser

## 📁 Project Structure

```
Assisment/
├── README.md                          # This file
├── database.sql                       # Database schema and sample data
├── index.php                          # Main product catalog page
├── config/
│   └── database.php                   # Database configuration
└── shopify/
    ├── featured-product.liquid        # Shopify Liquid section
    └── featured-product-mockup.html   # HTML mockup version
```

## 🎯 Features Implemented

### Task 1: PHP + MySQL Backend

#### Database Schema
- **Table**: `products`
  - `id` (INT, AUTO_INCREMENT, PRIMARY KEY)
  - `name` (VARCHAR(255), NOT NULL)
  - `price` (DECIMAL(10,2), NOT NULL)
  - `category` (VARCHAR(100), NOT NULL)
  - `created_at` (TIMESTAMP)
  - `updated_at` (TIMESTAMP)

#### Features
- **Product Listing**: Displays all products in a responsive grid layout
- **Add Product Form**: Form with validation for adding new products
- **Category Filter**: Dropdown to filter products by category
- **Statistics Dashboard**: Shows total products, categories, and total value
- **Responsive Design**: Works on desktop, tablet, and mobile
- **Security**: Uses prepared statements to prevent SQL injection
- **Error Handling**: Comprehensive error handling and user feedback

#### Sample Data
The database includes 10 sample products across various categories:
- Electronics (Headphones, LED Lamp, Bluetooth Speaker)
- Clothing (T-Shirt, Jeans)
- Accessories (Water Bottle, Wallet, Phone Case)
- Footwear (Running Shoes)
- Home & Kitchen (Coffee Mug Set)

### Task 2: Shopify Liquid Frontend

#### Shopify Liquid Section (`featured-product.liquid`)
- **Custom Section**: Fully customizable Shopify section
- **Product Display**: Shows featured product with image, name, price
- **Buy Now Button**: Integrated with Shopify cart system
- **Responsive Design**: Mobile-first responsive layout
- **Hover Effects**: Smooth image scaling and card animations
- **Loading States**: Button loading animation during cart operations
- **Sale Badge**: Displays sale badge for discounted products
- **Theme Customizer**: Configurable settings for product selection and display options

#### HTML Mockup (`featured-product-mockup.html`)
- **Standalone Version**: Works without Shopify store
- **Interactive Demo**: Simulates add-to-cart functionality
- **Feature Showcase**: Demonstrates all section capabilities
- **Responsive Design**: Identical responsive behavior to Liquid version

## 🎨 Design Features

### Visual Design
- **Modern UI**: Clean, professional design with gradient backgrounds
- **Card-based Layout**: Product cards with hover effects and shadows
- **Color Scheme**: Professional blue-purple gradient theme
- **Typography**: Clean, readable fonts with proper hierarchy

### Responsive Design
- **Mobile-first**: Optimized for mobile devices
- **Breakpoints**: 
  - Desktop: 1200px+
  - Tablet: 768px - 1199px
  - Mobile: < 768px
- **Flexible Grid**: CSS Grid and Flexbox for responsive layouts

### Interactive Elements
- **Hover Effects**: Image scaling, card lifting, button animations
- **Loading States**: Spinner animations for form submissions
- **Smooth Transitions**: CSS transitions for all interactive elements
- **Form Validation**: Real-time validation with user feedback

## 🔧 Technical Implementation

### Backend (PHP + MySQL)
- **PDO**: Database abstraction layer with prepared statements
- **Error Handling**: Try-catch blocks with user-friendly error messages
- **Input Validation**: Server-side validation for all form inputs
- **Security**: SQL injection prevention, XSS protection
- **Performance**: Indexed database queries, optimized SQL

### Frontend (Shopify Liquid)
- **Liquid Templating**: Dynamic content rendering
- **Schema Settings**: Theme customizer integration
- **JavaScript**: Interactive functionality and form handling
- **CSS**: Modern styling with CSS Grid, Flexbox, and animations
- **Accessibility**: Semantic HTML and ARIA attributes

## 📱 Browser Support

- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+
- Mobile browsers (iOS Safari, Chrome Mobile)

## 🚀 Deployment

### Local Development
1. Use PHP built-in server: `php -S localhost:8000`
2. Or configure Apache/Nginx virtual host

### Production Deployment
1. Upload files to web server
2. Configure database connection
3. Import database schema
4. Set proper file permissions
5. Configure SSL certificate (recommended)

### Shopify Deployment
1. Upload `featured-product.liquid` to your theme's sections folder
2. Add the section to your theme through the theme customizer
3. Configure the section settings

## 🧪 Testing

### Manual Testing Checklist
- [ ] Database connection works
- [ ] Product listing displays correctly
- [ ] Add product form validation works
- [ ] Category filter functions properly
- [ ] Responsive design works on all devices
- [ ] Hover effects function correctly
- [ ] Form submissions handle errors gracefully

### Browser Testing
- [ ] Chrome (Desktop & Mobile)
- [ ] Firefox (Desktop & Mobile)
- [ ] Safari (Desktop & Mobile)
- [ ] Edge (Desktop)

## 📝 Notes

### Security Considerations
- All database queries use prepared statements
- Input validation and sanitization implemented
- XSS protection through proper output escaping
- CSRF protection recommended for production

### Performance Optimizations
- Database indexes on frequently queried columns
- Optimized CSS with minimal redundancy
- Lazy loading for images
- Minified JavaScript (recommended for production)

### Future Enhancements
- User authentication system
- Product image uploads
- Advanced search functionality
- Product reviews and ratings
- Shopping cart functionality
- Admin dashboard

## 📞 Support

For questions or issues with this assignment implementation, please refer to the code comments or create an issue in the repository.

## 📄 License

This project is created for the Adhyatmik Web Development Internship assignment.

---

**Assignment completed by**: [Your Name]  
**Date**: [Current Date]  
**Total Development Time**: ~4-6 hours
