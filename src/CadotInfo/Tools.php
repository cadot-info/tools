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
     * @param  array $urlTwoPoints extract before : , example mailto, htpp..., default: 'mailto', 'http', 'https'
     * @param  array $urlPoint extract before first point , example www, default 'www'
     * @param  array $classRefuse don't take link if class is in this array. It's for js for example
     * @param  array|boolean $client send a client if you have login for example
     * @param  array $links for recursivity
     * @return array of url of links
     */
    
    public function returnAllLinks(string $start, int $descent = 0, $client=false,$urlTwoPoints,$urlPoint,array $classRefuse ,array $links = []):array
    {
        //init default value
        if($urlTwoPoints==null)$urlTwoPoints=['mailto', 'http', 'https'];
        if( $urlPoint==null)$urlPoint=['www'];
        if($classRefuse==null)$classRefuse=[];


        $exlinks = $links;
        if(!$client)$client = static::createClient();
        $crawler = $client->request('GET', $start);
        //see links of the page
        foreach ($crawler->filter('a[href]') as $link) { // no get link without href
            /** @var DOMElement $link */
            $url = $link->getAttribute('href');
            // pass link exist and if has not the class, not in urlpoint and urlTwoPoints
            if (!in_array(explode(':', $url)[0], $urlTwoPoints) && (!in_array(explode('.', $url)[0],$urlPoint)) &&  !isset($exlinks[$url]) && count(array_intersect($classRefuse, explode(' ', $link->getAttribute('class'))))==0){
                if ($descent > -1) { // si on est dans une récursivité acceptée
                    $links = $this->returnAllLinks($url, $descent - 1,$client,$urlTwoPoints,$urlPoint,$classRefuse, $links);
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
