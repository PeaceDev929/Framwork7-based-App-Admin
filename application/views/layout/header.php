<!DOCTYPE html>
<!--[if IE 8]>
<html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>
<html lang="en" class="ie9 no-js"> <![endif]-->
<html lang="en">
<head>
    <head>
        <meta charset="utf-8"/>
        <title>BunYang</title>
        <link href="<?= base_url() ?>assets/images/logo2.png" rel="icon">
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="<?= base_url() ?>assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet"
              type="text/css"/>
        <link href="<?= base_url() ?>assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet"
              type="text/css"/>
        <link href="<?= base_url() ?>assets/global/plugins/bootstrap-sweetalert/sweetalert.css" rel="stylesheet"
              type="text/css"/>
        <link href="<?= base_url() ?>assets/global/plugins/bootstrap-toastr/toastr.min.css" rel="stylesheet"
              type="text/css"/>
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="<?= base_url() ?>assets/global/plugins/datatables/datatables.min.css" rel="stylesheet"
              type="text/css"/>
        <link href="<?= base_url() ?>assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css"
              rel="stylesheet" type="text/css"/>
        <link href="<?= base_url() ?>assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css"
              rel="stylesheet" type="text/css"/>
        <!--        <link href="-->
        <? //=base_url()?><!--assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />-->
        <!--        <link href="-->
        <? //=base_url()?><!--assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />-->
        <link href="<?= base_url() ?>assets/global/plugins/select2/css/select2.min.css" rel="stylesheet"
              type="text/css"/>
        <link href="<?= base_url() ?>assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet"
              type="text/css"/>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="<?= base_url() ?>assets/global/css/components.min.css" rel="stylesheet" id="style_components"
              type="text/css"/>
        <link href="<?= base_url() ?>assets/global/css/plugins.min.css" rel="stylesheet" type="text/css"/>
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="<?= base_url() ?>assets/layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css"/>
        <link href="<?= base_url() ?>assets/layouts/layout/css/themes/darkblue.min.css" rel="stylesheet" type="text/css"
              id="style_color"/>
        <link href="<?= base_url() ?>assets/css/custom.css?time=<?= time() ?>" rel="stylesheet" type="text/css"/>
        <script src="<?= base_url() ?>assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="<?= base_url() ?>assets/js/jquery.form.js" type="text/javascript"></script>
    </head>
