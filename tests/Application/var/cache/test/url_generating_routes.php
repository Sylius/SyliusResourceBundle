<?php

// This file has been auto-generated by the Symfony Routing Component.

return [
    'app_admin_board_game_create' => [[], ['_controller' => 'Sylius\\Component\\Resource\\Action\\PlaceHolderAction', '_sylius' => ['resource' => 'app.board_game', 'section' => 'admin']], [], [['text', '/admin/board-games/new']], [], [], []],
    'app_admin_board_game_update' => [['id'], ['_controller' => 'Sylius\\Component\\Resource\\Action\\PlaceHolderAction', '_sylius' => ['resource' => 'app.board_game', 'section' => 'admin']], [], [['text', '/edit'], ['variable', '/', '[^/]++', 'id', true], ['text', '/admin/board-games']], [], [], []],
    'app_admin_board_game_index' => [[], ['_controller' => 'Sylius\\Component\\Resource\\Action\\PlaceHolderAction', '_sylius' => ['resource' => 'app.board_game', 'section' => 'admin']], [], [['text', '/admin/board-games']], [], [], []],
    'app_admin_board_game_show' => [['id'], ['_controller' => 'Sylius\\Component\\Resource\\Action\\PlaceHolderAction', '_sylius' => ['resource' => 'app.board_game', 'section' => 'admin']], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/admin/board-games']], [], [], []],
    'app_admin_board_game_delete' => [['id'], ['_controller' => 'Sylius\\Component\\Resource\\Action\\PlaceHolderAction', '_sylius' => ['resource' => 'app.board_game', 'section' => 'admin']], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/admin/board-games']], [], [], []],
    'app_admin_subscription_index' => [[], ['_controller' => 'Sylius\\Component\\Resource\\Action\\PlaceHolderAction', '_sylius' => ['resource' => 'app.subscription', 'section' => 'admin']], [], [['text', '/admin/subscriptions']], [], [], []],
    'app_admin_subscription_create' => [[], ['_controller' => 'Sylius\\Component\\Resource\\Action\\PlaceHolderAction', '_sylius' => ['resource' => 'app.subscription', 'section' => 'admin']], [], [['text', '/admin/subscriptions/new']], [], [], []],
    'app_admin_subscription_update' => [['id'], ['_controller' => 'Sylius\\Component\\Resource\\Action\\PlaceHolderAction', '_sylius' => ['resource' => 'app.subscription', 'section' => 'admin']], [], [['text', '/edit'], ['variable', '/', '[^/]++', 'id', true], ['text', '/admin/subscriptions']], [], [], []],
    'app_admin_subscription_bulk_delete' => [[], ['_controller' => 'Sylius\\Component\\Resource\\Action\\PlaceHolderAction', '_sylius' => ['resource' => 'app.subscription', 'section' => 'admin']], [], [['text', '/admin/subscriptions/bulk_delete']], [], [], []],
    'app_admin_subscription_accept' => [['id'], ['_controller' => 'Sylius\\Component\\Resource\\Action\\PlaceHolderAction', '_sylius' => ['resource' => 'app.subscription', 'section' => 'admin']], [], [['text', '/accept'], ['variable', '/', '[^/]++', 'id', true], ['text', '/admin/subscriptions']], [], [], []],
    'app_admin_subscription_reject' => [['id'], ['_controller' => 'Sylius\\Component\\Resource\\Action\\PlaceHolderAction', '_sylius' => ['resource' => 'app.subscription', 'section' => 'admin']], [], [['text', '/reject'], ['variable', '/', '[^/]++', 'id', true], ['text', '/admin/subscriptions']], [], [], []],
    'app_admin_subscription_delete' => [['id'], ['_controller' => 'Sylius\\Component\\Resource\\Action\\PlaceHolderAction', '_sylius' => ['resource' => 'app.subscription', 'section' => 'admin']], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/admin/subscriptions']], [], [], []],
    'app_admin_subscription_show' => [['id'], ['_controller' => 'Sylius\\Component\\Resource\\Action\\PlaceHolderAction', '_sylius' => ['resource' => 'app.subscription', 'section' => 'admin']], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/admin/subscriptions']], [], [], []],
    'app_ajax_subscription_get_collection' => [[], ['_controller' => 'Sylius\\Component\\Resource\\Action\\PlaceHolderAction', '_sylius' => ['resource' => 'app.subscription', 'section' => 'ajax']], [], [['text', '/ajax/subscriptions']], [], [], []],
    'app_ajax_subscription_post' => [[], ['_controller' => 'Sylius\\Component\\Resource\\Action\\PlaceHolderAction', '_sylius' => ['resource' => 'app.subscription', 'section' => 'ajax']], [], [['text', '/ajax/subscriptions']], [], [], []],
    'app_ajax_subscription_put' => [['id'], ['_controller' => 'Sylius\\Component\\Resource\\Action\\PlaceHolderAction', '_sylius' => ['resource' => 'app.subscription', 'section' => 'ajax']], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/ajax/subscriptions']], [], [], []],
    'app_ajax_subscription_delete' => [['id'], ['_controller' => 'Sylius\\Component\\Resource\\Action\\PlaceHolderAction', '_sylius' => ['resource' => 'app.subscription', 'section' => 'ajax']], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/ajax/subscriptions']], [], [], []],
    'app_ajax_subscription_get' => [['id'], ['_controller' => 'Sylius\\Component\\Resource\\Action\\PlaceHolderAction', '_sylius' => ['resource' => 'app.subscription', 'section' => 'ajax']], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/ajax/subscriptions']], [], [], []],
    'app_blog_post_index' => [[], ['_controller' => 'app.controller.blog_post::indexAction', '_sylius' => ['serialization_groups' => ['Default'], 'permission' => false]], [], [['text', '/blog-posts/']], [], [], []],
    'app_blog_post_create' => [[], ['_controller' => 'app.controller.blog_post::createAction', '_sylius' => ['serialization_groups' => ['Default', 'Detailed'], 'permission' => false]], [], [['text', '/blog-posts/']], [], [], []],
    'app_blog_post_update' => [['id'], ['_controller' => 'app.controller.blog_post::updateAction', '_sylius' => ['serialization_groups' => ['Default', 'Detailed'], 'permission' => false]], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/blog-posts']], [], [], []],
    'app_blog_post_show' => [['id'], ['_controller' => 'app.controller.blog_post::showAction', '_sylius' => ['serialization_groups' => ['Default', 'Detailed'], 'permission' => false]], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/blog-posts']], [], [], []],
    'app_blog_post_delete' => [['id'], ['_controller' => 'app.controller.blog_post::deleteAction', '_sylius' => ['csrf_protection' => false, 'permission' => false]], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/blog-posts']], [], [], []],
    'app_blog_post_apply_transition' => [['id', 'transition'], ['_controller' => 'app.controller.blog_post::applyStateMachineTransitionAction', '_sylius' => ['csrf_protection' => false, 'state_machine' => ['graph' => 'blog_publishing', 'transition' => '$transition']]], [], [['variable', '/', '[^/]++', 'transition', true], ['variable', '/', '[^/]++', 'id', true], ['text', '/blog-posts']], [], [], []],
    'app_book_index' => [[], ['_controller' => 'app.controller.book::indexAction', '_sylius' => ['serialization_groups' => ['Default'], 'permission' => false]], [], [['text', '/books/']], [], [], []],
    'app_book_create' => [[], ['_controller' => 'app.controller.book::createAction', '_sylius' => ['serialization_groups' => ['Default', 'Detailed'], 'permission' => false]], [], [['text', '/books/']], [], [], []],
    'app_book_update' => [['id'], ['_controller' => 'app.controller.book::updateAction', '_sylius' => ['serialization_groups' => ['Default', 'Detailed'], 'permission' => false]], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/books']], [], [], []],
    'app_book_show' => [['id'], ['_controller' => 'app.controller.book::showAction', '_sylius' => ['serialization_groups' => ['Default', 'Detailed'], 'permission' => false]], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/books']], [], [], []],
    'app_book_delete' => [['id'], ['_controller' => 'app.controller.book::deleteAction', '_sylius' => ['csrf_protection' => false, 'permission' => false]], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/books']], [], [], []],
    'app_gedmo_index' => [[], ['_controller' => 'app.controller.gedmo::indexAction', '_sylius' => ['serialization_groups' => ['Default'], 'permission' => false]], [], [['text', '/gedmos/']], [], [], []],
    'app_gedmo_create' => [[], ['_controller' => 'app.controller.gedmo::createAction', '_sylius' => ['serialization_groups' => ['Default', 'Detailed'], 'permission' => false]], [], [['text', '/gedmos/']], [], [], []],
    'app_gedmo_update' => [['id'], ['_controller' => 'app.controller.gedmo::updateAction', '_sylius' => ['serialization_groups' => ['Default', 'Detailed'], 'permission' => false]], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/gedmos']], [], [], []],
    'app_gedmo_show' => [['id'], ['_controller' => 'app.controller.gedmo::showAction', '_sylius' => ['serialization_groups' => ['Default', 'Detailed'], 'permission' => false]], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/gedmos']], [], [], []],
    'app_gedmo_delete' => [['id'], ['_controller' => 'app.controller.gedmo::deleteAction', '_sylius' => ['csrf_protection' => false, 'permission' => false]], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/gedmos']], [], [], []],
    'app_pull_request_index' => [[], ['_controller' => 'app.controller.pull_request::indexAction', '_sylius' => ['serialization_groups' => ['Default'], 'permission' => false]], [], [['text', '/pull-requests/']], [], [], []],
    'app_pull_request_create' => [[], ['_controller' => 'app.controller.pull_request::createAction', '_sylius' => ['serialization_groups' => ['Default', 'Detailed'], 'permission' => false]], [], [['text', '/pull-requests/']], [], [], []],
    'app_pull_request_update' => [['id'], ['_controller' => 'app.controller.pull_request::updateAction', '_sylius' => ['serialization_groups' => ['Default', 'Detailed'], 'permission' => false]], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/pull-requests']], [], [], []],
    'app_pull_request_show' => [['id'], ['_controller' => 'app.controller.pull_request::showAction', '_sylius' => ['serialization_groups' => ['Default', 'Detailed'], 'permission' => false]], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/pull-requests']], [], [], []],
    'app_pull_request_delete' => [['id'], ['_controller' => 'app.controller.pull_request::deleteAction', '_sylius' => ['csrf_protection' => false, 'permission' => false]], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/pull-requests']], [], [], []],
    'app_pull_request_apply_transition' => [['id', 'transition'], ['_controller' => 'app.controller.pull_request::applyStateMachineTransitionAction', '_sylius' => ['csrf_protection' => false, 'state_machine' => ['graph' => 'pull_request', 'transition' => '$transition']]], [], [['variable', '/', '[^/]++', 'transition', true], ['variable', '/', '[^/]++', 'id', true], ['text', '/pull-requests']], [], [], []],
    'app_book_sortable_index' => [[], ['_controller' => 'App\\Controller\\BookController::indexAction', '_sylius' => ['sortable' => true]], [], [['text', '/sortable-books/']], [], [], []],
    'app_book_filterable_index' => [[], ['_controller' => 'app.controller.book::indexAction', '_sylius' => ['filterable' => true]], [], [['text', '/filterable-books/']], [], [], []],
    'app_comic_book_index' => [['version'], ['_controller' => 'app.controller.comic_book::indexAction', '_sylius' => ['serialization_groups' => ['Default'], 'serialization_version' => '$version', 'permission' => false]], [], [['text', '/comic-books/'], ['variable', '', '[^/]++', 'version', true], ['text', '/v']], [], [], []],
    'app_comic_book_create' => [['version'], ['_controller' => 'app.controller.comic_book::createAction', '_sylius' => ['serialization_groups' => ['Default', 'Detailed'], 'serialization_version' => '$version', 'permission' => false]], [], [['text', '/comic-books/'], ['variable', '', '[^/]++', 'version', true], ['text', '/v']], [], [], []],
    'app_comic_book_update' => [['version', 'id'], ['_controller' => 'app.controller.comic_book::updateAction', '_sylius' => ['serialization_groups' => ['Default', 'Detailed'], 'serialization_version' => '$version', 'permission' => false]], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/comic-books'], ['variable', '', '[^/]++', 'version', true], ['text', '/v']], [], [], []],
    'app_comic_book_show' => [['version', 'id'], ['_controller' => 'app.controller.comic_book::showAction', '_sylius' => ['serialization_groups' => ['Default', 'Detailed'], 'serialization_version' => '$version', 'permission' => false]], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/comic-books'], ['variable', '', '[^/]++', 'version', true], ['text', '/v']], [], [], []],
    'app_comic_book_delete' => [['version', 'id'], ['_controller' => 'app.controller.comic_book::deleteAction', '_sylius' => ['csrf_protection' => false, 'serialization_version' => '$version', 'permission' => false]], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/comic-books'], ['variable', '', '[^/]++', 'version', true], ['text', '/v']], [], [], []],
    'app_create_book_with_custom_factory' => [[], ['_controller' => 'app.controller.book::createAction', '_sylius' => ['factory' => ['method' => ['expr:service(\'test.custom_book_factory\')', 'createCustom']]]], [], [['text', '/create-custom-book']], [], [], []],
    'app_index_books_with_custom_repository' => [[], ['_controller' => 'app.controller.book::indexAction', '_sylius' => ['repository' => ['method' => ['expr:service(\'test.custom_book_repository\')', 'findCustomBooks']]]], [], [['text', '/find-custom-books']], [], [], []],
    'app_show_book_with_custom_repository' => [[], ['_controller' => 'app.controller.book::showAction', '_sylius' => ['repository' => ['method' => ['expr:service(\'test.custom_book_repository\')', 'findCustomBook'], 'arguments' => ['J.R.R. Tolkien']]]], [], [['text', '/find-custom-book']], [], [], []],
    'app_science_book_index' => [[], ['_controller' => 'app.controller.science_book::indexAction', '_sylius' => ['grid' => 'science_book_grid', 'template' => 'ScienceBook/index.html.twig', 'permission' => false]], [], [['text', '/science-books/']], [], [], []],
    'app_science_book_create' => [[], ['_controller' => 'app.controller.science_book::createAction', '_sylius' => ['template' => 'ScienceBook/create.html.twig', 'permission' => false]], [], [['text', '/science-books/new']], [], [], []],
    'app_science_book_update' => [['id'], ['_controller' => 'app.controller.science_book::updateAction', '_sylius' => ['template' => 'ScienceBook/update.html.twig', 'permission' => false]], [], [['text', '/edit'], ['variable', '/', '[^/]++', 'id', true], ['text', '/science-books']], [], [], []],
    'app_science_book_show' => [['id'], ['_controller' => 'app.controller.science_book::showAction', '_sylius' => ['template' => 'ScienceBook/show.html.twig', 'permission' => false]], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/science-books']], [], [], []],
    'app_science_book_bulk_delete' => [[], ['_controller' => 'app.controller.science_book::bulkDeleteAction', '_sylius' => ['permission' => false, 'paginate' => false, 'repository' => ['method' => 'findById', 'arguments' => ['$ids']]]], [], [['text', '/science-books/bulk-delete']], [], [], []],
    'app_science_book_delete' => [['id'], ['_controller' => 'app.controller.science_book::deleteAction', '_sylius' => ['permission' => false]], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/science-books']], [], [], []],
];