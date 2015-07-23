<?php

namespace jorenvanhocht\Blogify\Repositories\Comment;

use jorenvanhocht\Blogify\Models\Comment;

class EloquentCommentRepository implements CommentInterface
{
    /**
     * @var \jorenvanhocht\Blogify\Models\Comment
     */
    protected $comment;

    /**
     * @param \jorenvanhocht\Blogify\Models\Comment $comment
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * @param $revised
     * @return mixed
     */
    public function byRevised($revised)
    {
        return $this->comment->byRevised($revised);
    }

    /**
     * @param null $perPage
     * @param array $columns
     * @param string $pageName
     */
    public function paginate($perPage = null, $columns = array('*'), $pageName = 'page')
    {
        return $this->comment->paginate($perPage, $columns, $pageName);
    }

    /**
     * @param $hash
     * @return mixed
     */
    public function byHash($hash)
    {
        return $this->comment->byHash($hash);
    }

}