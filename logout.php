
<?php
// Ξεκινάμε τη σύνοδο
session_start();

// Εκκαθαρίζουμε τη σύνοδο
$_SESSION = array();

// Εάν έχετε ρυθμίσει τη σύνοδο να αποθηκεύεται σε cookies, τότε πρέπει να τα διαγράψετε
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Καταστρέφουμε τη σύνοδο
session_destroy();

// Ανακατευθύνουμε τον χρήστη στη σελίδα εισόδου
header("Location: index.php");
exit;
?>