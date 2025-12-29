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
            $post = mysqli_fetch_assoc($result);
            // Increment views
            $this->incrementViews($idEsc);
            return $post;
        }
        return null;
    }

    public function generateSlug($title)
    {
        $slug = strtolower(trim($title));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');
        
        // Ensure uniqueness
        $originalSlug = $slug;
        $counter = 1;
        while ($this->slugExists($slug)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }

    private function slugExists($slug)
    {
        $slugEsc = mysqli_real_escape_string($this->conn, $slug);
        $sql = "SELECT COUNT(*) as count FROM blog_posts WHERE slug = '$slugEsc'";
        $result = mysqli_query($this->conn, $sql);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            return $row['count'] > 0;
        }
        return false;
    }

    public function createPost($authorId, $title, $excerpt, $content, $imageUrl = null, $status = 'draft')
    {
        $authorIdEsc = (int)$authorId;
        $slug = $this->generateSlug($title);
        $slugEsc = mysqli_real_escape_string($this->conn, $slug);
        $titleEsc = mysqli_real_escape_string($this->conn, $title);
        $excerptEsc = mysqli_real_escape_string($this->conn, $excerpt);
        $contentEsc = mysqli_real_escape_string($this->conn, $content);
        $statusEsc = mysqli_real_escape_string($this->conn, $status);
        $imageUrlEsc = $imageUrl ? mysqli_real_escape_string($this->conn, $imageUrl) : 'NULL';
        
        $publishedAt = ($statusEsc === 'published') ? 'NOW()' : 'NULL';
        
        $sql = "INSERT INTO blog_posts (title, slug, excerpt, content, author_id, image_url, status, published_at) 
                VALUES ('$titleEsc', '$slugEsc', '$excerptEsc', '$contentEsc', $authorIdEsc, " . ($imageUrlEsc !== 'NULL' ? "'$imageUrlEsc'" : 'NULL') . ", '$statusEsc', $publishedAt)";

        if (mysqli_query($this->conn, $sql)) {
            return [
                'success' => true,
                'post_id' => mysqli_insert_id($this->conn),
                'slug' => $slug
            ];
        }
        
        return ['success' => false, 'error' => mysqli_error($this->conn)];
    }

    public function updatePost($id, $title, $excerpt, $content, $imageUrl = null, $status = null)
    {
        $idEsc = (int)$id;
        $titleEsc = mysqli_real_escape_string($this->conn, $title);
        $excerptEsc = mysqli_real_escape_string($this->conn, $excerpt);
        $contentEsc = mysqli_real_escape_string($this->conn, $content);
        $imageUrlEsc = $imageUrl !== null ? (mysqli_real_escape_string($this->conn, $imageUrl) ? "'" . mysqli_real_escape_string($this->conn, $imageUrl) . "'" : 'NULL') : null;
        
        // Get current post to update slug if title changed
        $currentPost = $this->getPostByIdWithoutIncrement($idEsc);
        if (!$currentPost) {
            return ['success' => false, 'error' => 'Post not found'];
        }
        
        $updates = [];
        if ($titleEsc !== $currentPost['title']) {
            $slug = $this->generateSlug($titleEsc);
            $slugEsc = mysqli_real_escape_string($this->conn, $slug);
            $updates[] = "title = '$titleEsc'";
            $updates[] = "slug = '$slugEsc'";
        }
        
        $updates[] = "excerpt = '$excerptEsc'";
        $updates[] = "content = '$contentEsc'";
        
        if ($imageUrlEsc !== null) {
            $updates[] = "image_url = $imageUrlEsc";
        }
        
        if ($status !== null) {
            $statusEsc = mysqli_real_escape_string($this->conn, $status);
            $updates[] = "status = '$statusEsc'";
            if ($statusEsc === 'published' && $currentPost['status'] !== 'published') {
                $updates[] = "published_at = NOW()";
            }
        }
        
        $updates[] = "updated_at = NOW()";
        
        $sql = "UPDATE blog_posts SET " . implode(', ', $updates) . " WHERE id = $idEsc";
        
        if (mysqli_query($this->conn, $sql)) {
            return ['success' => true];
        }
        
        return ['success' => false, 'error' => mysqli_error($this->conn)];
    }

    public function deletePost($id)
    {
        $idEsc = (int)$id;
        $sql = "DELETE FROM blog_posts WHERE id = $idEsc";
        
        if (mysqli_query($this->conn, $sql)) {
            return ['success' => true];
        }
        
        return ['success' => false, 'error' => mysqli_error($this->conn)];
    }

    private function getPostByIdWithoutIncrement($id)
    {
        $idEsc = (int)$id;
        $sql = "SELECT * FROM blog_posts WHERE id = $idEsc LIMIT 1";
        $result = mysqli_query($this->conn, $sql);
        if ($result && mysqli_num_rows($result) === 1) {
            return mysqli_fetch_assoc($result);
        }
        return null;
    }

    private function incrementViews($id)
    {
        $idEsc = (int)$id;
        $sql = "UPDATE blog_posts SET views = views + 1 WHERE id = $idEsc";
        mysqli_query($this->conn, $sql);
    }

    public function getPostBySlug($slug)
    {
        $slugEsc = mysqli_real_escape_string($this->conn, $slug);
        $sql = "SELECT bp.*, CONCAT(u.first_name, ' ', u.last_name) AS author_name 
                FROM blog_posts bp LEFT JOIN users u ON bp.author_id = u.id
                WHERE bp.slug = '$slugEsc' AND bp.status = 'published' LIMIT 1";
        $result = mysqli_query($this->conn, $sql);
        if ($result && mysqli_num_rows($result) === 1) {
            $post = mysqli_fetch_assoc($result);
            // Increment views
            $this->incrementViews($post['id']);
            return $post;
        }
        return null;
    }
}

