<?php

namespace Potherca\CrossReference\HelloWorld\Provider;

class HelloworldcollectionProvider extends AbstractFetcher
{
    final public function getName()
    {
        return 'helloworldcollection/helloworldcollection.github.io';
    }

    final public function fetch()
    {
        $client = $this->getClient();

        $file = $client->api('repo')->contents()->show('helloworldcollection', 'helloworldcollection.github.io', 'index.htm');

        $content = html_entity_decode(base64_decode($file['content']));

        preg_match_all('@<a name="([^"]+)"></a>(?!<)@', $content, $matches);

        array_shift($matches[1]);

        $languages = $matches[1];

        return $languages;
    }
}
