<?php

namespace App\Constatns\ResponseConstants;

enum UserResponseEnum: string
{
    case ARTICLE_LIST = 'Article list';
    case ARTICLE_SHOW = 'Article show';
    case ARTICLE_CREATE = 'Article create';
    case ARTICLE_UPDATE = 'Article update';
    case ARTICLE_DELETE = 'Article delete';
    case ERROR = "Something went wrong, check Logs!";
}
