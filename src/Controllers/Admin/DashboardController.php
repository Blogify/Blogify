<?php namespace jorenvanhocht\Blogify\Controllers\Admin;

use App\User;
use jorenvanhocht\Blogify\Models\Comment;
use jorenvanhocht\Blogify\Models\Post;
use jorenvanhocht\Tracert\Models\History;

class DashboardController extends BaseController {

    /**
     * Holds an instance of the user model
     *
     * @var User
     */
    protected $user;

    /**
     * @var History
     */
    protected $history;

    /**
     * @var Post
     */
    protected $post;

    /**
     * @var Comment
     */
    protected $comment;

    /**
     * Holds the data for the dashboard
     *
     * @var array
     */
    protected $data = [];

    /**
     * @param User $user
     * @param History $history
     * @param Post $post
     * @param Comment $comment
     */
    public function __construct( User $user, History $history, Post $post, Comment $comment )
    {
        parent::__construct();

        $this->user = $user;
        $this->history = $history;
        $this->post = $post;
        $this->comment = $comment;

        /*if ($this->auth_user->role->name == 'Admin') $this->buildDataObjectForAdmin();
        if ($this->auth_user->role->name == 'Author') $this->buildDataObjectForAuthor();
        if ($this->auth_user->role->name == 'Reviewer') $this->buildDataObjectForReviewer();*/
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
    private function buildDataObjectForAdmin()
    {
        $this->data['new_users_since_last_visit'] = $this->user->newUsersSince( $this->auth_user->updated_at )->count();

        $this->data['activity'] = $this->history->where('crud_action', '<>', 'login')
                                                    ->where('crud_action', '<>', 'logout')
                                                    ->orderBy('updated_at', 'DESC')
                                                    ->paginate($this->config->items_per_page);

        $this->data['pending_comments'] = $this->comment->byRevised(1)->count();

        $this->data['published_posts'] = $this->post->where('publish_date', '<=', date('Y-m-d H:i:s'))->count();

        $this->data['pending_review_posts'] = $this->post->whereStatusId(2)->count();

        objectify($this->data);
    }

    /**
     * @return void
     */
    private function buildDataObjectForAuthor()
    {
        $this->data['published_posts'] = $this->post->where('publish_date', '<=', date('Y-m-d H:i:s'))->forAuthor()->count();

        $this->data['pending_review_posts'] = $this->post->whereStatusId(2)->forAuthor()->count();

        $post_ids = $this->post->forAuthor()->lists('id');
        $this->data['pending_comments'] = $this->comment->byRevised(1)->whereIn('post_id', $post_ids)->count();

        objectify($this->data);
    }

    /**
     * @return void
     */
    private function buildDataObjectForReviewer()
    {
        $this->data['pending_review_posts'] = $this->post->whereStatusId(2)->forReviewer()->count();
    }

}