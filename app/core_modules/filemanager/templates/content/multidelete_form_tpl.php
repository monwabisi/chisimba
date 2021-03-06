<?php

$this->objFileIcons = $this->getObject('fileicons', 'files');
$this->loadClass('form', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');

// echo '<pre>';
// print_r($_POST);
// echo '</pre>';



if ($this->getParam('files') == NULL || !is_array($this->getParam('files')) || count($this->getParam('files')) == 0) {
    echo '<div class="noRecordsMessage">'.$this->objLanguage->languageText('mod_filemanager_nofileswereselected', 'filemanager', 'No Files Were Selected').'</div>';
    echo '<p><a href="javascript:history.back()">'.$this->objLanguage->languageText('mod_filemanager_backtopreviouspage', 'filemanager', 'Back to Previous Page').'</a> / ';
    echo '<a href="'.$this->uri(NULL).'">'.$this->objLanguage->languageText('mod_filemanager_returntofilemanager', 'filemanager', 'Return to File Manager').'</a></p>';
    
    
    
} else {
    
    
    $files = $this->getParam('files');
    
    $form = new form('confirmdelete', $this->uri(array('action'=>'multideleteconfirm')));
    
    $folderIcon = $this->objFileIcons->getExtensionIcon('folder');
    
    $form->addToForm ('<ul>');
    
    $counter = 0;
    
    foreach ($files as $file)
    {
        
        if (substr($file, 0, 8) == 'folder__') {
            $file = substr($file, 8);
            $folderDetails = $this->objFolders->getFolder($file);
            
            if ($folderDetails != FALSE) {
                
                $counter++;
                
                $folderName = htmlentities($folderDetails['folderpath']);
                $folderName = preg_replace('/\\Ausers\/'.$this->objUser->userId().'\//', 'My Files/', $folderName);
                
                $checkbox = new checkbox('files[]', $folderName, TRUE);
                $checkbox->value = 'folder__'.$file;
                $checkbox->cssId = 'folder__'.$file;
                
                $form->addToForm ('<li>'.$checkbox->show().' '.$folderIcon.' '.$folderName.'</li>');
            }
        } else {
            $fileDetails = $this->objFiles->getFile($file);
            
            if ($fileDetails != FALSE) {
            
                $counter++;
                
                $checkbox = new checkbox('files[]', htmlentities($fileDetails['filename']), TRUE);
                $checkbox->value = $file;
                $checkbox->cssId = $file;
                
                $form->addToForm ('<li>'.$checkbox->show().' '.htmlentities($fileDetails['filename']).'</li>');
            } else {
                if (substr($file, 0, 9) == 'symlink__') {
                    $symlinkId = substr($file, 9);
                    
                    $symlink = $this->objSymlinks->getFullSymlink($symlinkId);
                    
                    if ($symlink != FALSE) {
                        $counter++;
                        
                        $checkbox = new checkbox('files[]', htmlentities($symlink['filename']), TRUE);
                        $checkbox->cssId = $file;
                        $checkbox->value = $file;
                        $form->addToForm ('<li>'.$checkbox->show().' '.htmlentities($symlink['filename']).'</li>');
                    }
                    
                }
            }
        }
    }
    
    $form->addToForm ('</ul>');
    
    $button = new button ('submitform', $this->objLanguage->languageText('mod_filemanager_confirmdeleteselecteditems', 'filemanager', 'Confirm Delete Selected Items'));
    $button->setToSubmit();
    
    $form->addToForm ($button->show());
    
    $folderInput = new hiddeninput('folder', $this->getParam('folder'));
    $this->setVar('folderId', $this->getParam('folder'));
    
    $form->addToForm($folderInput->show());
    
    if ($counter > 0) {
        echo '<h1>'.$this->objLanguage->languageText('mod_filemanager_confirmdeletefiles', 'filemanager', 'Confirm Delete Files').'?</h1>';
        echo '<p>'.$this->objLanguage->languageText('mod_filemanager_areyousuredeletefiles', 'filemanager', 'Are you sure you want to delete these files/folders?').'</p>';
        echo $form->show();
    } else {
        echo '<h1 class="error">'.$this->objLanguage->languageText('word_error', 'system', 'Error').':</h1>';
        echo '<p>'.$this->objLanguage->languageText('mod_filemanager_warnfilesnolonger', 'filemanager', 'The files/folders you have attempted to delete no longer exist').'.</p>';
    }
}
      
?>