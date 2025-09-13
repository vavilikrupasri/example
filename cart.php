<?php
/**
 * Shopping Cart Management - Adhyatmik Dynamic E-commerce
 * Handles cart operations: add, remove, update quantities
 */

require_once 'config/database.php';

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    try {
        $pdo = getDBConnection();
        
        switch ($_POST['action']) {
            case 'add_to_cart':
                $product_id = (int)$_POST['product_id'];
                $quantity = (int)($_POST['quantity'] ?? 1);
                
                // Get product details
                $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
                $stmt->execute([$product_id]);
                $product = $stmt->fetch();
                
                if (!$product) {
                    throw new Exception('Product not found');
                }
                
                // Check stock
                if ($product['stock_quantity'] < $quantity) {
                    throw new Exception('Insufficient stock');
                }
                
                // Add to cart
                if (isset($_SESSION['cart'][$product_id])) {
                    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
                } else {
                    $_SESSION['cart'][$product_id] = [
                        'id' => $product['id'],
                        'name' => $product['name'],
                        'price' => $product['price'],
                        'image_url' => $product['image_url'],
                        'quantity' => $quantity
                    ];
                }
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Product added to cart',
                    'cart_count' => array_sum(array_column($_SESSION['cart'], 'quantity'))
                ]);
                break;
                
            case 'remove_from_cart':
                $product_id = (int)$_POST['product_id'];
                if (isset($_SESSION['cart'][$product_id])) {
                    unset($_SESSION['cart'][$product_id]);
                }
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Product removed from cart',
                    'cart_count' => array_sum(array_column($_SESSION['cart'], 'quantity'))
                ]);
                break;
                
            case 'update_quantity':
                $product_id = (int)$_POST['product_id'];
                $quantity = (int)$_POST['quantity'];
                
                if ($quantity <= 0) {
                    unset($_SESSION['cart'][$product_id]);
                } else {
                    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
                }
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Cart updated',
                    'cart_count' => array_sum(array_column($_SESSION['cart'], 'quantity'))
                ]);
                break;
                
            case 'get_cart_count':
                echo json_encode([
                    'success' => true,
                    'cart_count' => array_sum(array_column($_SESSION['cart'], 'quantity'))
                ]);
                break;
                
            default:
                throw new Exception('Invalid action');
        }
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
    exit;
}

// Get cart data for display
function getCartData() {
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        return [
            'items' => [],
            'total_items' => 0,
            'total_price' => 0
        ];
    }
    
    $total_items = 0;
    $total_price = 0;
    
    foreach ($_SESSION['cart'] as $item) {
        $total_items += $item['quantity'];
        $total_price += $item['price'] * $item['quantity'];
    }
    
    return [
        'items' => $_SESSION['cart'],
        'total_items' => $total_items,
        'total_price' => $total_price
    ];
}

// Clear cart
if (isset($_GET['clear_cart'])) {
    $_SESSION['cart'] = [];
    header('Location: cart.php');
    exit;
}
?>
