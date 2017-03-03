<?php

if(!defined('DOKU_INC')) die();

class helper_plugin_htwlabel extends DokuWiki_Plugin {

    private $labels = null;
    private $lang_translation = null;

    function __construct() {
        global $conf;
        if (isset($conf['lang'])) {
            $path = DOKU_INC.'/lang/'.$conf['lang'].'/htwlabel';
            if (file_exists($path)) {
                @include($path);
                $this->lang_translation = $lang;
            }
        }
    }

    function getDB() {
        static $db;
        if (!is_null($db)) {
            return $db;
        }

        $db = plugin_load('helper', 'sqlite');
        if (is_null($db)) {
            msg('The labeled plugin needs the sqlite plugin', -1);
            return false;
        }
        if($db->init('htwlabel', dirname(__FILE__).'/db/')){
       // if($db->init('htwlabel',DOKU_PLUGIN.'htwlabel/db/')){            
            return $db;
        }
        //msg('DB not found', 1);
        return false;
    }

    public function tpl_labels() {
        global $ID;
        $all = $this->getAllLabels();
        if (count($all) === 0) return false;
        $current = $this->getLabels($ID);
        $result = '';
        $result .=  '<div class="plugin_labeled"><ul>';

        $edit = auth_quickaclcheck($ID) >= AUTH_EDIT;
        foreach ($all as $label => $opts) {

            $active = in_array($label, $current);

            $color = ($active)?$opts['color']:'aaa';
            $icon = $opts['icon'];

            $result .=  '<li class="labeled_'.($active?'':'in').'active" style="border-color:'.$color.';background-color:'.$color.'">';
            if ($edit) {
                $link = wl($ID,
                    array(
                        'do' => 'htwlabel',
                        action_plugin_htwlabel_change::$act => $active?'remove':'add',
                        'label' => $label
                    )
                );
                $title = '';
                $result .= sprintf('<a href="%s" title="%s">', $link, $title);
            }
            $result .=  hsc((isset($this->lang_translation[$label])) ? $this->lang_translation[$label] : $label);
            $result .=  ' <i class="fa '.$icon.'"></i>';

            if ($edit) $result .=  '</a>';
            $result .=  '</li>';
        }

        $result .=  '</ul></div>';
        return $result;
    }

    /**
     * parse a string of tags.
     * @param string $tags
     * @return array single tags as array.
     * FIXME can be deleted?
     */
    public function parseLabels($labels) {
        $labels = explode(',', $labels);
        foreach ($lables as &$label) {
            $label = trim($label);
        }
        return $labels;
    }

    public function setLabels($labels, $id) {
        if (auth_quickaclcheck($id) < AUTH_EDIT) {
            return false;
        }

        $this->deleteLabels($id);

        $db = $this->getDb();
        foreach ($labels as $label) {
            if (!$this->labelExists($label)) continue;
            $db->query('INSERT INTO htwlabel (id, label) VALUES (?,?)', $id, $label);
        }
    }

    public function changeColor($label, $newColor) {
        global $INFO;
        if (!$INFO['isadmin']) return;

        $db = $this->getDb();
        $db->query('UPDATE htwlabels SET color=? WHERE name=?', $newColor, $label);
    }

    /**
     * remove a label form a page
     * @param string $label label to remove
     * @param string $id    wiki page id
     */
    public function removeLabel($label, $id) {
        if (auth_quickaclcheck($id) < AUTH_EDIT) {
            return false;
        }
        $db = $this->getDb();
        $db->query('DELETE FROM htwlabel WHERE id=? AND label=?', $id, $label);
        addLogEntry(time(true) ,$id, 'e', 'removed label '.$label );
        $this -> createStatusOverview();
    }

    /**
     * delete all labels from a wikipage
     * @param string $id the wikipage
     * @return void
     */
    private function deleteLabels($id) {
        if (auth_quickaclcheck($id) < AUTH_EDIT) {
            return false;
        }

        $db = $this->getDb();
        $db->query('DELETE FROM htwlabel WHERE id=?', $id);
    }

    /**
     * add a single label to a wiki page
     * @param string $label
     * @param string $id wiki page
     * @return void
     */
    public function addLabel($label, $id) {
        $labels = $this->getLabels($id);
        $labels[] = $label;
        $labels = array_unique($labels);

        $this->setLabels($labels, $id);
        addLogEntry(time(true) ,$id, 'e', 'added label '.$label );
        $this -> createStatusOverview();
    }


    /**
     * check if a page has assigned a label
     * @param string $label
     * @param string $id wiki page
     * @return void
     */
    
    public function checkPage($id) {
        $db = $this->getDb();
        $res = $db->query('SELECT label FROM htwlabel WHERE id=?', $id);
        $activeLabel = $db->res2arr($res);
        if($activeLabel){
          return true;
        }else false;

    }   

