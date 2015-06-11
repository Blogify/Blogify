<?php

namespace jorenvanhocht\Blogify\Controllers\Admin;

use jorenvanhocht\Blogify\Models\Comment;
use Illuminate\Contracts\Auth\Guard;
use jorenvanhocht\Tracert\Tracert;

class CommentsController extends BaseController
{

    /**
     * @var \jorenvanhocht\Blogify\Models\Comment
     */
    protected $comment;

    /**
     * @var \jorenvanhocht\Tracert\Tracert
     */
    protected $tracert;

    /**
     * @param \jorenvanhocht\Blogify\Models\Comment $comment
     * @param \Illuminate\Contracts\Auth\Guard $auth
     * @param \jorenvanhocht\Tracert\Tracert $tracert
     */
    public function __construct(
        Comment $comment,
        Guard $auth,
        Tracert $tracert
    ) {
        parent::__construct($auth);

        $this->comment = $comment;
        $this->tracert = $tracert;
    }

    ///////////////////////////////////////////////////////////////////////////
    // View methods
    ///////////////////////////////////////////////////////////////////////////

    /**
     * @param string $revised
     * @return \Illuminate\View\View
     */
    public function index($revised = "pending")
    {
        $revised = $this->checkRevised($revised);
        if ($revised === false) {
            abort(404);
        }

        $data = [
            'comments' => $this->comment
                                ->byRevised($revised)
                                ->paginate($this->config->items_per_page),
            'revised' => $revised,
        ];

        return view('blogify::admin.comments.index', $data);
    }


    ///////////////////////////////////////////////////////////////////////////
    // CRUD methods
    ///////////////////////////////////////////////////////////////////////////

    /**
     * @param string $hash
     * @param string $new_revised
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeStatus($hash, $new_revised)
    {
        $revised = $this->checkRevised($new_revised);
        if ($revised === false) {
            abort(404);
        }

        $comment = $this->comment->byHash($hash);
        $comment->revised = $revised;
        $comment->save();

        $this->tracert->log(
            'comments',
            $comment->id,
            $this->auth_user->id,
            $new_revised
        );

        $message = trans(
            'blogify::notify.comment_success',
            ['action' => $new_revised]
        );
        session()->flash('notify', ['success', $message]);

        return redirect()->route('admin.comments.index');
    }

    ///////////////////////////////////////////////////////////////////////////
    // Helper methods
    ///////////////////////////////////////////////////////////////////////////

    /**
     * Check if the given revised
     * is valid
     *
     * @param string $revised
     * @return int|bool
     */
    private function checkRevised($revised)
    {
        $allowed = [1 => 'pending', 2 => 'approved', 3 => 'disapproved'];

        return array_search($revised, $allowed);
    }

}