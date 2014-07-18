Kinopoisk-Parser
================

Parser php class for parse the film information

Use:

Start the class: $parce = new parser('you_login', 'you_password');
Parse a film id: $parce->get_id($get_film);  // $get_film can be an url or an id
Get the converted to UTF8 result: $encode_result = $parce->encode_result('mb', $get_id);
Parce result from a rules: $parce->parse($rules, $encode_result)

Rules array example:

$rules = array(
    'name' =>'#<h1 class='moviename-big' itemprop='name'>(.*?)</h1>#si',
 );
 
This array for search the film name