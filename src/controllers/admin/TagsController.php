<?php namespace jorenvanhocht\Blogify\Controllers\admin;

use Input;
use jorenvanhocht\Blogify\Models\Tag;

class TagsController extends BlogifyController {

    protected $tag;
    protected $tags = [];
    protected $stored_tags = [];

    public function __construct( Tag $tag )
    {
        parent::__construct();

        $this->tag = $tag;

    }

    ///////////////////////////////////////////////////////////////////////////
    // View methods
    ///////////////////////////////////////////////////////////////////////////



    ///////////////////////////////////////////////////////////////////////////
    // CRUD methods
    ///////////////////////////////////////////////////////////////////////////

    public function store()
    {
        $this->fillTagsArray();
        $this->deleteSpacesAtTheBeginningAndEnd();
        $validation = $this->tag->validate($this->tags);

        if ( $validation->fails() ){
            $data = [
                'passed' => false,
                'messages' => $validation->messages(),
            ];
            return $data;
        }

        $this->storeOrUpdateTags();

        $data = [
            'passed' => true,
            'tags'  => $this->stored_tags
        ];
        return $data;
    }

    ///////////////////////////////////////////////////////////////////////////
    // Helper methods
    ///////////////////////////////////////////////////////////////////////////

    private function fillTagsArray()
    {
        $tags = Input::get('tags');
        $this->tags = explode(',', $tags);
    }

    private function deleteSpacesAtTheBeginningAndEnd()
    {
        foreach ( $this->tags as $key => $tag )
        {
            $this->tags[$key] = trim($tag);
        }
    }

    public function storeOrUpdateTags()
    {
        foreach ( $this->tags as $tag_name )
        {
            $t = $this->tag->whereName($tag_name)->first();

            if ( count( $t ) > 0 )
            {
                $tag = $t;
            }
            else
            {
                $tag        = new Tag;
                $tag->hash  = blogify()->makeUniqueHash('tags', 'hash');
            }

            $tag->name = $tag_name;

            $tag->save();
            array_push($this->stored_tags, $tag);
        }
    }
}