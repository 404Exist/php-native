<?php

echo <<<HTML
<form method="POST" enctype="multipart/form-data">
    <input type="text" name="amount" />
    <input type="file" name="file" />
    <input type="submit" />
</form>
<a href="/users">Users Page</a> <br />
<a href="/generators">Generators Page</a> <br />
<a href="/datetimes">Datetimes Page</a> <br />
HTML;
