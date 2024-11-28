<?php

return [
    '' => 'EditorController@index',
    'editor/save' => 'EditorController@save',
    'login' => 'AuthController@login',
    'logout' => 'AuthController@logout',
    'register' => 'AuthController@register',
    'verify-email' => 'AuthController@verifyEmail',
    'forgot-password' => 'AuthController@forgotPassword',
    'reset-password' => 'AuthController@resetPassword',
    'image' => 'ImageController@get',
    'thumbnail' => 'ImageController@thumbnail',
    'image/delete' => 'ImageController@delete',
    'image/like' => 'ImageController@like',
    'image/comment' => 'ImageController@comment',
    'gallery' => 'GalleryController@index',
    'gallery/show' => 'GalleryController@show',
    '404' => 'ErrorController@notFound',
    '403' => 'ErrorController@forbidden',
    'profil' => 'ProfilController@index',
    'settings' => 'ProfilController@settings',
];