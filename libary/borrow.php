<?php
require 'config.php'; 

if (!is_logged_in()) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['book_id'])) {
    $book_id = intval($_GET['book_id']);
    $user_id = $_SESSION['user_id']; 

    // Check if user already borrowed this book and it's not returned
    $check_borrow = $conn->prepare("SELECT id FROM borrows WHERE user_id=? AND book_id=? AND status='borrowed'");
    $check_borrow->bind_param("ii", $user_id, $book_id);
    $check_borrow->execute();
    $check_result = $check_borrow->get_result();

    if ($check_result->num_rows > 0) {
        // Already borrowed
        header("Location: index.php?borrow_error=1");
        exit;
    }

    // Check book quantity
    $check = $conn->prepare("SELECT quantity FROM books WHERE id = ?");
    $check->bind_param("i", $book_id);
    $check->execute();
    $book = $check->get_result()->fetch_assoc();

    if ($book && $book['quantity'] > 0) {
        $conn->begin_transaction();
        try {
            // Insert borrow
            $stmt = $conn->prepare("
                INSERT INTO borrows (user_id, book_id, borrow_date, due_date, status) 
                VALUES (?, ?, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 7 DAY), 'borrowed')
            ");
            $stmt->bind_param("ii", $user_id, $book_id);
            $stmt->execute();

            // Update quantity
            $update = $conn->prepare("UPDATE books SET quantity = quantity - 1 WHERE id = ?");
            $update->bind_param("i", $book_id);
            $update->execute();

            $conn->commit();
            header("Location: index.php?borrow_success=1");
            exit;
        } catch (Exception $e) {
            $conn->rollback();
            echo "❌ Error: " . $e->getMessage();
        }
    } else {
        header("Location: index.php?borrow_error=2"); // Out of stock
        exit;
    }
} else {
    header("Location: index.php?borrow_error=3"); // Invalid request
    exit;
}
?>