    /**
     * rename a label
     * @param string $label old label name
     * @param string $newLabel new label name
     */
    public function renameLabel($label, $newName) {
        global $INFO;
        if (!$INFO['isadmin']) return;

        if (!$this->labelExists($label)) return;
        $db = $this->getDb();
        $db->query('UPDATE htwlabels set name=? WHERE name=?', $newName, $label);
        $db->query('UPDATE htwlabel set label=? WHERE id=?', $newName, $label);

    }

        public function changeicon($label, $newicon) {
        global $INFO;
        if (!$INFO['isadmin']) return;

        if (!$this->labelExists($label)) return;
        $db = $this->getDb();
        $db->query('UPDATE htwlabels set icon=? WHERE name=?', $newicon, $label);

    }

    /**
     * get all labels
     * @param string $id from wiki page id
     * @return array list of all labels
     */
    public function getLabels($id) {
        if (auth_quickaclcheck($id) < AUTH_READ) {
            return false;
        }

        $db = $this->getDb();
        $res = $db->query('SELECT label FROM htwlabel WHERE id=?', $id);

        $labels = $db->res2arr($res);
        $result = array();
        foreach ($labels as $label) {
            $result[] = $label['label'];
        }
        return $result;
    }

    /**
     * get the active label of a page 
     * @param string $id from wiki page id
     * @return the active label as a string
     */
    public function getActiveLabel($id) {
        if (auth_quickaclcheck($id) < AUTH_READ) {
            return false;
        }

        $db = $this->getDb();
        $res = $db->query('SELECT label FROM htwlabel WHERE id=?', $id);

        $labels = $db->res2arr($res);
        $result = '';
        foreach ($labels as $label) {
            $result = $label['label'];
        }
        return $result;
    }

    /**
     * check if a label exists
     * @param string $label label to check
     * @return boolean true if exists
     */
    public function labelExists($label) {
        $labels = $this->getAllLabels();
        return isset($labels[$label]);
    }

    /**
     * @return array get an array of all available labels
     * @param boolean $reload on true force a reload
     */
    public function getAllLabels($reload = false) {
        if ($this->labels !== null && !$reload) return $this->labels;

        $db = $this->getDb();
        if ($db !== false) {
            $res = $db->query('SELECT name, color, icon FROM htwlabels ORDER BY name');

            $labels = $db->res2arr($res);

            $this->labels = array();
            foreach ($labels as $label) {
                $this->labels[$label['name']] = $label;
            }

            return $this->labels;
        }else{ msg('Es gibt keine Tabelle vorhanden', -1); }
    }

    /**
     * Change the order of the labels - Irrelevant for HTWLabel
     *
     * @param string $name label name to change
     * @param float $order ordering number
     */
    public function changeOrder($name, $order) {

    }

    //  Irrelevant for HTWLabel         
    public function cmpOrder($a, $b) {

    }

    /**
     * create a new label
     * @param string $name   new name of the label
     * @param string $color  hex color of the label
     * @param boolean $order Ordering number
     * @param string $ns     namespace filter for the label
     */
    public function createLabel($name, $color, $icon) {
        global $INFO;
        if (!$INFO['isadmin']) return;

        if ($this->labelExists($name)) return;

        //$ns = cleanID($ns);
        $db = $this->getDb();
        $db->query('INSERT INTO htwlabels (name, color, icon) VALUES (?,?,?)', $name, $color, $icon);
    }

    /**
     * delete a label (and all uses of it)
     * @param string $label label to delete
     */
    public function deleteLabel($label) {
        global $INFO;
        if (!$INFO['isadmin']) return;

        $db = $this->getDb();
        $db->query('DELETE FROM htwlabels WHERE name=?', $label);
        $db->query('DELETE FROM htwlabel WHERE id=?', $label);
    }


    /**
     * remove all labels from a page
     * @param string $label label to delete
     */
    public function deleteAllLabel($id) {       
        $db = $this->getDb();
        $db->query('DELETE FROM htwlabel WHERE id=?', $id);
    }   

    public function createStatusOverview(){
        $base = 'data/pages';
        $input = '====== Status Overview ====== '."\n";
        $lvl = 1;
        $this -> searchFiles($base, $input, $lvl);
        saveWikiText('overview', $input, '');
    }

    public function searchFiles($base, &$input, $lvl)
    {
        $directory = substr($base, 11);
        $ns = utf8_encodeFN(str_replace(':','/',$directory));

        $data = array();
        search($data,$base,'search_index',array(), '', $lvl);
        foreach ($data as $file) {
            for ($x = 1; $x < $file['level'] ; $x++) {
                $input .= '  ';
            } 
            if ($file['type'] == 'd'){
                $input .= '  * '.$file['id'] ." \n";
                $new = $base . '/' . $file['id'];
                $this -> searchFiles($new, $input, $lvl + 1);
            }
            elseif ($file['type'] == 'f'){
                if (empty($ns)){
                    $id = $file['id'];
                    $input .= '  * [['.$file['id'].']]  (**'.$this->getActiveLabel($id).'**)'."\n";
                }
                else {
                    $id = $ns . ':' . $file['id'];
                    $input .= '  * [['.$id.']]  (**'.$this->getActiveLabel($id).'**)'."\n";
                }
            }
        }
    }

}