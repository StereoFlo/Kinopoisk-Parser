Kinopoisk-Parser
================

It's a php class for parse movie info from the site kinopoisk.ru

How to use:

$parce = new parser('you_login', 'you_password');
$parce->get_id($get_film);  // $get_film can be an url or an id (integer)
Get the converted to UTF8 result: $encode_result = $parce->encode_result('mb', $get_id);
Parse of result by rules: $parce->parse($rules, $encode_result)

Rules an array example:

$rules = array(
    'name' =>'#<h1 class='moviename-big' itemprop='name'>(.*?)</h1>#si',
 );
