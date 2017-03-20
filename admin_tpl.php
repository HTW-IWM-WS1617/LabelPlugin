<h1><?php echo $this->getLang('admin headline')?></h1>

<form action="<?php echo script()?>" method="post">
    <div class="no">
        <input type="hidden" name="do" value="admin" />
        <input type="hidden" name="page" value="htwlabel" />
        <input type="hidden" name="id" value="<?php echo hsc($ID)?>" />
        <input type="hidden" name="sectok" value="<?php echo hsc(getSecurityToken())?>" />
    </div>

<table class="inline">
    <tr>
        <th><?php echo $this->getLang('admin label name')?></th>
        <th><?php echo $this->getLang('admin label color')?></th>
        <th><?php echo $this->getLang('admin icon')?></th>
        <th><?php echo $this->getLang('admin initial')?></th>
        <th><?php echo $this->getLang('admin labelEN')?></th>
        <th><?php echo $this->getLang('admin labelFR')?></th>
        <th><?php echo $this->getLang('admin labelES')?></th>
        <th><?php echo $this->getLang('admin action')?></th>
    </tr>
    <tr>
        <td><input type="text" name="newlabel[name]" class="edit" size="10"/></td>
        <td><input type="color" name="newlabel[color]" class="edit"/></td>
        <td>
            <input type="radio" name="newlabel[icon]" value="fa-file-o" checked><i class="fa fa-file-o" aria-hidden="true"></i>
            <input type="radio" name="newlabel[icon]" value="fa-wrench"><i class="fa fa-wrench" aria-hidden="true"></i>
            <input type="radio" name="newlabel[icon]" value="fa-check"><i class="fa fa-check" aria-hidden="true"></i><br>
            <input type="radio" name="newlabel[icon]" value="fa-pencil" ><i class="fa fa-pencil" aria-hidden="true"></i>
            <input type="radio" name="newlabel[icon]" value="fa-file-text-o" ><i class="fa fa-file-text-o" aria-hidden="true"></i>
            <input type="radio" name="newlabel[icon]" value="fa-paper-plane-o" ><i class="fa fa-paper-plane-o" aria-hidden="true"></i>
        </td>
        <td><input type="radio" name="newlabel[initial]" value="initial" disabled /></td>
        <td><input type="text" name="newlabel[labelEN]" class="edit" size="10" /></td>
        <td><input type="text" name="newlabel[labelFR]" class="edit" size="10" /></td>
        <td><input type="text" name="newlabel[labelES]" class="edit" size="10" /></td>
        <td><input type="submit" class="button" name="action[create]" value="<?php echo $this->getLang('admin create')?>" /></td>
    </tr>
