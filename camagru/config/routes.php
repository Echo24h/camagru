<?php

return [
    '' => 'EditorController@index',
    'user/profile' => 'UserController@profile',
    'login' => 'AuthController@login',
    'logout' => 'AuthController@logout',
    'register' => 'AuthController@register',
    'verify-email' => 'AuthController@verifyEmail',
    'image/save' => 'ImageController@save',
    'image/delete' => 'ImageController@delete',
    'image/like' => 'ImageController@like',
    'image/comment' => 'ImageController@comment',
    'gallery' => 'GalleryController@index',
    'gallery/show' => 'GalleryController@show',
    '404' => 'ErrorController@notFound',
];