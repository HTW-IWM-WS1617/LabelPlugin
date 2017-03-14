<?php
/**
 * DokuWiki Plugin htwlabel (Admin Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Leon Todtenhausen <lt12@hotmail.de>
 */

// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

class admin_plugin_htwlabel extends DokuWiki_Admin_Plugin {
    private $hlp;

    /**
     * @return int sort number in admin menu
     */
    public function getMenuSort() {
        return 400;
    }

    /**
     * @return bool true if only access for superuser, false is for superusers and moderators
     */
    public function forAdminOnly() {
        return false;
    }

    /**
     * Should carry out any processing required by the plugin.
     */
    public function handle() {
        $this->hlp = plugin_load('helper', 'htwlabel');
        $this->hlp->getDB();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (checkSecurityToken()) {
                if (isset($_POST['action']['delete'])) {
                   $this->delLabel();
                }
                if (isset($_POST['action']['create'])) {
                    $this->create();
                } else if (isset($_POST['action']['save'])) {
                    $this->applyChanges();
                    $this->create(true);
                }
            }
        }
    }

    /**
     * Render HTML output, e.g. helpful text and a form
     */
    public function html() {
        global $ID;
        $labels = $this->hlp->getAllLabels();
        include dirname(__FILE__) . '/admin_tpl.php';    
    }


    /**
     * Try to delete a label
     */
    private function delLabel() {
        $labels = array_keys($_POST['action']['delete']);
        foreach ($labels as $label) {
            $this->hlp->deleteLabel($label);
        }
        msg($this->getLang('label deleted'));
    }

        private function applyChanges() {
        $labels = $this->hlp->getAllLabels();

        if (!isset($_POST['labels'])) return; // nothing to do

        foreach ($_POST['labels'] as $oldName => $newValues) {

            // apply color
            if ($labels[$oldName]['color'] != $newValues['color']) {
                if ($this->validateColor($newValues['color'])) {
                    $this->hlp->changeColor($oldName, $newValues['color']);
                } else {
                    msg('invalid color', -1);
                }
            }

            // apply icon
            if ($labels[$oldName]['icon'] != $newValues['icon']) {
                $this->hlp->changeicon($oldName, $newValues['icon']);
            } else if (empty($newValues['icon'])) {
                $this->hlp->changeicon($oldName, $newValues['icon']);
                $labels = $this->hlp->getAllLabels();
            }

            // apply renaming
            if ($oldName !== $newValues['name'] && !empty($newValues['name'])) {
                if ($this->validateName($newValues['name'])) {
                    $this->hlp->renameLabel($oldName, $newValues['name']);
                } else {
                    msg('name already exists');
                }
            }

            // apply Lang EN
            if ($labels[$oldName]['labelEN'] != $newValues['labelEN']) {
                $this->hlp->changeLabelEN($oldName, $newValues['labelEN']);
            }

            // apply Lang FR
            if ($labels[$oldName]['labelFR'] != $newValues['labelFR']) {
                $this->hlp->changeLabelFR($oldName, $newValues['labelFR']);
            }

            // apply Lang ES
            if ($labels[$oldName]['labelES'] != $newValues['labelES']) {
                $this->hlp->changeLabelES($oldName, $newValues['labelES']);
            }                              
        }

            // apply initial status
            if (!empty($_POST['initial'])) {
                $this->hlp->changeinitstatus($_POST['initial']);
            }        

        $this->hlp->getAllLabels(true);

    }

 /**
     * create a label using the request parameter
     */
    private function create($applyMode = false) {
        if (!isset($_POST['newlabel'])) return;

        $name = (isset($_POST['newlabel']['name']))?$_POST['newlabel']['name']:'';
        $color = (isset($_POST['newlabel']['color']))?$_POST['newlabel']['color']:'';
        $icon = (isset($_POST['newlabel']['icon']))?$_POST['newlabel']['icon']:'';
        $labelEN = (isset($_POST['newlabel']['labelEN']))?$_POST['newlabel']['labelEN']:'';
        $labelFR = (isset($_POST['newlabel']['labelFR']))?$_POST['newlabel']['labelFR']:'';
        $labelES = (isset($_POST['newlabel']['labelES']))?$_POST['newlabel']['labelES']:'';

       // if (empty($icon)) $icon = null; // maxint - last element
       // $icon = floatval($icon);

        if ($applyMode && empty($name) && empty($color)) return;

        if (!$this->validateName($name)) {
            return;
        }

        if (!$this->validateColor($color)) {
            return;
        }

        $this->hlp->createLabel($name, $color, $icon, $initial, $labelEN, $labelFR, $labelES);
       // $this->hlp->changeicon$icon($name, $icon);
        msg($this->getLang('label created'));
        $this->hlp->getAllLabels(true);
    }

 /**
     * validate if a color is correct.
     * @param string $color the color string
     * @return boolean true if the color is correct
     */
    private function validateColor($color) {
        if (!preg_match('/^#[0-9a-f]{3}([0-9a-f]{3})?$/i', $color)) {
            msg($this->getLang('invalid color', -1));
            return false;
        }
        return true;
    }

    /**
     * check if a label name is correct
     * @param string $name label name
     * @return boolean true if everything is correct
     */
    private function validateName($name) {
        if ($this->hlp->labelExists($name)) {
            msg($this->getLang('label already exists', -1));
            return false;
        }
        if (empty($name)) {
            msg($this->getLang('no name', 1));
            return false;
        }
        return true;
    }

}
// vim:ts=4:sw=4:et: