<?php

//get data
$tplShareGlobal = \Lib::tplShareGlobal('admin/');
$tplShareGlobal['site_title'] = "Quản trị";

//add to tpl
\View::share('def', $tplShareGlobal);
\View::share('menuLeft', \Menu::getMenu(0, true));
\View::share('menuTop', \Menu::getMenu(1));
\View::share('defLang', \Lib::getDefaultLang());