<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white  page-sidebar-fixed" id="total_body">
<div class="page-wrapper">
    <div class="page-header navbar navbar-fixed-top">
        <div class="page-header-inner ">
            <div class="page-logo" style="">
                <label style="color: white;font-weight: 600;font-size: 20px;margin: auto;height: 50px;line-height: 50px;">BunYang</label>
            </div>
            <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse"
               data-target=".navbar-collapse">
                <span></span>
            </a>
            <div class="top-menu">
                <ul class="nav navbar-nav pull-right">
                    <li class="dropdown dropdown-user">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
                           data-close-others="true">
                            <span class="username username-hide-on-mobile"> The administrator has logged in. </span>
                            <i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-default">
                            <li>
                                <a href="<?= site_url('login/logout') ?>">
                                    <i class="icon-logout"></i> Logout </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="page-container">
        <div class="page-content-wrapper">
            <div class="page-sidebar-wrapper">
                <div class="page-sidebar navbar-collapse collapse">
                    <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true"
                        data-slide-speed="200">
                        <li class="nav-item <?= $page_index == 0 ? 'active' : '' ?>">
                            <a class="nav-link nav-toggle" href="<?= site_url('User/index') ?>">
                                <i class="fa fa-genderless"></i>
                                <span class="title">Member Manage</span>
                                <span class="<?= $page_index == 0 ? 'selected' : '' ?>"></span>
                            </a>
                        </li>
                        <li class="nav-item <?= $page_index == 1 ? 'active' : '' ?>">
                            <a class="nav-link nav-toggle" href="<?= site_url('Chat/index') ?>">
                                <i class="fa fa-genderless"></i>
                                <span class="title">1:1Chatting Manage</span>
                                <span class="<?= $page_index == 1 ? 'selected' : '' ?>"></span>
                            </a>
                        </li>
                        <li class="nav-item <?= $page_index == 2 ? 'active' : '' ?>">
                            <a class="nav-link nav-toggle" href="<?= site_url('Room/index') ?>">
                                <i class="fa fa-genderless"></i>
                                <span class="title">Chatting Room Manage</span>
                                <span class="<?= $page_index == 2 ? 'selected' : '' ?>"></span>
                            </a>
                        </li>
                        <li class="nav-item <?= $page_index == 3 ? 'active' : '' ?>">
                            <a class="nav-link nav-toggle" href="<?= site_url('Board/index') ?>">
                                <i class="fa fa-genderless"></i>
                                <span class="title">Board Manage</span>
                                <span class="<?= $page_index == 3 ? 'selected' : '' ?>"></span>
                            </a>
                        </li>
                        <li class="nav-item <?= $page_index == 4 ? 'active' : '' ?>">
                            <a class="nav-link nav-toggle" href="<?= site_url('Banner/index') ?>">
                                <i class="fa fa-genderless"></i>
                                <span class="title">Banner Manage</span>
                                <span class="<?= $page_index == 4 ? 'selected' : '' ?>"></span>
                            </a>
                        </li>
                        <li class="nav-item <?= $page_index == 5 ? 'active' : '' ?>">
                            <a class="nav-link nav-toggle" href="<?= site_url('PostBanner/index') ?>">
                                <i class="fa fa-genderless"></i>
                                <span class="title">Bunyang Banner Manage</span>
                                <span class="<?= $page_index == 5 ? 'selected' : '' ?>"></span>
                            </a>
                        </li>
                        <li class="nav-item <?= $page_index == 6 ? 'active' : '' ?>">
                            <a class="nav-link nav-toggle" href="<?= site_url('Post/index') ?>">
                                <i class="fa fa-genderless"></i>
                                <span class="title">Bunyang Post</span>
                                <span class="<?= $page_index == 6 ? 'selected' : '' ?>"></span>
                            </a>
                        </li>
                        <li class="nav-item <?= $page_index == 7 ? 'active' : '' ?>">
                            <a class="nav-link nav-toggle" href="<?= site_url('Interest/index') ?>">
                                <i class="fa fa-genderless"></i>
                                <span class="title">Interest Customer Manage</span>
                                <span class="<?= $page_index == 7 ? 'selected' : '' ?>"></span>
                            </a>
                        </li>
                        <li class="nav-item <?= $page_index == 8 ? 'active' : '' ?>">
                            <a class="nav-link nav-toggle" href="<?= site_url('Push/index') ?>">
                                <i class="fa fa-genderless"></i>
                                <span class="title">Push Manage</span>
                                <span class="<?= $page_index == 8 ? 'selected' : '' ?>"></span>
                            </a>
                        </li>
                        <li class="nav-item <?= $page_index == 9 ? 'active' : '' ?>">
                            <a class="nav-link nav-toggle" href="<?= site_url('Notice') ?>">
                                <i class="fa fa-genderless"></i>
                                <span class="title">Notice Manage</span>
                                <span class="<?= $page_index == 9 ? 'selected' : '' ?>"></span>
                            </a>
                        </li>
                        <li class="nav-item <?= $page_index == 10 ? 'active' : '' ?>">
                            <a class="nav-link nav-toggle" href="<?= site_url('Report') ?>">
                                <i class="fa fa-genderless"></i>
                                <span class="title">Report Manage</span>
                                <span class="<?= $page_index == 10 ? 'selected' : '' ?>"></span>
                            </a>
                        </li>
                        <li class="nav-item <?= $page_index == 11 ? 'active' : '' ?>">
                            <a class="nav-link nav-toggle" href="<?= site_url('InvestmentRoom') ?>">
                                <i class="fa fa-genderless"></i>
                                <span class="title">투자방관리</span>
                                <span class="<?= $page_index == 11 ? 'selected' : '' ?>"></span>
                            </a>
                        </li>
                        <li class="nav-item <?= $page_index == 12 ? 'active' : '' ?>">
                            <a class="nav-link nav-toggle" href="<?= site_url('Refund') ?>">
                                <i class="fa fa-genderless"></i>
                                <span class="title">Point Application History</span>
                                <span class="<?= $page_index == 12 ? 'selected' : '' ?>"></span>
                            </a>
                        </li>
                        <li class="nav-item <?= $page_index == 13 ? 'active' : '' ?>">
                            <a class="nav-link nav-toggle" href="<?= site_url('BoardReport') ?>">
                                <i class="fa fa-genderless"></i>
                                <span class="title">Board Report Manage</span>
                                <span class="<?= $page_index == 13 ? 'selected' : '' ?>"></span>
                            </a>
                        </li>
                        <li class="nav-item <?= $page_index == 14 ? 'active' : '' ?>">
                            <a class="nav-link nav-toggle" href="<?= site_url('Block') ?>">
                                <i class="fa fa-genderless"></i>
                                <span class="title">Block Manage</span>
                                <span class="<?= $page_index == 14 ? 'selected' : '' ?>"></span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="page-content" id="div_page_content">
                <div class="page-bar">
                    <ul class="page-breadcrumb">
                        <i class="fa fa-home" style="color: #92d050;"></i>
                        <span id="page_title" style="font-weight: 700;font-size: 15px;color:#92d050">&nbsp;>&nbsp;<a
                                    style="color: #92d050"><?= $page_title ?></a></span>
                    </ul>
                </div>