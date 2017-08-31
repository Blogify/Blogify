<?php

namespace Donatix\Blogify\Controllers\Admin;

use App\User;
use Donatix\Blogify\Models\Comment;
use Donatix\Blogify\Models\Post;
use Donatix\Tracert\Models\History;
use Illuminate\Contracts\Auth\Guard;
use jorenvanhocht\Tracert\Tracert;

class DashboardController extends BaseController
{

    /**
     * @var \App\User
     */
    protected $user;

    /**
     * @var \Donatix\Tracert\Models\History
     */
    protected $history;

    /**
     * @var \Donatix\Blogify\Models\Post
     */
    protected $post;

    /**
     * @var \Donatix\Blogify\Models\Comment
     */
    protected $comment;

    /**
     * Holds the data for the dashboard
     *
     * @var array
     */
    protected $data = [];

    /**
     * @var \Donatix\Tracert\Tracert
     */
    protected $tracert;

    /**
     * @param \App\User $user
     * @param \Donatix\Tracert\Models\History $history
     * @param \Donatix\Blogify\Models\Post $post
     * @param \Donatix\Blogify\Models\Comment $comment
     * @param \Illuminate\Contracts\Auth\Guard $auth
     * @param \Donatix\Tracert\Tracert $tracert
     */
    public function __construct(
        User $user,
        History $history,
        Post $post,
        Comment $comment,
        Guard $auth,
        Tracert $tracert
    ) {
        parent::__construct($auth);

        $this->user = $user;
        $this->history = $history;
        $this->post = $post;
        $this->comment = $comment;
        $this->tracert = $tracert;

        if ($this->auth_user) {
            $this->{"buildDataArrayFor".$this->auth_user->role->name}();
        }
    }

    ///////////////////////////////////////////////////////////////////////////
    // View methods
    ///////////////////////////////////////////////////////////////////////////

    /**
     * Show the dashboard view
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view("blogify::admin.home", $this->data);
    }

    ///////////////////////////////////////////////////////////////////////////
    // Helper methods
    ///////////////////////////////////////////////////////////////////////////

    /**
     * @return void
     */
    private function buildDataArrayForAdmin()
    {
        $this->data['new_users_since_last_visit'] = $this->user->newUsersSince($this->auth_user->updated_at)->count();

        $this->data['activity'] = $this->history->where('crud_action', '<>', 'login')
                                                    ->where('crud_action', '<>', 'logout')
                                                    ->orderBy('updated_at', 'DESC')
                                                    ->paginate($this->config->items_per_page);

        $this->data['pending_comments'] = $this->comment->byRevised(1)->count();

        $this->data['published_posts'] = $this->post->where('publish_date', '<=', date('Y-m-d H:i:s'))->count();

        $this->data['pending_review_posts'] = $this->post->whereStatusId(2)->count();
    }

    /**
     * @return void
     */
    private function buildDataArrayForAuthor()
    {
        $this->data['published_posts'] = $this->post->where('publish_date', '<=', date('Y-m-d H:i:s'))
                                            ->forAuthor()
                                            ->count();

        $this->data['pending_review_posts'] = $this->post->whereStatusId(2)->forAuthor()->count();

        $post_ids = $this->post->forAuthor()->lists('id');
        $this->data['pending_comments'] = $this->comment->byRevised(1)->whereIn('post_id', $post_ids)->count();
    }

    /**
     * @return void
     */
    private function buildDataArrayForReviewer()
    {
        $this->data['pending_review_posts'] = $this->post->whereStatusId(2)->forReviewer()->count();
    }

}
