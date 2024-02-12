<?php
namespace App\Consts;

class ProjectConst
{
	public const DISP_PER_PAGE = 20;
    public const POST_STATE_NULL = null;
    public const POST_STATE_OPEN = 1;
    public const POST_STATE_NOT_OPEN = 0;
    public const HEADER_FLG_ONE = 1;
    public const HEADER_FLG_ZERO = 0;   

    public static function func()
    {
        return self::DISP_PER_PAGE;
    }
}