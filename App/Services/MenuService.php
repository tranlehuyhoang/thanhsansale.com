<?php
namespace App\Services;
class MenuService
{
    private $menu;

    public function __construct()
    {
        require_once __DIR__ . '/../../App/Routes/Menu.php';
        $this->menu = $menu;
    }

    public function generateMenu()
    {
        $html = '<ul class="metismenu list-unstyled" id="side-menu">';
        foreach ($this->menu as $item) {
            if (isset($item['children']) && count($item['children']) > 0) {
                $html .= '<li class="menu-title" data-key="t-menu">' . $item['name'] . '</li>';
                foreach ($item['children'] as $child) {
                    $html .= $this->generateMenuItem($child);
                }
            } else {
                $html .= '<li class="menu-title mt-2" data-key="t-components">' . $item['name'] . '</li>';
            }
        }
        $html .= '</ul>';
        return $html;
    }
    private function generateMenuItem($item)
    {
        $html = '';
        if (empty($item['children'])) {
            $html .= '<li><a href="' . $item['url'] . '"><i data-feather="' . $item['icon'] . '"></i><span data-key="t-dashboard">' . $item['name'] . '</span></a></li>';
        } else {
            $html .= '<li><a href="javascript: void(0);" class="has-arrow"><i data-feather="' . $item['icon'] . '"></i><span data-key="t-authentication">' . $item['name'] . '</span></a><ul class="sub-menu" aria-expanded="false">';
            foreach ($item['children'] as $child) {
                $html .= '<li><a href="' . $child['url'] . '" data-key="t-login">' . $child['name'] . '</a></li>';
            }
            $html .= '</ul></li>';
        }
        return $html;
    }
}
