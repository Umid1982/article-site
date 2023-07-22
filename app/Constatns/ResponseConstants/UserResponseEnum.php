<?php

namespace App\Constatns\ResponseConstants;

enum UserResponseEnum: string
{
    case ARTICLE_LIST = 'Article list';
    case ERROR = "Something went wrong, check Logs!";
}
