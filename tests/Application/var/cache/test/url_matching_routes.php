<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/admin/board-games/new' => [[['_route' => 'app_admin_board_game_create', '_controller' => 'Sylius\\Component\\Resource\\Action\\PlaceHolderAction', '_sylius' => ['resource' => 'app.board_game', 'section' => 'admin']], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
        '/admin/board-games' => [[['_route' => 'app_admin_board_game_index', '_controller' => 'Sylius\\Component\\Resource\\Action\\PlaceHolderAction', '_sylius' => ['resource' => 'app.board_game', 'section' => 'admin']], null, ['GET' => 0], null, false, false, null]],
        '/admin/subscriptions' => [[['_route' => 'app_admin_subscription_index', '_controller' => 'Sylius\\Component\\Resource\\Action\\PlaceHolderAction', '_sylius' => ['resource' => 'app.subscription', 'section' => 'admin']], null, ['GET' => 0], null, false, false, null]],
        '/admin/subscriptions/new' => [[['_route' => 'app_admin_subscription_create', '_controller' => 'Sylius\\Component\\Resource\\Action\\PlaceHolderAction', '_sylius' => ['resource' => 'app.subscription', 'section' => 'admin']], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
        '/admin/subscriptions/bulk_delete' => [[['_route' => 'app_admin_subscription_bulk_delete', '_controller' => 'Sylius\\Component\\Resource\\Action\\PlaceHolderAction', '_sylius' => ['resource' => 'app.subscription', 'section' => 'admin']], null, ['DELETE' => 0], null, false, false, null]],
        '/ajax/subscriptions' => [
            [['_route' => 'app_ajax_subscription_get_collection', '_controller' => 'Sylius\\Component\\Resource\\Action\\PlaceHolderAction', '_sylius' => ['resource' => 'app.subscription', 'section' => 'ajax']], null, ['GET' => 0], null, false, false, null],
            [['_route' => 'app_ajax_subscription_post', '_controller' => 'Sylius\\Component\\Resource\\Action\\PlaceHolderAction', '_sylius' => ['resource' => 'app.subscription', 'section' => 'ajax']], null, ['POST' => 0], null, false, false, null],
        ],
        '/blog-posts' => [
            [['_route' => 'app_blog_post_index', '_controller' => 'app.controller.blog_post::indexAction', '_sylius' => ['serialization_groups' => ['Default'], 'permission' => false]], null, ['GET' => 0], null, true, false, null],
            [['_route' => 'app_blog_post_create', '_controller' => 'app.controller.blog_post::createAction', '_sylius' => ['serialization_groups' => ['Default', 'Detailed'], 'permission' => false]], null, ['POST' => 0], null, true, false, null],
        ],
        '/books' => [
            [['_route' => 'app_book_index', '_controller' => 'app.controller.book::indexAction', '_sylius' => ['serialization_groups' => ['Default'], 'permission' => false]], null, ['GET' => 0], null, true, false, null],
            [['_route' => 'app_book_create', '_controller' => 'app.controller.book::createAction', '_sylius' => ['serialization_groups' => ['Default', 'Detailed'], 'permission' => false]], null, ['POST' => 0], null, true, false, null],
        ],
        '/gedmos' => [
            [['_route' => 'app_gedmo_index', '_controller' => 'app.controller.gedmo::indexAction', '_sylius' => ['serialization_groups' => ['Default'], 'permission' => false]], null, ['GET' => 0], null, true, false, null],
            [['_route' => 'app_gedmo_create', '_controller' => 'app.controller.gedmo::createAction', '_sylius' => ['serialization_groups' => ['Default', 'Detailed'], 'permission' => false]], null, ['POST' => 0], null, true, false, null],
        ],
        '/pull-requests' => [
            [['_route' => 'app_pull_request_index', '_controller' => 'app.controller.pull_request::indexAction', '_sylius' => ['serialization_groups' => ['Default'], 'permission' => false]], null, ['GET' => 0], null, true, false, null],
            [['_route' => 'app_pull_request_create', '_controller' => 'app.controller.pull_request::createAction', '_sylius' => ['serialization_groups' => ['Default', 'Detailed'], 'permission' => false]], null, ['POST' => 0], null, true, false, null],
        ],
        '/sortable-books' => [[['_route' => 'app_book_sortable_index', '_controller' => 'App\\Controller\\BookController::indexAction', '_sylius' => ['sortable' => true]], null, ['GET' => 0], null, true, false, null]],
        '/filterable-books' => [[['_route' => 'app_book_filterable_index', '_controller' => 'app.controller.book::indexAction', '_sylius' => ['filterable' => true]], null, ['GET' => 0], null, true, false, null]],
        '/create-custom-book' => [[['_route' => 'app_create_book_with_custom_factory', '_controller' => 'app.controller.book::createAction', '_sylius' => ['factory' => ['method' => ['expr:service(\'test.custom_book_factory\')', 'createCustom']]]], null, ['POST' => 0], null, false, false, null]],
        '/find-custom-books' => [[['_route' => 'app_index_books_with_custom_repository', '_controller' => 'app.controller.book::indexAction', '_sylius' => ['repository' => ['method' => ['expr:service(\'test.custom_book_repository\')', 'findCustomBooks']]]], null, ['GET' => 0], null, false, false, null]],
        '/find-custom-book' => [[['_route' => 'app_show_book_with_custom_repository', '_controller' => 'app.controller.book::showAction', '_sylius' => ['repository' => ['method' => ['expr:service(\'test.custom_book_repository\')', 'findCustomBook'], 'arguments' => ['J.R.R. Tolkien']]]], null, ['GET' => 0], null, false, false, null]],
        '/science-books' => [[['_route' => 'app_science_book_index', '_controller' => 'app.controller.science_book::indexAction', '_sylius' => ['grid' => 'science_book_grid', 'template' => 'ScienceBook/index.html.twig', 'permission' => false]], null, ['GET' => 0], null, true, false, null]],
        '/science-books/new' => [[['_route' => 'app_science_book_create', '_controller' => 'app.controller.science_book::createAction', '_sylius' => ['template' => 'ScienceBook/create.html.twig', 'permission' => false]], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/a(?'
                    .'|dmin/(?'
                        .'|board\\-games/([^/]++)(?'
                            .'|/edit(*:49)'
                            .'|(*:56)'
                        .')'
                        .'|subscriptions/([^/]++)(?'
                            .'|/(?'
                                .'|edit(*:97)'
                                .'|accept(*:110)'
                                .'|reject(*:124)'
                            .')'
                            .'|(*:133)'
                        .')'
                    .')'
                    .'|jax/subscriptions/([^/]++)(?'
                        .'|(*:172)'
                    .')'
                .')'
                .'|/b(?'
                    .'|log\\-posts/([^/]++)(?'
                        .'|(*:209)'
                        .'|/([^/]++)(*:226)'
                    .')'
                    .'|ooks/([^/]++)(?'
                        .'|(*:251)'
                    .')'
                .')'
                .'|/gedmos/([^/]++)(?'
                    .'|(*:280)'
                .')'
                .'|/pull\\-requests/([^/]++)(?'
                    .'|(*:316)'
                    .'|/([^/]++)(*:333)'
                .')'
                .'|/v([^/]++)/comic\\-books(?'
                    .'|(*:368)'
                    .'|/([^/]++)(?'
                        .'|(*:388)'
                    .')'
                .')'
                .'|/science\\-books/(?'
                    .'|([^/]++)(?'
                        .'|/edit(*:433)'
                        .'|(*:441)'
                    .')'
                    .'|bulk\\-delete(*:462)'
                    .'|([^/]++)(*:478)'
                .')'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        49 => [[['_route' => 'app_admin_board_game_update', '_controller' => 'Sylius\\Component\\Resource\\Action\\PlaceHolderAction', '_sylius' => ['resource' => 'app.board_game', 'section' => 'admin']], ['id'], ['GET' => 0, 'PUT' => 1], null, false, false, null]],
        56 => [
            [['_route' => 'app_admin_board_game_show', '_controller' => 'Sylius\\Component\\Resource\\Action\\PlaceHolderAction', '_sylius' => ['resource' => 'app.board_game', 'section' => 'admin']], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'app_admin_board_game_delete', '_controller' => 'Sylius\\Component\\Resource\\Action\\PlaceHolderAction', '_sylius' => ['resource' => 'app.board_game', 'section' => 'admin']], ['id'], ['DELETE' => 0], null, false, true, null],
        ],
        97 => [[['_route' => 'app_admin_subscription_update', '_controller' => 'Sylius\\Component\\Resource\\Action\\PlaceHolderAction', '_sylius' => ['resource' => 'app.subscription', 'section' => 'admin']], ['id'], ['GET' => 0, 'PUT' => 1], null, false, false, null]],
        110 => [[['_route' => 'app_admin_subscription_accept', '_controller' => 'Sylius\\Component\\Resource\\Action\\PlaceHolderAction', '_sylius' => ['resource' => 'app.subscription', 'section' => 'admin']], ['id'], ['PUT' => 0, 'PATCH' => 1], null, false, false, null]],
        124 => [[['_route' => 'app_admin_subscription_reject', '_controller' => 'Sylius\\Component\\Resource\\Action\\PlaceHolderAction', '_sylius' => ['resource' => 'app.subscription', 'section' => 'admin']], ['id'], ['PUT' => 0, 'PATCH' => 1], null, false, false, null]],
        133 => [
            [['_route' => 'app_admin_subscription_delete', '_controller' => 'Sylius\\Component\\Resource\\Action\\PlaceHolderAction', '_sylius' => ['resource' => 'app.subscription', 'section' => 'admin']], ['id'], ['DELETE' => 0], null, false, true, null],
            [['_route' => 'app_admin_subscription_show', '_controller' => 'Sylius\\Component\\Resource\\Action\\PlaceHolderAction', '_sylius' => ['resource' => 'app.subscription', 'section' => 'admin']], ['id'], ['GET' => 0], null, false, true, null],
        ],
        172 => [
            [['_route' => 'app_ajax_subscription_put', '_controller' => 'Sylius\\Component\\Resource\\Action\\PlaceHolderAction', '_sylius' => ['resource' => 'app.subscription', 'section' => 'ajax']], ['id'], ['PUT' => 0], null, false, true, null],
            [['_route' => 'app_ajax_subscription_delete', '_controller' => 'Sylius\\Component\\Resource\\Action\\PlaceHolderAction', '_sylius' => ['resource' => 'app.subscription', 'section' => 'ajax']], ['id'], ['DELETE' => 0], null, false, true, null],
            [['_route' => 'app_ajax_subscription_get', '_controller' => 'Sylius\\Component\\Resource\\Action\\PlaceHolderAction', '_sylius' => ['resource' => 'app.subscription', 'section' => 'ajax']], ['id'], ['GET' => 0], null, false, true, null],
        ],
        209 => [
            [['_route' => 'app_blog_post_update', '_controller' => 'app.controller.blog_post::updateAction', '_sylius' => ['serialization_groups' => ['Default', 'Detailed'], 'permission' => false]], ['id'], ['PUT' => 0, 'PATCH' => 1], null, false, true, null],
            [['_route' => 'app_blog_post_show', '_controller' => 'app.controller.blog_post::showAction', '_sylius' => ['serialization_groups' => ['Default', 'Detailed'], 'permission' => false]], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'app_blog_post_delete', '_controller' => 'app.controller.blog_post::deleteAction', '_sylius' => ['csrf_protection' => false, 'permission' => false]], ['id'], ['DELETE' => 0], null, false, true, null],
        ],
        226 => [[['_route' => 'app_blog_post_apply_transition', '_controller' => 'app.controller.blog_post::applyStateMachineTransitionAction', '_sylius' => ['csrf_protection' => false, 'state_machine' => ['graph' => 'blog_publishing', 'transition' => '$transition']]], ['id', 'transition'], ['PUT' => 0], null, false, true, null]],
        251 => [
            [['_route' => 'app_book_update', '_controller' => 'app.controller.book::updateAction', '_sylius' => ['serialization_groups' => ['Default', 'Detailed'], 'permission' => false]], ['id'], ['PUT' => 0, 'PATCH' => 1], null, false, true, null],
            [['_route' => 'app_book_show', '_controller' => 'app.controller.book::showAction', '_sylius' => ['serialization_groups' => ['Default', 'Detailed'], 'permission' => false]], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'app_book_delete', '_controller' => 'app.controller.book::deleteAction', '_sylius' => ['csrf_protection' => false, 'permission' => false]], ['id'], ['DELETE' => 0], null, false, true, null],
        ],
        280 => [
            [['_route' => 'app_gedmo_update', '_controller' => 'app.controller.gedmo::updateAction', '_sylius' => ['serialization_groups' => ['Default', 'Detailed'], 'permission' => false]], ['id'], ['PUT' => 0, 'PATCH' => 1], null, false, true, null],
            [['_route' => 'app_gedmo_show', '_controller' => 'app.controller.gedmo::showAction', '_sylius' => ['serialization_groups' => ['Default', 'Detailed'], 'permission' => false]], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'app_gedmo_delete', '_controller' => 'app.controller.gedmo::deleteAction', '_sylius' => ['csrf_protection' => false, 'permission' => false]], ['id'], ['DELETE' => 0], null, false, true, null],
        ],
        316 => [
            [['_route' => 'app_pull_request_update', '_controller' => 'app.controller.pull_request::updateAction', '_sylius' => ['serialization_groups' => ['Default', 'Detailed'], 'permission' => false]], ['id'], ['PUT' => 0, 'PATCH' => 1], null, false, true, null],
            [['_route' => 'app_pull_request_show', '_controller' => 'app.controller.pull_request::showAction', '_sylius' => ['serialization_groups' => ['Default', 'Detailed'], 'permission' => false]], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'app_pull_request_delete', '_controller' => 'app.controller.pull_request::deleteAction', '_sylius' => ['csrf_protection' => false, 'permission' => false]], ['id'], ['DELETE' => 0], null, false, true, null],
        ],
        333 => [[['_route' => 'app_pull_request_apply_transition', '_controller' => 'app.controller.pull_request::applyStateMachineTransitionAction', '_sylius' => ['csrf_protection' => false, 'state_machine' => ['graph' => 'pull_request', 'transition' => '$transition']]], ['id', 'transition'], ['PUT' => 0], null, false, true, null]],
        368 => [
            [['_route' => 'app_comic_book_index', '_controller' => 'app.controller.comic_book::indexAction', '_sylius' => ['serialization_groups' => ['Default'], 'serialization_version' => '$version', 'permission' => false]], ['version'], ['GET' => 0], null, true, false, null],
            [['_route' => 'app_comic_book_create', '_controller' => 'app.controller.comic_book::createAction', '_sylius' => ['serialization_groups' => ['Default', 'Detailed'], 'serialization_version' => '$version', 'permission' => false]], ['version'], ['POST' => 0], null, true, false, null],
        ],
        388 => [
            [['_route' => 'app_comic_book_update', '_controller' => 'app.controller.comic_book::updateAction', '_sylius' => ['serialization_groups' => ['Default', 'Detailed'], 'serialization_version' => '$version', 'permission' => false]], ['version', 'id'], ['PUT' => 0, 'PATCH' => 1], null, false, true, null],
            [['_route' => 'app_comic_book_show', '_controller' => 'app.controller.comic_book::showAction', '_sylius' => ['serialization_groups' => ['Default', 'Detailed'], 'serialization_version' => '$version', 'permission' => false]], ['version', 'id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'app_comic_book_delete', '_controller' => 'app.controller.comic_book::deleteAction', '_sylius' => ['csrf_protection' => false, 'serialization_version' => '$version', 'permission' => false]], ['version', 'id'], ['DELETE' => 0], null, false, true, null],
        ],
        433 => [[['_route' => 'app_science_book_update', '_controller' => 'app.controller.science_book::updateAction', '_sylius' => ['template' => 'ScienceBook/update.html.twig', 'permission' => false]], ['id'], ['GET' => 0, 'PUT' => 1, 'PATCH' => 2], null, false, false, null]],
        441 => [[['_route' => 'app_science_book_show', '_controller' => 'app.controller.science_book::showAction', '_sylius' => ['template' => 'ScienceBook/show.html.twig', 'permission' => false]], ['id'], ['GET' => 0], null, false, true, null]],
        462 => [[['_route' => 'app_science_book_bulk_delete', '_controller' => 'app.controller.science_book::bulkDeleteAction', '_sylius' => ['permission' => false, 'paginate' => false, 'repository' => ['method' => 'findById', 'arguments' => ['$ids']]]], [], ['DELETE' => 0], null, false, false, null]],
        478 => [
            [['_route' => 'app_science_book_delete', '_controller' => 'app.controller.science_book::deleteAction', '_sylius' => ['permission' => false]], ['id'], ['DELETE' => 0], null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
