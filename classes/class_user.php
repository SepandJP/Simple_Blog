<?php


class class_user
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    //To check a user is logged in
    public function is_logged_in()
    {
        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']==true)
        {
            return true;
        }
    }

    public function create_hash($value)
    {
        return $hash = md5($value);
    }

    //used to get the columns of user from the database and return them.
    private function get_user_hash($username)
    {
        try {
            $sql = 'SELECT * FROM blog_members WHERE username = :username';
            $result = $this->db->prepare($sql);
            $result->bindParam(':username', $username);
            $result->execute();

            $user_col = $result->fetch(PDO::FETCH_ASSOC);
            if (isset($user_col['password']))
            {
                return $user_col['password'];
            }
        }

        catch (PDOException $e)
        {
            $message = $e->getMessage();
            echo "<p>.$message.</p>";
        }
    }

    //fetch username and password then user
    public function login($username, $password)
    {
        $input_pass = $this->create_hash($password);
        $user_pass = $this->get_user_hash($username);

        if (isset($input_pass) && isset($user_pass) && hash_equals($input_pass, $user_pass)) {
            $_SESSION['loggedin'] = true;
            return true;
        }
    }

    public function logout()
    {
        session_destroy();
    }
}