<?php foreach ($labels as $label => $opts): ?>
<?php $label = hsc($label); ?>
    <tr>
        <td>
            <input class="edit" type="text" value="<?php echo $label ?>" name="labels[<?php echo $label ?>][name]"  size="10"/>
        </td>
        <td>
            <input class="edit" style="color: <?php echo $opts['color'] ?>" type="color"
                value="<?php echo $opts['color'] ?>" name="labels[<?php echo $label ?>][color]" />
        </td>
        <td>
            <!--<input class="edit" type="text" value="<?php echo $opts['icon'] ?>" name="labels[<?php echo $label ?>][icon]" />-->
        <?php if ($opts['icon'] == "fa-file-o"): ?>
            <input class="edit" type="radio" name="labels[<?php echo $label ?>][icon]" value="fa-file-o" checked><i class="fa fa-file-o" aria-hidden="true"></i>
            <input class="edit" type="radio" name="labels[<?php echo $label ?>][icon]" value="fa-wrench"><i class="fa fa-wrench" aria-hidden="true"></i>
            <input class="edit" type="radio" name="labels[<?php echo $label ?>][icon]" value="fa-check"><i class="fa fa-check" aria-hidden="true"></i><br>
            <input class="edit" type="radio" name="labels[<?php echo $label ?>][icon]" value="fa-pencil"><i class="fa fa-pencil" aria-hidden="true"></i>
            <input class="edit" type="radio" name="labels[<?php echo $label ?>][icon]" value="fa-file-text-o"><i class="fa fa-file-text-o" aria-hidden="true"></i>
            <input class="edit" type="radio" name="labels[<?php echo $label ?>][icon]" value="fa-paper-plane-o"><i class="fa fa-paper-plane-o" aria-hidden="true"></i>
        <?php elseif ($opts['icon'] == "fa-wrench"): ?>
            <input class="edit" type="radio" name="labels[<?php echo $label ?>][icon]" value="fa-file-o"><i class="fa fa-file-o" aria-hidden="true"></i>
            <input class="edit" type="radio" name="labels[<?php echo $label ?>][icon]" value="fa-wrench" checked><i class="fa fa-wrench" aria-hidden="true"></i>
            <input class="edit" type="radio" name="labels[<?php echo $label ?>][icon]" value="fa-check"><i class="fa fa-check" aria-hidden="true"></i><br>
            <input class="edit" type="radio" name="labels[<?php echo $label ?>][icon]" value="fa-pencil"><i class="fa fa-pencil" aria-hidden="true"></i>
            <input class="edit" type="radio" name="labels[<?php echo $label ?>][icon]" value="fa-file-text-o"><i class="fa fa-file-text-o" aria-hidden="true"></i>
            <input class="edit" type="radio" name="labels[<?php echo $label ?>][icon]" value="fa-paper-plane-o"><i class="fa fa-paper-plane-o" aria-hidden="true"></i>
        <?php elseif ($opts['icon'] == "fa-check"): ?>
            <input class="edit" type="radio" name="labels[<?php echo $label ?>][icon]" value="fa-file-o"><i class="fa fa-file-o" aria-hidden="true"></i>
            <input class="edit" type="radio" name="labels[<?php echo $label ?>][icon]" value="fa-wrench"><i class="fa fa-wrench" aria-hidden="true"></i>
            <input class="edit" type="radio" name="labels[<?php echo $label ?>][icon]" value="fa-check" checked><i class="fa fa-check" aria-hidden="true"></i><br>
            <input class="edit" type="radio" name="labels[<?php echo $label ?>][icon]" value="fa-pencil"><i class="fa fa-pencil" aria-hidden="true"></i>
            <input class="edit" type="radio" name="labels[<?php echo $label ?>][icon]" value="fa-file-text-o"><i class="fa fa-file-text-o" aria-hidden="true"></i>
            <input class="edit" type="radio" name="labels[<?php echo $label ?>][icon]" value="fa-paper-plane-o"><i class="fa fa-paper-plane-o" aria-hidden="true"></i>
        <?php elseif ($opts['icon'] == "fa-pencil"): ?>
            <input class="edit" type="radio" name="labels[<?php echo $label ?>][icon]" value="fa-file-o"><i class="fa fa-file-o" aria-hidden="true"></i>
            <input class="edit" type="radio" name="labels[<?php echo $label ?>][icon]" value="fa-wrench"><i class="fa fa-wrench" aria-hidden="true"></i>
            <input class="edit" type="radio" name="labels[<?php echo $label ?>][icon]" value="fa-check" ><i class="fa fa-check" aria-hidden="true"></i><br>
            <input class="edit" type="radio" name="labels[<?php echo $label ?>][icon]" value="fa-pencil" checked><i class="fa fa-pencil" aria-hidden="true"></i>
            <input class="edit" type="radio" name="labels[<?php echo $label ?>][icon]" value="fa-file-text-o"><i class="fa fa-file-text-o" aria-hidden="true"></i>
            <input class="edit" type="radio" name="labels[<?php echo $label ?>][icon]" value="fa-paper-plane-o"><i class="fa fa-paper-plane-o" aria-hidden="true"></i>  
        <?php elseif ($opts['icon'] == "fa-file-text-o"): ?>
            <input class="edit" type="radio" name="labels[<?php echo $label ?>][icon]" value="fa-file-o"><i class="fa fa-file-o" aria-hidden="true"></i>
            <input class="edit" type="radio" name="labels[<?php echo $label ?>][icon]" value="fa-wrench"><i class="fa fa-wrench" aria-hidden="true"></i>
            <input class="edit" type="radio" name="labels[<?php echo $label ?>][icon]" value="fa-check" ><i class="fa fa-check" aria-hidden="true"></i><br>
            <input class="edit" type="radio" name="labels[<?php echo $label ?>][icon]" value="fa-pencil"><i class="fa fa-pencil" aria-hidden="true"></i>
            <input class="edit" type="radio" name="labels[<?php echo $label ?>][icon]" value="fa-file-text-o" checked><i class="fa fa-file-text-o" aria-hidden="true"></i>
            <input class="edit" type="radio" name="labels[<?php echo $label ?>][icon]" value="fa-paper-plane-o"><i class="fa fa-paper-plane-o" aria-hidden="true"></i>  
        <?php else: ?>
            <input class="edit" type="radio" name="labels[<?php echo $label ?>][icon]" value="fa-file-o"><i class="fa fa-file-o" aria-hidden="true"></i>
            <input class="edit" type="radio" name="labels[<?php echo $label ?>][icon]" value="fa-wrench"><i class="fa fa-wrench" aria-hidden="true"></i>
            <input class="edit" type="radio" name="labels[<?php echo $label ?>][icon]" value="fa-check" ><i class="fa fa-check" aria-hidden="true"></i><br>
            <input class="edit" type="radio" name="labels[<?php echo $label ?>][icon]" value="fa-pencil"><i class="fa fa-pencil" aria-hidden="true"></i>
            <input class="edit" type="radio" name="labels[<?php echo $label ?>][icon]" value="fa-file-text-o"><i class="fa fa-file-text-o" aria-hidden="true"></i>
            <input class="edit" type="radio" name="labels[<?php echo $label ?>][icon]" value="fa-paper-plane-o" checked><i class="fa fa-paper-plane-o" aria-hidden="true"></i>          
        <?php endif; ?>
        </td>
        <td>
            <input class="edit" type="radio" value="<?php echo $label ?>" name="initial" <?php if ($opts['initial'] == "X") echo "checked"; ?> />
        </td> 
        <td>
            <input class="edit" type="text" value="<?php echo $opts['labelEN'] ?>" name="labels[<?php echo $label ?>][labelEN]" size="10" />
        </td>
        <td>
            <input class="edit" type="text" value="<?php echo $opts['labelFR'] ?>" name="labels[<?php echo $label ?>][labelFR]" size="10" />
        </td>
        <td>
            <input class="edit" type="text" value="<?php echo $opts['labelES'] ?>" name="labels[<?php echo $label ?>][labelES]" size="10" />
        </td>                               
        <td>
            <input type="submit" name="action[delete][<?php echo $label ?>]" class="button"
                value="<?php echo $this->getLang('admin delete')?>" />
        </td>
    </tr>
<?php endforeach; ?>
</table>

<input type="submit" name="action[save]" value="<?php echo $this->getLang('admin save')?>" class="button" />
</form>

<br>
<br>
<br>
<h1>Namensräume/Seiten ausschließen</h1>
<form action="<?php echo script()?>" method="post">
    <div class="no">
        <input type="hidden" name="do" value="admin" />
        <input type="hidden" name="page" value="htwlabel" />
        <input type="hidden" name="id" value="<?php echo hsc($ID)?>" />
        <input type="hidden" name="sectok" value="<?php echo hsc(getSecurityToken())?>" />
    </div>

<table class="inline">
    <tr>
        <td><input type="text" name="newexclusion[name]" class="edit" /></td>
        <td><input type="submit" class="button" name="action[add ex]" value="<?php echo $this->getLang('add exclusion')?>" /></td>
    </tr>
    <?php foreach ($excluded as $ex => $opts2): ?>
        <tr>
            <td>
                <p><?php echo $opts2?></p>
            </td>
            <td>
                <input type="submit" name="action[del ex][<?php echo $opts2 ?>]" class="button" value="<?php echo $this->getLang('delete exclusion')?>" />
            </td>
        </tr>
    <?php endforeach; ?>
</table>
</form>

