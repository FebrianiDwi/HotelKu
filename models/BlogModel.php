<?php

class BlogModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getPublishedPosts($limit = 10)
    {
        $limitEsc = (int)$limit;
        $sql = "SELECT bp.*, CONCAT(u.first_name, ' ', u.last_name) AS author_name 
                FROM blog_posts bp LEFT JOIN users u ON bp.author_id = u.id
                WHERE bp.status = 'published' ORDER BY bp.published_at DESC LIMIT $limitEsc";
        $result = mysqli_query($this->conn, $sql);
        $posts = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $posts[] = $row;
            }
        }
        return $posts;
    }

    public function getAllPosts()
    {
        $sql = "SELECT bp.*, CONCAT(u.first_name, ' ', u.last_name) AS author_name 
                FROM blog_posts bp LEFT JOIN users u ON bp.author_id = u.id
                ORDER BY bp.created_at DESC";
        $result = mysqli_query($this->conn, $sql);
        $posts = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $posts[] = $row;
            }
        }
        return $posts;
    }

    public function getPostById($id)
    {
        $idEsc = (int)$id;
        $sql = "SELECT bp.*, CONCAT(u.first_name, ' ', u.last_name) AS author_name 
                FROM blog_posts bp LEFT JOIN users u ON bp.author_id = u.id
                WHERE bp.id = $idEsc LIMIT 1";
        $result = mysqli_query($this->conn, $sql);
        if ($result && mysqli_num_rows($result) === 1) {
            return mysqli_fetch_assoc($result);
        }
        return null;
    }
}

