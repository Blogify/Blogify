<h1>Blogify password</h1>
<p>
    Hello {{ $data['user']->firstname }},
</p>
<p>
    An account for you has been created. You can sign in with your e-mail adress {{ $data['user']->email }}
    and the generated password <strong>{{ $data['password'] }}</strong>, pleas make sure to change this
    password when you first login in our application.
</p>
<p>
    Blogify
</p>