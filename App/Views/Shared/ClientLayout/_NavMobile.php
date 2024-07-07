<?php

use App\Services\Common\Session; ?>

<nav id="main-nav">
    <ul class="second-nav">
        <li>
            <a href="/"><i class="icofont-smart-phone mr-2"></i>Trang chủ</a>
        </li>
        <?php
        if (isset(Session::get('user')->Username)) { ?>
            <li>
                <a href="#"><i class="icofont-ui-user mr-2"></i>Quản lý tài khoản</a>
                <ul>
                    <li>
                        <a class="dropdown-item" href="/profile">Tài khoản của tôi</a>
                    </li>
            </li>
            <li><a class="dropdown-item" href="/auth/logout">Đăng xuất</a></li>
        </ul>
        </li>
    <?php } else { ?>
        <li>
            <a href="#"><i class="icofont-login mr-2"></i>Xác thực</a>
            <ul>
                <li><a class="dropdown-item" href="/auth/login">Đăng nhập</a></li>
                <li><a class="dropdown-item" href="/auth/register">Đăng kí</a></li>
            </ul>
        </li>
    <?php } ?>
        <?php foreach ($pages as $page) { ?>
            <li>
                <a class="dropdown-item" href="/trang/<?= $page->Slug ?>-<?= $page->Id ?>"><?= $page->Title ?></a>
            </li>
        <?php } ?>

    </ul>
    <ul class="bottom-nav">
        <li class="email">
            <a class="text-success" href="/">
                <p class="h5 m-0"><i class="icofont-home text-success"></i></p>
                Trang chủ
            </a>
        </li>
    </ul>
</nav>