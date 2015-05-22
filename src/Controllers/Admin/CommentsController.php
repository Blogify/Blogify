<?php namespace jorenvanhocht\Blogify\Controllers\Admin;

use jorenvanhocht\Blogify\Models\Comment;

class CommentsController extends BaseController
{

    /**
     * Holds an instance of the comment model
     *
     * @var Comment
     */
    protected $comment;

    /**
     * Construct the class
     *
     * @param Comment $comment
     */
    public function __construct(Comment $comment)
    {
        parent::__construct();

        $this->comment = $comment;
    }

    ///////////////////////////////////////////////////////////////////////////
    // View methods
    ///////////////////////////////////////////////////////////////////////////

    /**
     * Show the view with the overview
     * of comments
     *
     * @param string $revised
     * @return \Illuminate\View\View
     */
    public function index($revised = "pending")
    {
        $revised = $this->checkRevised( $revised );
        if ($revised === false) abort(404);

        $data = [
            'comments' => $this->comment->byRevised( $revised )->paginate( $this->config->items_per_page ),
            'revised' => $revised,
        ];

        return view('blogify::admin.comments.index', $data);
    }


    ///////////////////////////////////////////////////////////////////////////
    // CRUD methods
    ///////////////////////////////////////////////////////////////////////////

    /**
     * Change the status of an given comment
     *
     * @param $hash
     * @param $new_revised
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeStatus($hash, $new_revised)
    {
        $revised = $this->checkRevised( $new_revised );
        if ($revised === false) abort(404);

        $comment            = $this->comment->byHash( $hash );
        $comment->revised   = $revised;
        $comment->save();

        tracert()->log('comments', $comment->id, $this->auth_user->id, $new_revised);

        $message = trans('blogify::notify.comment_success', ['action' => $new_revised]);
        session()->flash('notify', [ 'success', $message]);

        return redirect()->route('admin.comments.index');
    }

    ///////////////////////////////////////////////////////////////////////////
    // Helper methods
    ///////////////////////////////////////////////////////////////////////////

    /**
     * Check if the given revised
     * is valid
     *
     * @param $revised
     * @return int|bool
     */
    private function checkRevised($revised)
    {
        $allowed = [1 => 'pending', Ò2 => 'approved', 3 => 'disapproved'];

        return array_search($revised, $allowed);
    }

}