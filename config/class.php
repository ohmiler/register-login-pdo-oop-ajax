<?php

include_once 'connection.php';

class Websystem {

    private $db;

    public function __construct() {
        $this->db = connectDB();
    }

    public function registerUser($name, $email, $password) {
        try {

            // Check if email or username already exists
            $stmt = $this->db->prepare("SELECT id FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                echo json_encode([
                    'message' => 'Email already exists.',
                    'status' => 400,
                    'success' => false
                ]);
                return;
            }

            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Prepare SQL statement to prevent SQL injection
            $stmt = $this->db->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");

            // Bind parameters and execute the statement
            $stmt->execute(['name' => $name, 'email' => $email, 'password' => $hashed_password]);

            echo json_encode([
                'message' => 'Registration successful!',
                'status' => 200,
                'success' => true,
            ]);

        } catch(PDOException $e) {
            echo json_encode([
                'success' => false,
                'status' => 500,
                'message' => 'A server error occurred.'
            ]);
        }
    }

    public function loginUser($email, $password) {
        try {

            // Prepare SQL statement to prevent SQL injection
            $stmt = $this->db->prepare("SELECT id, email, password FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                if (password_verify($password, $user['password'])) {
                    // Password is correct, start a new session and store user information
                    $_SESSION['userId'] = $user['id'];
                    echo json_encode([
                        'status' => 200,
                        'success' => true,
                        'message' => 'User login successfully'
                    ]);
                } else {
                    echo json_encode([
                        'status' => 400,
                        'success' => false,
                        'message' => "Invalid username or password.",
                    ]);
                }
            } else {
                // Email does not exist
                echo json_encode([
                    'status' => 400,
                    'success' => false,
                    'message' => "Email does not exist."
                ]);
            }

        } catch(PDOException $e) {
            echo json_encode([
                'success' => false,
                'status' => 500,
                'message' => 'A server error occurred.'
            ]);
        }
    }

    public function displayData() {
        try {
            $userData = $this->db->prepare("SELECT * FROM users WHERE id = :id");
            $userData->execute([
                ':id' => $_SESSION['userId']
            ]);
            return $userData->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo json_encode([
                'success' => false,
                'status' => 500,
                'message' => 'A server error occurred.'
            ]);
        }
    }
}
?>
