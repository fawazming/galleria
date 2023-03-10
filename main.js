function preg_match_all(regex, str) {
  return [...str.matchAll(new RegExp(regex, 'g'))].reduce((acc, group) => {
    group.filter((element) => typeof element === 'string').forEach((element, i) => {
      if (!acc[i]) acc[i] = [];
      acc[i].push(element);
    });

    return acc;
  }, []);
}

function get_html(props, expiration = 0)
{
    if (html = get_embed_player_html_code(props)) {
        expiration = expiration > 0 ? Math.max(expiration, min_expiration) : 0;
        return html;
    }
    return NULL;
}

     function parse_ogtags(contents)
    {
        let m = [];
        m = preg_match_all('~<\s*meta\s+property="(og:[^"]+)"\s+content="([^"]*)~i', contents);
        let ogtags = [];
		
        for(let i = 0; i < m[1].length; i++) {
        //    $ogtags[$m[1][$i]] = $m[2][$i];
		console.log('ogtag')
        }
		
        return ogtags;
    }

function get_remote_contents($link)
    {
        fetch($link).then(dt=>dt.json()).then((res)=>{
            console.log(res)
        })
        //return response/body;
    }