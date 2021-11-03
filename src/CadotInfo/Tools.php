<?php
/*
 * Created on Wed Nov 03 2021 by The MIT License (MIT) Copyright (c) 2021 Cadot.info,licence: http://spdx.org/licenses/MIT.html webmestre@cadot.info
 *
 *
 *-------------------------------------------------------------------------- *
 *      Tools.php *
 * -------------------------------------------------------------------------- *
 *
 * Usage:
 * - composer require cadot.info/symfony-testing-tools
 * - use CadotInfo\Tools;
 * - in the class: use Tools;
 *
 * Source on: https://github.com/cadot-info/tools
 */

namespace CadotInfo;


trait Tools
{
    
    /**
     * returnAllLinks 
     *
     * @param  string $start url of strat seek
     * @param  int $descent number or sub links
     * @param  mixed $client send a client if you have login for example
     * @param  array $links for recursivity
     * @return array of url of links
     */
    public function returnAllLinks(string $start, int $descent = 0,$client=false, array $links = [])
    {
        $exlinks = $links;
        if(!$client)$client = static::createClient();
        $crawler = $client->request('GET', $start);
        //on regarde les liens de la page
        foreach ($crawler->filter('a[href]') as $link) { // on ne prend pas les liens sans href
            /** @var DOMElement $link */
            $url = $link->getAttribute('href');
            // on ne prend pas les liens déjà pris, ni les mailto ni les url extérieures, ni les bigpictures
            if (!in_array(explode(':', $url)[0], ['mailto', 'http', 'https']) && explode('.', $url)[0] != 'www' &&  !isset($exlinks[$url]) && !in_array('bigpicture', explode(' ', $link->getAttribute('class')))) {
                if ($descent > -1) { // si on est dans une récursivité acceptée
                    $links = $this->returnAllLinks($url, $descent - 1,$client, $links);
                } else {
                    $links[$url] = trim(preg_replace('/\s+/', ' ', str_replace(array("\n", "\r", ""), '', $link->nodeValue)));
                }
            }
        }
        return $links;
    }    

    /**
     * E funtion for send message immediatly
     *
     * @param  string $texte
     * @return void
     */
    public function E(string $texte):void
    {
        echo "- " . ucfirst($texte) . "\n";
        ob_flush();
    }
}
