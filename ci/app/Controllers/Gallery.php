<?php

    function Hi($key)
    {
     return getPhotos('https://photos.app.goo.gl/'.$key,0, 0, 370, 300);
    }

     function getPhotos($link,  $width = 0, $height = 480, $imageWidth = 1920, $imageHeight = 1080, $expiration = 0)
    {
        // code...
        $props = create_default_props();
        $props -> link = $link;
        $props -> width = $width;
        $props -> height = $height;
        $props -> imageWidth = $imageWidth;
        $props -> imageHeight = $imageHeight;
        return get_html($props, $expiration);
    }

     function get_html($props, $expiration = 0)
    {
        if ($html = get_embed_player_html_code($props)) {
            $expiration = $expiration > 0 ? max($expiration, min_expiration) : 0;
            return $html;
        }
        return NULL;
    }

     function get_remote_contents($link)
    {
        $client = \Config\Services::curlrequest();
        $response = $client->request('GET', $link, ['allow_redirects' => true]);
        return $response->getBody();
    }

     function parse_ogtags($contents)
    {
        $m = NULL;
        preg_match_all('~<\s*meta\s+property="(og:[^"]+)"\s+content="([^"]*)~i', $contents, $m);
        $ogtags = array();
        for($i = 0; $i < count($m[1]); $i++) {
            $ogtags[$m[1][$i]] = $m[2][$i];
        }
        return $ogtags;
    }

     function parse_photos($contents)
    {
        $m = NULL;
        preg_match_all('~\"(http[^"]+)"\,[0-9^,]+\,[0-9^,]+~i', $contents, $m);
        return array_unique($m[1]);
    }

     function get_embed_player_html_code($props)
    {
        // var_dump( get_remote_contents($props -> link));
        if ($contents = get_remote_contents($props -> link)) {
            $og = parse_ogtags($contents);
            $title = isset($og['og:title']) ? $og['og:title'] : NULL;
            $photos = parse_photos($contents);
            $style = 'display:none;'
                . 'width:' . ($props -> width === 0 ? '100%' : ($props -> width . 'px')) . ';'
                . 'height:' . ($props -> height === 0 ? '100%' : ($props -> height . 'px')) . ';';
            $items_code = '';
            foreach ($photos as $photo) {
                $src = sprintf('%s=w%d-h%d', $photo, $props -> imageWidth, $props -> imageHeight);
                $boxsrc = sprintf('%s=w%d-h%d', $photo, $props -> imageWidth*2, $props -> imageHeight*2);
                $items_code .= "<div class='swiper-slide'>
                                    <div class='program-item'>
                                        <div class='lab-inner'>
                                            <div class='lab-thumb'>
                                                <a href='".$boxsrc. "' data-toggle='lightbox' data-gallery='orgGallery' data-type='image'>
                                                    <img src='".$src. "' alt=''>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>";
            }

            // return "". $items_code . "";
            return $photos;
        }
        return NULL;
    }

     function create_default_props()
    {
        $props = new \StdClass();
        $props -> mode = 'gallery-player';
        $props -> link = NULL;
        $props -> width = 0;
        $props -> height = 480;
        $props -> imageWidth = 1920;
        $props -> imageHeight = 1080;
        $props -> includeThumbnails = NULL;
        $props -> attachMetadata = NULL;
        $props -> slideshowAutoplay = NULL;
        $props -> slideshowDelay = NULL;
        $props -> slideshowRepeat = NULL;
        $props -> mediaItemsAspectRatio = NULL;
        $props -> mediaItemsEnlarge = NULL;
        $props -> mediaItemsStretch = NULL;
        $props -> mediaItemsCover = NULL;
        $props -> backgroundColor = NULL; // Default is '#000000';
        $props -> expiration = 0;
        return $props;
    }