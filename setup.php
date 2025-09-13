<?php
/**
 * Setup Script for Adhyatmik Web Development Assignment
 * This script helps set up the database and verify the installation
 */

echo "<h1>Adhyatmik Assignment Setup</h1>";
echo "<h2>Database Setup and Verification</h2>";

// Database configuration
$host = 'localhost';
$dbname = 'adhyatmik_products';
$username = 'root';
$password = '';

echo "<h3>Step 1: Testing Database Connection</h3>";

try {
    // Test connection without database first
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Database server connection successful<br>";
    
    // Check if database exists
    $stmt = $pdo->query("SHOW DATABASES LIKE '$dbname'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Database '$dbname' already exists<br>";
    } else {
        echo "⚠️ Database '$dbname' does not exist. Please run: <code>mysql -u root -p < database.sql</code><br>";
    }
    
    // Test connection to the specific database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Database '$dbname' connection successful<br>";
    
} catch (PDOException $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "<br>";
    echo "<p><strong>Please check your database configuration in config/database.php</strong></p>";
    exit;
}

echo "<h3>Step 2: Verifying Database Structure</h3>";

try {
    // Check if products table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'products'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Products table exists<br>";
        
        // Check table structure
        $stmt = $pdo->query("DESCRIBE products");
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $requiredColumns = ['id', 'name', 'price', 'category'];
        
        foreach ($requiredColumns as $column) {
            if (in_array($column, $columns)) {
                echo "✅ Column '$column' exists<br>";
            } else {
                echo "❌ Column '$column' missing<br>";
            }
        }
        
        // Check sample data
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM products");
        $count = $stmt->fetch()['count'];
        echo "✅ Products table contains $count records<br>";
        
        if ($count < 5) {
            echo "⚠️ Warning: Less than 5 products found. Please check sample data.<br>";
        }
        
    } else {
        echo "❌ Products table does not exist. Please run: <code>mysql -u root -p < database.sql</code><br>";
    }
    
} catch (PDOException $e) {
    echo "❌ Error checking database structure: " . $e->getMessage() . "<br>";
}

echo "<h3>Step 3: Testing Application Files</h3>";

$requiredFiles = [
    'index.php' => 'Main application file',
    'config/database.php' => 'Database configuration',
    'database.sql' => 'Database schema file',
    'shopify/featured-product.liquid' => 'Shopify Liquid section',
    'shopify/featured-product-mockup.html' => 'HTML mockup',
    'README.md' => 'Documentation'
];

foreach ($requiredFiles as $file => $description) {
    if (file_exists($file)) {
        echo "✅ $file - $description<br>";
    } else {
        echo "❌ $file - $description (Missing)<br>";
    }
}

echo "<h3>Step 4: PHP Configuration Check</h3>";

$phpVersion = phpversion();
echo "PHP Version: $phpVersion<br>";

if (version_compare($phpVersion, '7.4.0', '>=')) {
    echo "✅ PHP version is compatible<br>";
} else {
    echo "⚠️ PHP version should be 7.4 or higher<br>";
}

$extensions = ['pdo', 'pdo_mysql'];
foreach ($extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "✅ $ext extension loaded<br>";
    } else {
        echo "❌ $ext extension not loaded<br>";
    }
}

echo "<h3>Step 5: Quick Test</h3>";

try {
    // Test a simple query
    $stmt = $pdo->query("SELECT * FROM products LIMIT 3");
    $products = $stmt->fetchAll();
    
    if (count($products) > 0) {
        echo "✅ Database query test successful<br>";
        echo "<h4>Sample Products:</h4>";
        echo "<ul>";
        foreach ($products as $product) {
            echo "<li>{$product['name']} - \${$product['price']} ({$product['category']})</li>";
        }
        echo "</ul>";
    } else {
        echo "⚠️ No products found in database<br>";
    }
    
} catch (PDOException $e) {
    echo "❌ Database query test failed: " . $e->getMessage() . "<br>";
}

echo "<h3>Setup Complete!</h3>";
echo "<p><strong>Next Steps:</strong></p>";
echo "<ol>";
echo "<li>If all checks passed, visit <a href='index.php'>index.php</a> to see the product catalog</li>";
echo "<li>If there were issues, please fix them and run this setup script again</li>";
echo "<li>For Shopify section, use the files in the <code>shopify/</code> directory</li>";
echo "</ol>";

echo "<hr>";
echo "<p><em>Setup script completed at " . date('Y-m-d H:i:s') . "</em></p>";
?>
