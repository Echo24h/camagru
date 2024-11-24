<?php

return [
    '' => 'EditorController@index',
    'login' => 'AuthController@login',
    'logout' => 'AuthController@logout',
    'register' => 'AuthController@register',
    'verify-email' => 'AuthController@verifyEmail',
    'forgot-password' => 'AuthController@forgotPassword',
    'reset-password' => 'AuthController@resetPassword',
    'image/save' => 'ImageController@save',
    'image/delete' => 'ImageController@delete',
    'image/like' => 'ImageController@like',
    'image/comment' => 'ImageController@comment',
    'gallery' => 'GalleryController@index',
    'gallery/show' => 'GalleryController@show',
    '404' => 'ErrorController@notFound',
    'profil' => 'ProfilController@index',
    'settings' => 'ProfilController@settings',
];