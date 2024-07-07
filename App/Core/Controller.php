<?php

namespace App\Core;

use App\Models\User;
use App\Services\Common\AlertSession;
use App\Services\Common\Enums\ERole;
use App\Services\Common\Session;
use App\Services\Common\Validator;
use App\Services\Identities\UserServices\UserService;
use App\Services\MenuService;
use App\Services\NotificationServices\NotificationService;
use App\Services\PageServices\PageService;
use App\Services\SettingServices\SettingService;

class Controller
{
    private $layout = '_LayoutAdmin';
    protected $pageConfig;
    protected $validator;
    public $settingService;
    public $menuService;
    public User $userLogin ;
    public $config;

    public PageService $pageService;
    private NotificationService $notificationService;
    public function __construct()
    {
        $this->pageConfig = Config::PageConfig();
        $this->validator = new Validator();
        $this->settingService = new SettingService();
        $this->menuService = new MenuService();
        $this->config = new Config();
        $this->pageService = new PageService();
        $this->notificationService = new NotificationService();
        if(isset($_SESSION['user'])){
            $user = Session::Get('user') ?? null;
            $userService = new UserService();
            $this->userLogin = $userService->GetById($user->Id);
        }
    }
    protected function view($viewName, $data = [])
    {
        define("Admin",ERole::Admin);
        define("Mod",ERole::Mod);
        define("Member",ERole::Member);

        $layoutName = $data['layout'] ?? $this->layout;
        $viewName = str_replace('.', '/', $viewName);

        $alerts = AlertSession::getAlerts();
        $setting = $this->settingService->GetSetting(1);
        $settingClient = $this->settingService->GetSetting(0);
        $menu = $this->menuService->generateMenu();
        $userLogin = $this->userLogin ?? null;
        $googleKey = $this->config::GoogleRecaptcha_KEY;

        $pages = $this->pageService->GetAllIsMenu(1);
        $notificationsAll = $this->notificationService->GetByType(0)[0]; // 0: ALl, 1: ForUser

        extract($data);
        include "../App/Views/Shared/$layoutName.php";
    }
    protected function render($viewName, $layoutName, $data = [])
    {
        $viewName = str_replace('.', '/', $viewName);
        
        $alerts = AlertSession::getAlerts();
        $setting = $this->settingService->GetSetting(1);
        $settingClient = $this->settingService->GetSetting(0);
        $menu = $this->menuService->generateMenu();
        $userLogin = $this->userLogin ?? null;
        $googleKey = $this->config::GoogleRecaptcha_KEY;

        $pages = $this->pageService->GetAllIsMenu(1);
        $notificationsAll = $this->notificationService->GetByType(0)[0]; // 0: ALl, 1: ForUser
        // Extract data for use in the view
        extract($data);
        // Load the view file
        include "../App/Views/Shared/$layoutName.php";
    }
    protected function json($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        return;
    }
    // redirect to a different page
    protected function redirect($url)
    {
        header('Location: ' . $url);
    }
}
