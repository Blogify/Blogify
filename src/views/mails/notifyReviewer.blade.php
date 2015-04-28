<h1>You are asigned to review a post</h1>

<p>
    Hello {{ $data['reviewer']->firstname }},
</p>
<p>
   You are assigned as reviewer for the " {{ $data['post']['title'] }}" article.<br>
    Please review it as soon as possible, the article is placed in your feed.
</p>
<p>
    Please <a href="{{ route('admin.login') }}" title="">sign in</a> to review the article.
</p>
<p>
    Blogify
</p>