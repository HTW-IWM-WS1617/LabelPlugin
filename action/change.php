<?php

if(!defined('DOKU_INC')) die();
class action_plugin_htwlabel_change extends DokuWiki_Action_Plugin {

    var $hlp;

    public static $act = 'htwlabel_do';

    /**
     * Register handlers
     */
    function register(Doku_Event_Handler $controller) {
        $controller->register_hook('ACTION_ACT_PREPROCESS', 'BEFORE', $this, 'change_action');
    }

    function change_action(&$event, $param) {
        if ($event->data !== 'htwlabel') {
            return;
        }
        $event->preventDefault();
        $this->hlp = plugin_load('helper', 'htwlabel');
        if (is_null($this->hlp)) {
            msg('HTW Label plugin corrupted, please reinstall', -1);
            return false;
        }
        $this->handle();
        global $ACT;
        $ACT = 'show';
    }

    /**
     * Handle label change actions.
     */
    private function handle() {
        global $ID;
        $this->hlp->getDb();

        if (!isset($_REQUEST[action_plugin_htwlabel_change::$act])) return;
        $do = $_REQUEST[action_plugin_htwlabel_change::$act];
        switch ($do) {
            case 'set': /* set all string of many labels separated by , */
                $this->_set();break;
            case 'add': /* add a label to a page*/
                $this->_add();break;
            case 'remove': /* delete a label from a page*/
                $this->_remove();break;
            case 'create': /* create a new label */
                $this->_create();break;
            case 'delete': /* delete a label */
                $this->_delete();break;

        }
    }

    private function _delete() {
        if (!isset($_REQUEST['label'])) return;
        global $ID;
        $this->hlp->deleteLabel($_REQUEST['label'], $ID);
    }

    private function _remove() {
        if (!isset($_REQUEST['label'])) return;
        global $ID;
        $this->hlp->removeLabel($_REQUEST['label'], $ID);
    }

    private function _add() {
        if (!isset($_REQUEST['label'])) return;
        $label = $_REQUEST['label'];
        global $ID;

        $this->hlp->addLabel($label, $ID);

    }

    private function _set() {
        if (!isset($_REQUEST['labels'])) return;
        global $ID;

        $labels = $this->hlp->parseLabels($_REQUEST['labels']);
        $this->hlp->setLabels($labels, $ID);
    }

    private function _create() {
        foreach (array('label', 'color') as $attr) {
            if (!isset($_REQUEST[$attr])) return;
            $$attr = $_REQUEST[$attr];
        }
        //$ns = '';
        //if (isset($_REQUEST['ns'])) $ns = $_REQUEST['ns'];

        $icon = $_REQUEST['icon'];

        $this->hlp->createLabel($label, $color, $icon);
    }



}
