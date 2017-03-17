# htwlabel Plugin for DokuWiki

Plug in to create and manage labels for dokuwiki. Based on [labeled plug in](https://www.dokuwiki.org/plugin:labeled).

All documentation for this plugin can be found at
http://localhost/dokuwiki/doku.php?

If you install this plugin manually, make sure it is installed in
lib/plugins/htwlabel/ - if the folder is called different it
will not work!

To display the labels, add this code directly into your template's main.php:

```php
<?php
    //show labels only if user has access to page. Works on wikis with disabled acl too.
    if (auth_quickaclcheck(getID()) >= AUTH_READ) {
        //check if sqlite plugin exists, htwlabel plugin requires it.
        if (!plugin_isdisabled('sqlite')) {
            //HTW label
            $htwlabel = plugin_load('helper','htwlabel');
            if($htwlabel) echo $htwlabel->tpl_labels();
        }
    }
?>
```

Please refer to http://www.dokuwiki.org/plugins for additional info
on how to install plugins in DokuWiki.

----
Copyright (C) Grecia Graterol, Katharina Krause, Anna Zagorski, Leon Todtenhausen, Ricky Thiermann <htw_label@fraunhofer.de>

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; version 2 of the License

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

See the COPYING file in your DokuWiki folder for details
