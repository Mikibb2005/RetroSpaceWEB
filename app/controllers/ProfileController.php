<?php
class ProfileController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function index($id = null) {
        if (!$id) {
            $id = $this->userModel->getCurrentUserId();
            if (!$id) {
                header('Location: /login');
                exit;
            }
        }

        $user = $this->userModel->getUserById($id);
        if (!$user) {
            header('Location: /'); // O 404
            exit;
        }

        $isOwnProfile = ($id == $this->userModel->getCurrentUserId());
        $isFollowing = false;
        if (!$isOwnProfile && $this->userModel->isLogged()) {
            $isFollowing = $this->userModel->isFollowing($this->userModel->getCurrentUserId(), $id);
        }

        $followers = $this->userModel->getFollowers($id);
        $following = $this->userModel->getFollowing($id);
        $activity = $this->userModel->getUserActivity($id);

        require __DIR__ . '/../views/profile/index.php';
    }

    public function edit() {
        if (!$this->userModel->isLogged()) {
            header('Location: /login');
            exit;
        }

        $user = $this->userModel->getUserById($this->userModel->getCurrentUserId());

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombreReal = $_POST['nombre_real'] ?? '';
            $biografia = $_POST['biografia'] ?? '';
            $avatar = $_POST['avatar'] ?? 'avatar-1.png';
            
            // Validate avatar format
            if (!preg_match('/^avatar-\d+\.png$/', $avatar)) {
                $avatar = 'avatar-1.png';
            }

            require_once __DIR__ . '/../helpers/avatar_helper.php';
            
            // Extract number and get URL
            preg_match('/^avatar-(\d+)\.png$/', $avatar, $matches);
            $avatarNum = $matches[1] ?? 1;
            $avatarUrl = getAvatarUrl($avatarNum);

            $this->userModel->updateProfile($user['id'], $nombreReal, $biografia, $avatarUrl);
            header('Location: /perfil');
            exit;
        }

        require __DIR__ . '/../views/profile/edit.php';
    }

    public function follow($id) {
        if (!$this->userModel->isLogged()) {
            header('Location: /login');
            exit;
        }
        $this->userModel->follow($this->userModel->getCurrentUserId(), $id);
        header('Location: /perfil/' . $id);
        exit;
    }

    public function unfollow($id) {
        if (!$this->userModel->isLogged()) {
            header('Location: /login');
            exit;
        }
        $this->userModel->unfollow($this->userModel->getCurrentUserId(), $id);
        header('Location: /perfil/' . $id);
        exit;
    }
}
?>