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

    private $availableTags = [
        'MS-DOS / PC-DOS', 'Windows 1.0', 'Windows 2.0', 'Windows 3.0/3.1', 'Windows 95', 'Windows 98', 'Windows ME', 
        'Windows NT 3.x', 'Windows NT 4.0', 'Windows 2000', 'Windows XP', 'Windows Vista', 'Windows 7', 'Windows 8', 
        'Windows 8.1', 'Windows 10', 'Windows 11', 'Windows Server 2003', 'Windows Server 2008', 'Windows Server 2012', 
        'Windows Server 2016', 'Windows Server 2019', 'Windows Server 2022', 'System 1', 'System 2', 'System 3', 
        'System 4', 'System 5', 'System 6', 'System 7', 'Mac OS 8', 'Mac OS 9', 'Mac OS X Cheetah (10.0)', 
        'Mac OS X Puma (10.1)', 'Mac OS X Jaguar (10.2)', 'Mac OS X Panther (10.3)', 'Mac OS X Tiger (10.4)', 
        'Mac OS X Leopard (10.5)', 'Mac OS X Snow Leopard (10.6)', 'Mac OS X Lion (10.7)', 'Mac OS X Mountain Lion (10.8)', 
        'Mac OS X Mavericks (10.9)', 'Mac OS X Yosemite (10.10)', 'Mac OS X El Capitan (10.11)', 'macOS Sierra (10.12)', 
        'macOS High Sierra (10.13)', 'macOS Mojave (10.14)', 'macOS Catalina (10.15)', 'macOS Big Sur (11)', 
        'macOS Monterey (12)', 'macOS Ventura (13)', 'macOS Sonoma (14)', 'Darwin', 'NeXTSTEP', 'OpenStep', 
        'Linux (kernel)', 'Debian', 'Ubuntu', 'Red Hat Enterprise Linux (RHEL)', 'Fedora', 'CentOS', 'AlmaLinux', 
        'Rocky Linux', 'SUSE', 'openSUSE', 'Arch Linux', 'Manjaro', 'Gentoo', 'Slackware', 'Linux Mint', 'Pop!_OS', 
        'Elementary OS', 'Zorin OS', 'Alpine Linux', 'CoreOS / Flatcar', 'Qubes OS', 'FreeBSD', 'OpenBSD', 'NetBSD', 
        'DragonFly BSD', 'Solaris', 'OpenSolaris', 'illumos', 'OpenIndiana', 'OmniOS', 'SmartOS', 'AIX', 'HP-UX', 
        'Tru64 UNIX', 'SCO UnixWare', 'SCO OpenServer', 'Plan 9', 'Minix', 'QNX', 'VxWorks', 'Varios RTOS (FreeRTOS, Zephyr, RTEMS)', 
        'SteamOS', 'Batocera.linux', 'RetroPie', 'Recalbox', 'Lakka', 'EmuELEC', '351ELEC', 'ArkOS', 'LibreELEC', 
        'OpenELEC', 'RetroArch', 'Orbis OS (PlayStation)', 'Xbox OS', 'Horizon OS (Nintendo Switch)', 'Android 1.x', 
        'Android 2.x', 'Android 3.x', 'Android 4.x', 'Android 5.x', 'Android 6.x', 'Android 7.x', 'Android 8.x', 
        'Android 9', 'Android 10', 'Android 11', 'Android 12', 'Android 13', 'Android 14', 'MIUI', 'One UI', 'ColorOS', 
        'Amazon Fire OS', 'GrapheneOS', 'LineageOS', '/e/ OS', 'CalyxOS', 'iPhone OS 1–3', 'iOS 4', 'iOS 5', 'iOS 6', 
        'iOS 7', 'iOS 8', 'iOS 9', 'iOS 10', 'iOS 11', 'iOS 12', 'iOS 13', 'iOS 14', 'iOS 15', 'iOS 16', 'iOS 17', 
        'iPadOS', 'watchOS', 'tvOS', 'visionOS', 'Windows Mobile', 'Windows Phone 7', 'Windows Phone 8', 
        'Windows Phone 8.1', 'Windows 10 Mobile', 'BlackBerry OS', 'BlackBerry 10', 'Symbian', 'webOS', 'Tizen', 
        'Sailfish OS', 'KaiOS', 'Ubuntu Touch', 'postmarketOS', 'Mobian', 'Firefox OS', 'Palm OS / Garnet'
    ];

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

            // Procesar etiquetas
            $etiquetas = $_POST['etiquetas_so'] ?? [];
            if (is_array($etiquetas)) {
                $etiquetas = array_slice($etiquetas, 0, 10); // Máximo 10
                $etiquetasJson = json_encode($etiquetas);
            } else {
                $etiquetasJson = '[]';
            }

            $this->userModel->updateProfile($user['id'], $nombreReal, $biografia, $avatarUrl, $etiquetasJson);
            header('Location: /perfil');
            exit;
        }

        $availableTags = $this->availableTags;
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