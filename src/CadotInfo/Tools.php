<?php

namespace CadotInfo;


trait Tools
{

    public function returnAllLinks($start, $descent = 0, $links = [])
    {
        $exlinks = $links;
        $client = static::createClient();
        $crawler = $client->request('GET', $start);
        //on regarde les liens de la page
        foreach ($crawler->filter('a[href]') as $link) { // on ne prend pas les liens sans href
            /** @var DOMElement $link */
            $url = $link->getAttribute('href');
            // on ne prend pas les liens déjà pris, ni les mailto ni les url extérieures, ni les bigpictures
            if (!in_array(explode(':', $url)[0], ['mailto', 'http', 'https']) && explode('.', $url)[0] != 'www' &&  !isset($exlinks[$url]) && !in_array('bigpicture', explode(' ', $link->getAttribute('class')))) {
                if ($descent > -1) { // si on est dans une récursivité acceptée
                    $links = $this->returnAllLinks($url, $descent - 1, $links);
                } else {
                    $links[$url] = trim(preg_replace('/\s+/', ' ', str_replace(array("\n", "\r", ""), '', $link->nodeValue)));
                }
            }
        }
        return $links;
    }
    public function E($texte)
    {
        echo "- " . ucfirst($texte) . "\n";
        ob_flush();
    }
}
