@props(['user'])

<x-forms.update-user-personal :user="$user" />
<hr>
<x-forms.update-user-race-gender :user="$user" />
<hr>
<x-forms.update-user-disability :user="$user" />
<hr>
<x-forms.update-user-password :user="$user" />
