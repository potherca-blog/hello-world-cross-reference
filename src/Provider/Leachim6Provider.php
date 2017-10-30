<?php

namespace Potherca\CrossReference\HelloWorld\Provider;

class Leachim6Provider extends AbstractFetcher
{
    final public function getName()
    {
        return 'leachim6/hello-world';
    }

    final public function fetch()
    {
        $client = $this->getClient();

        $file = $client->api('repo')->contents()->show('leachim6', 'hello-world', 'README.md');

        $content = html_entity_decode(base64_decode($file['content']));

        preg_match_all('@\\* \\[([^\\]]+)\\]\\([^)]+\\)@', $content, $matches);

        return $matches[1];
    }
}
