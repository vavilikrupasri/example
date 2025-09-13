<?php
/**
 * Dynamic Product Catalog - Adhyatmik E-commerce
 * Enhanced with shopping cart functionality and modern UI
 */

require_once 'config/database.php';
require_once 'cart.php';

// Initialize variables
$products = [];
$categories = [];
$selectedCategory = '';
$message = '';
$error = '';
$cartData = getCartData();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add_product') {
        $name = trim($_POST['name'] ?? '');
        $price = trim($_POST['price'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $image_url = trim($_POST['image_url'] ?? '');
        $stock_quantity = (int)($_POST['stock_quantity'] ?? 100);
        $is_featured = isset($_POST['is_featured']) ? 1 : 0;
        
        // Validation
        $errors = [];
        if (empty($name)) $errors[] = 'Product name is required';
        if (empty($price) || !is_numeric($price) || $price <= 0) $errors[] = 'Valid price is required';
        if (empty($category)) $errors[] = 'Category is required';
        if (empty($description)) $errors[] = 'Description is required';
        if (empty($image_url)) $errors[] = 'Image URL is required';
        if ($stock_quantity < 0) $errors[] = 'Stock quantity must be non-negative';
        
        if (empty($errors)) {
            try {
                $pdo = getDBConnection();
                $stmt = $pdo->prepare("INSERT INTO products (name, price, category, description, image_url, stock_quantity, is_featured) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$name, $price, $category, $description, $image_url, $stock_quantity, $is_featured]);
                $message = "Product added successfully!";
            } catch (PDOException $e) {
                $error = "Error adding product: " . $e->getMessage();
            }
        } else {
            $error = implode(', ', $errors);
        }
    }
}

// Get selected category filter
$selectedCategory = $_GET['category'] ?? '';

try {
    $pdo = getDBConnection();
    
    // Get all categories for dropdown
    $stmt = $pdo->query("SELECT DISTINCT category FROM products ORDER BY category");
    $categories = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    // Get products with optional category filter
    if ($selectedCategory) {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE category = ? ORDER BY name");
        $stmt->execute([$selectedCategory]);
    } else {
        $stmt = $pdo->query("SELECT * FROM products ORDER BY name");
    }
    $products = $stmt->fetchAll();
    
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Catalog - Adhyatmik Assignment</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
            text-align: center;
            margin-bottom: 2rem;
            border-radius: 10px;
        }
        
        h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        
        .subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        .section {
            background: white;
            padding: 2rem;
            margin-bottom: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .section h2 {
            color: #667eea;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid #667eea;
            padding-bottom: 0.5rem;
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #555;
        }
        
        input, select {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        input:focus, select:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: transform 0.2s;
        }
        
        .btn:hover {
            transform: translateY(-2px);
        }
        
        .filter-section {
            display: flex;
            gap: 1rem;
            align-items: end;
            margin-bottom: 2rem;
        }
        
        .filter-group {
            flex: 1;
        }
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }
        
        .header-actions {
            margin-top: 1rem;
        }
        
        .cart-link {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 0.75rem 1.5rem;
            text-decoration: none;
            border-radius: 25px;
            transition: all 0.3s;
            display: inline-block;
        }
        
        .cart-link:hover {
            background: white;
            color: #667eea;
        }
        
        .product-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            position: relative;
        }
        
        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        
        .product-image-wrapper {
            position: relative;
            overflow: hidden;
            aspect-ratio: 1;
        }
        
        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .product-card:hover .product-image {
            transform: scale(1.1);
        }
        
        .featured-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: #ff6b6b;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .low-stock-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #ffa726;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .product-info {
            padding: 1.5rem;
        }
        
        .product-name {
            font-size: 1.2rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .product-description {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 1rem;
            line-height: 1.4;
        }
        
        .product-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 0.5rem;
        }
        
        .product-category {
            background: #f0f0f0;
            color: #666;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.9rem;
            display: inline-block;
            margin-bottom: 0.5rem;
        }
        
        .product-stock {
            font-size: 0.85rem;
            color: #888;
            margin-bottom: 1rem;
        }
        
        .product-actions {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }
        
        .quantity-selector {
            display: flex;
            align-items: center;
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
        }
        
        .qty-btn {
            width: 30px;
            height: 35px;
            border: none;
            background: #f8f9fa;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            transition: background 0.3s;
        }
        
        .qty-btn:hover {
            background: #e9ecef;
        }
        
        .qty-input {
            width: 50px;
            height: 35px;
            border: none;
            text-align: center;
            font-size: 0.9rem;
        }
        
        .btn-primary {
            flex: 1;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 0.75rem 1rem;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        
        textarea {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            font-family: inherit;
            resize: vertical;
        }
        
        textarea:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 10px;
            text-align: center;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }
            
            h1 {
                font-size: 2rem;
            }
            
            .filter-section {
                flex-direction: column;
            }
            
            .products-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Dynamic E-commerce Store</h1>
            <p class="subtitle">Adhyatmik Web Development Assignment - Enhanced</p>
            <div class="header-actions">
                <a href="cart-page.php" class="cart-link">
                    🛒 Cart (<span id="cart-count"><?php echo $cartData['total_items']; ?></span>)
                </a>
            </div>
        </header>

        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <!-- Statistics -->
        <div class="stats">
            <div class="stat-card">
                <div class="stat-number"><?php echo count($products); ?></div>
                <div class="stat-label">Total Products</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo count($categories); ?></div>
                <div class="stat-label">Categories</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">$<?php echo number_format(array_sum(array_column($products, 'price')), 2); ?></div>
                <div class="stat-label">Total Value</div>
            </div>
        </div>

        <!-- Add Product Form -->
        <div class="section">
            <h2>Add New Product</h2>
            <form method="POST" action="">
                <input type="hidden" name="action" value="add_product">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="name">Product Name:</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="price">Price ($):</label>
                        <input type="number" id="price" name="price" step="0.01" min="0" required>
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="category">Category:</label>
                        <input type="text" id="category" name="category" required placeholder="e.g., Electronics, Clothing">
                    </div>
                    <div class="form-group">
                        <label for="stock_quantity">Stock Quantity:</label>
                        <input type="number" id="stock_quantity" name="stock_quantity" min="0" value="100">
                    </div>
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" rows="3" required placeholder="Product description..."></textarea>
                </div>
                <div class="form-group">
                    <label for="image_url">Image URL:</label>
                    <input type="url" id="image_url" name="image_url" required placeholder="https://example.com/image.jpg">
                </div>
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="is_featured" value="1"> Featured Product
                    </label>
                </div>
                <button type="submit" class="btn">Add Product</button>
            </form>
        </div>

        <!-- Category Filter -->
        <div class="section">
            <h2>Filter Products</h2>
            <div class="filter-section">
                <div class="filter-group">
                    <label for="category_filter">Filter by Category:</label>
                    <select id="category_filter" name="category" onchange="filterProducts()">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo htmlspecialchars($category); ?>" 
                                    <?php echo $selectedCategory === $category ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <button type="button" class="btn" onclick="clearFilter()">Clear Filter</button>
                </div>
            </div>
        </div>

        <!-- Products List -->
        <div class="section">
            <h2>Products 
                <?php if ($selectedCategory): ?>
                    - <?php echo htmlspecialchars($selectedCategory); ?>
                <?php endif; ?>
            </h2>
            
            <?php if (empty($products)): ?>
                <p>No products found.</p>
            <?php else: ?>
                <div class="products-grid">
                    <?php foreach ($products as $product): ?>
                        <div class="product-card" data-product-id="<?php echo $product['id']; ?>">
                            <div class="product-image-wrapper">
                                <img src="<?php echo htmlspecialchars($product['image_url'] ?? 'https://via.placeholder.com/300x300?text=No+Image'); ?>" 
                                     alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                     class="product-image"
                                     loading="lazy">
                                <?php if ($product['is_featured']): ?>
                                    <div class="featured-badge">Featured</div>
                                <?php endif; ?>
                                <?php if ($product['stock_quantity'] <= 10): ?>
                                    <div class="low-stock-badge">Low Stock</div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="product-info">
                                <div class="product-name"><?php echo htmlspecialchars($product['name']); ?></div>
                                <div class="product-description"><?php echo htmlspecialchars(substr($product['description'] ?? '', 0, 100)) . (strlen($product['description'] ?? '') > 100 ? '...' : ''); ?></div>
                                <div class="product-price">$<?php echo number_format($product['price'], 2); ?></div>
                                <div class="product-category"><?php echo htmlspecialchars($product['category']); ?></div>
                                <div class="product-stock">Stock: <?php echo $product['stock_quantity']; ?></div>
                                
                                <div class="product-actions">
                                    <div class="quantity-selector">
                                        <button class="qty-btn" onclick="changeQuantity(<?php echo $product['id']; ?>, -1)">-</button>
                                        <input type="number" class="qty-input" id="qty-<?php echo $product['id']; ?>" value="1" min="1" max="<?php echo $product['stock_quantity']; ?>">
                                        <button class="qty-btn" onclick="changeQuantity(<?php echo $product['id']; ?>, 1)">+</button>
                                    </div>
                                    <button class="btn btn-primary buy-now-btn" onclick="addToCart(<?php echo $product['id']; ?>)">
                                        <span class="btn-text">Add to Cart</span>
                                        <span class="btn-loading" style="display: none;">Adding...</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function filterProducts() {
            const category = document.getElementById('category_filter').value;
            const url = new URL(window.location);
            
            if (category) {
                url.searchParams.set('category', category);
            } else {
                url.searchParams.delete('category');
            }
            
            window.location.href = url.toString();
        }
        
        function clearFilter() {
            const url = new URL(window.location);
            url.searchParams.delete('category');
            window.location.href = url.toString();
        }
        
        function changeQuantity(productId, change) {
            const input = document.getElementById('qty-' + productId);
            const currentValue = parseInt(input.value);
            const newValue = Math.max(1, currentValue + change);
            const maxValue = parseInt(input.max);
            
            if (newValue <= maxValue) {
                input.value = newValue;
            }
        }
        
        function addToCart(productId) {
            const quantity = document.getElementById('qty-' + productId).value;
            const button = document.querySelector(`[data-product-id="${productId}"] .buy-now-btn`);
            const btnText = button.querySelector('.btn-text');
            const btnLoading = button.querySelector('.btn-loading');
            
            // Show loading state
            btnText.style.display = 'none';
            btnLoading.style.display = 'inline';
            button.disabled = true;
            
            fetch('cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=add_to_cart&product_id=${productId}&quantity=${quantity}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update cart count
                    document.getElementById('cart-count').textContent = data.cart_count;
                    
                    // Show success message
                    showNotification('Product added to cart!', 'success');
                    
                    // Reset button state
                    btnText.style.display = 'inline';
                    btnLoading.style.display = 'none';
                    button.disabled = false;
                } else {
                    showNotification(data.message, 'error');
                    btnText.style.display = 'inline';
                    btnLoading.style.display = 'none';
                    button.disabled = false;
                }
            })
            .catch(error => {
                showNotification('An error occurred. Please try again.', 'error');
                btnText.style.display = 'inline';
                btnLoading.style.display = 'none';
                button.disabled = false;
            });
        }
        
        function showNotification(message, type) {
            // Remove existing notifications
            const existing = document.querySelector('.notification');
            if (existing) {
                existing.remove();
            }
            
            // Create notification
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.textContent = message;
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 1rem 2rem;
                border-radius: 5px;
                color: white;
                font-weight: 600;
                z-index: 1000;
                animation: slideIn 0.3s ease;
                ${type === 'success' ? 'background: #28a745;' : 'background: #dc3545;'}
            `;
            
            document.body.appendChild(notification);
            
            // Remove after 3 seconds
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }
        
        // Add CSS animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOut {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>
