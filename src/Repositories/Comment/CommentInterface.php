<?php

namespace jorenvanhocht\Blogify\Repositories\Comment;

interface CommentInterface
{

    public function byRevised($revised);
    public function paginate($perPage, $columns, $pageName);
    public function byHash($hash);

}