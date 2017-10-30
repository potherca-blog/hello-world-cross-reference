<?php

namespace Potherca\CrossReference\HelloWorld\Provider;

class Knightking100Provider extends AbstractFetcher
{
    final public function getName()
    {
        return 'knightking100/hello-worlds';
    }

    final public function fetch()
    {
        $client = $this->getClient();

        $file = $client->api('repo')->contents()->show('knightking100', 'hello-worlds', 'LANGUAGES.md');

        $content = html_entity_decode(base64_decode($file['content']));

        preg_match_all('@- \\[x\\] \\[([^\\]]+)\\]\\([^)]+\\)@', $content, $matches);

        return $matches[1];
    }
}
