<?php

class Lineup {

    public $fetch_url = "http://www.baseballpress.com/team-lineups/";
    
    public function getCurrentLineups($team) {
        
        $html = $this->_fetchHtml($this->fetch_url . $team);
        $parsed = strip_tags($this->_parseHtml($html));
        $ex = explode("(", $parsed);
        foreach($ex as $e) {
            $lineup[] = substr(trim(strchr($e, ".")), 2);
        }
        array_pop($lineup);
        
        return $lineup;        
    }
    
    public function getCity($team_name) {
        $teams = array("Angels" => "LAA", "D-backs" => "ARI", "Braves" => "ATL", "Orioles" => "BAL", "Red Sox" => "BOS", "Cubs" => "CHC", "White Sox" => "CWS", "Reds" => "CIN", "Indians" => "CLE", "Rockies" => "COL", "Tigers" => "DET", "Marlins" => "MIA", "Astros" => "HOU", "Royals" => "KC", "Dodgers" => "LAD", "Brewers" => "MIL", "Twins" => "MIN", "Mets" => "NYM", "Yankees" => "NYY", "Athletics" => "OAK", "Phillies" => "PHI", "Pirates" => "PIT", "Padres" => "SD", "Giants" => "SF", "Mariners" => "SEA", "Cardinals" => "STL", "Rays" => "TB", "Rangers" => "TEX", "Blue Jays" => "TOR", "Nationals" => "WSH");
        
        return $teams[$team_name];
    }
    
    public function DOMinnerHTML($element) { 
        
        $innerHTML = ""; 
        $children = $element->childNodes; 
        
        foreach ($children as $child) 
        { 
            $tmp_dom = new DOMDocument(); 
            $tmp_dom->appendChild($tmp_dom->importNode($child, true)); 
            $innerHTML.=trim($tmp_dom->saveHTML()); 
        } 
        return $innerHTML;
    }
    
    private function _parseHtml($html) {
        
        $doc = new DOMDocument();
        $doc->loadHTML($html);
        $div_elements = $doc->getElementsByTagName('div');
        
        if ($div_elements->length <> 0) {
            foreach ($div_elements as $div_element) {
                if ($div_element->getAttribute('class') == 'team-lineup highlight') {
                    $game = $this->DOMinnerHTML($div_element);
                    $doc->loadHTML($game);
                    $child_elements = $doc->getElementsByTagName('div');
                    foreach ($child_elements as $child) {
                        if ($child->getAttribute('class') == 'game-lineup') {
                            $lineup = $this->DOMinnerHTML($child);
                        }
                    }
                }
            }
        }
        
        return $lineup;
    }
    
    private function _fetchHtml($url) {
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        
        $data = curl_exec($ch);
        curl_close($ch);
        
        return $data;
    }
    
    
}
?>