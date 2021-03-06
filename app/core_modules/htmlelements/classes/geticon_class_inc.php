<?php
// security check - must be included in all scripts
if (!
/**
 * Description for $GLOBALS
 * @global unknown $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// Include the HTML interface class

/**
 * Description for require_once
 */
require_once("ifhtml_class_inc.php");

/**
* Class getIcon class to retrieve an icon by name
*
* It uses a naming and file location convention
* Files are in the /kngicon/ folder as specified in the configuration
* Icons are named file.type (e.g. home.gif)
*
* @package   htmlelements
* @category  Chisimba
* @author    Derek Keats
* @author    Megan Watson
* @author    Wesley Nitsckie
* @copyright 2004, University of the Western Cape & AVOIR Project
* @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General
Public License
* @version   $Id$;
* @link      http://avoir.uwc.ac.za
*/
class getIcon extends object implements ifhtml
{

    /**
    * @var string $type: The extension for the filename for the icon
    */
    public $type;
    /**
    * @var string $name: The name of the icon file, without the extension
    */
    public $name;
    /**
    * @var string $iconfolder: The icon folder for the icons (used so that
    *             it can be set to other than the default
    */
    public $iconfolder;
    /**
    * @var string $_objConfig: string for the configuration object
    */
    public $_objConfig;
    /**
    * @var string $align: optional align property
    */
    public $align;
    /**
    * @var string $alt: The alt text for the icon image
    */
    public $alt;
    /**
    * @var string $title: The title text for the icon image
    */
    public $title;
    /**
    * @var string $extra: Additional attributes for the icon image
    */
    public $extra;

    /**
    * Standard init method
    */
    public function init()
    {
        $this->_objConfig = $this->getObject('altconfig','config');
        $this->_objLanguage = $this->getObject('language', 'language');
        $this->_objSkin = $this->getObject('skin','skin');

        // initialise variables
        $this->align="middle";
        $this->type = '';
        $this->name = '';
        $this->iconfolder = '';
        $this->align = '';
        $this->alt = '';
        $this->title = '';
        $this->extra = '';
    }


    /**
    * Method to set the icon parameters
    * @param string $name       The name of the icon file before the 'extension',
    *                           but not including the extension. For
    *                           example, for the icon "help.gif", $name would be set to "help".
    * @param string $type       The file type / extension (usually gif or png). For
    *                           example, for the icon "help.gif", $type would be set to "gif" or left out.
    * @param string $iconfolder The iconfolder to use, defaults to the
    *                           one specified in the config file for KNG
    *
    */
    public function setIcon($name, $type = 'gif', $iconfolder='icons/')
    {
        $this->name = $name;
        // Just to be explicit - Tohir
        if ($type == NULL) {
            $this->type = 'png';
        } else {
            $this->type = $type;
        }
        // Before Setting the Icon Folder, check if file exists
        $this->_checkIconInSkin ($iconfolder);
    }

    /**
    * Method to set the icon folder, depending on whether the file exists in a skin
    * It sets the folder for the icon
    *
    * @param  string  $folder Folder to check for file
    * @access private
    */
    private function _checkIconInSkin ($folder)
    {
        // Check if last character of folder is a slash, else add one
        if (substr($folder, -1) != '/') {
            $folder .= '/';
        }

        // Prepare Filename - Folder + name + Extension
        $filename = $folder.$this->name.'.'.$this->type;

        // Check if file exists in the current skin
        if (file_exists($this->_objSkin->getSkinLocation().$filename)) {
            $this->iconfolder = $this->_objSkin->getSkinUrl().$folder;
        } else {
            // else set folder to be the _common skin
            if (file_exists($this->_objConfig->getsiteRootPath().$this->_objConfig->getskinRoot().'_common/'.$filename)){
                $this->iconfolder = $this->_objConfig->getskinRoot().'_common/'.$folder;
            } else {
                $this->iconfolder = $this->_objConfig->getskinRoot().'_common/icons/';
                $this->name = 'default';
                $this->type = 'gif';
            }
        }
    }

    /**
    * Method to set the module icon parameters
    * @param string $name The name of the icon file before the 'extension',
    *                     but not including the extension. For
    *                     example, for the icon "help.gif", $name would be set to "help".
    */
    public function setModuleIcon($name)
    {
        // Use Internal Method to set Icon
        $this->setIcon($name, NULL, 'icons/modules/');
        $filename = $this->iconfolder.$this->name.'.'.$this->type;
        //if icon does not exist in modules folder, try one level up
        if (!file_exists($filename)) {
            $this->setIcon($name);
            $filename2 = $this->iconfolder.$this->name.'.'.$this->type;
            //if this doesnt exist, use default
            if (!file_exists($filename2)) {
                $this->setIcon('default');
            }
        }
    }

    /**
    * Method to return edit linked icon
    * @param string $url The uri generated path for the task to be performed
    */
    public function getEditIcon($url)
    {
        $this->setIcon('edit_sm');
        // Set title to be the word edit
        $this->title = $this->_objLanguage->languagetext('word_edit', NULL, "Edit");
        $objLink = $this->newObject('link', 'htmlelements');
        $objLink->href=$url;
        $objLink->link=$this->show();

        return $objLink->show();
    }

    /**
   * Method to return delete linked icon
   * @param string $url The uri generated path for the task to be performed
   */
    public function getDeleteIcon($url)
    {
        $this->setIcon('delete');
        // Set title to be the word delete
        $this->title = $this->_objLanguage->languagetext('word_delete');
        $objLink = $this->newObject('link', 'htmlelements');
        $objLink->href=$url;
        $objLink->link=$this->show();

        return $objLink->show();
    }

   /**
   *
   * Method to return a delete icon linked to confirm
   *
   * @param string $id            The id of the item to be deleted
   * @param string $deleteArray   The array of parameters for the querystring
   * @param string $callingModule THe module which is calling it.
   *
   */
    public function getDeleteIconWithConfirm($id, $deleteArray=NULL, $callingModule=NULL, $deletephrase='phrase_confirmdelete')
    {
        //Set up the delete array
        if ($deleteArray == NULL) {
            $deleteArray = array(
              'action' => $action,
              'confirm'=>'yes',
              'action' => 'managelist',
              'id' => $id);
        }
        //Set the delete icon
        $this->setIcon("delete");
        // Set title to be the word delete
        $this->title = $this->_objLanguage->languagetext('word_delete');
        //Get the delete icon for the confirm object
        $delIcon = $this->show();
        //Create an instance of the confirm object
        $objConfirm = $this->newObject('confirm','utilities');
        if ($callingModule) {
            $location = $this->uri($deleteArray, $callingModule);
        } else {
            $location = $this->uri($deleteArray);
        }

        if ($deletephrase == 'phrase_confirmdelete') {
            $deletephrase = $this->_objLanguage->languageText($deletephrase);
        }

        $objConfirm->setConfirm($delIcon, $location, $deletephrase);
        return $objConfirm->show();
    }

    /**
    * Method to return delete linked icon
    * @param string $url The uri generated path for the task to be performed
    */
    public function getAddIcon($url)
    {
        $this->setIcon("add");
        $objLink = $this->newObject('link', 'htmlelements');
        $objLink->href=$url;
        $objLink->link=$this->show();
        return $objLink->show();
    }

     /**
    * Method to return upload linked icon
    * @param string $url The uri generated path for the task to be performed
    */
    public function getUploadIcon($url)
    {
        $this->setIcon("folder_up");
        $objLink = $this->newObject('link', 'htmlelements');
        $objLink->href=$url;
        $objLink->link=$this->show();
        return $objLink->show();
    }

     /**
    * Method to return upload linked icon
    * @param string $url The uri generated path for the task to be performed
    */
    public function getDownloadIcon($url)
    {
        $this->setIcon("download");
        $objLink = $this->newObject('link', 'htmlelements');
        $objLink->href=$url;
        $objLink->link=$this->show();
        return $objLink->show();
    }

   /**
    * Method to return upload linked icon
    * @param string $url The uri generated path for the task to be performed
    */
    public function getViewIcon($url, $id = '')
    {
        $this->setIcon("bookopen");
        $objLink = $this->newObject('link', 'htmlelements');
        $objLink->href=$url;
        if ($id != '') {
            $objLink->cssId=$id;
        }
        $objLink->link=$this->show();
        return $objLink->show();
    }

    /**
    *
    * Method to return an icon with a link
    *
    * @param string $url        The uri generated path for the task to be performed
    * @param string $name       The name of the icon file before the 'extension',
    *                           but not including the extension. For
    *                           example, for the icon "help.gif", $name would be set to "help".
    * @param string $type       The file type / extension (usually gif or png). For
        example, for the icon "help.gif", $type would be set to "gif" or left out.
    * @param string $iconfolder The iconfolder to use, defaults to the
    *                           one specified in the config file for KNG
    *
    */
    public function getLinkedIcon($url, $name, $type = 'gif', $iconfolder='icons/')
    {
        $this->setIcon($name, $type, $iconfolder);
        $objLink = $this->newObject('link', 'htmlelements');
        $objLink->href=$url;
        $objLink->link=$this->show();
        return $objLink->show();
    }

    /**
    *
    * Method to return an icon with a FQDN link
    *
    * @param string $url        The uri generated path for the task to be performed
    * @param string $name       The name of the icon file before the 'extension',
    *                           but not including the extension. For
    *                           example, for the icon "help.gif", $name would be set to "help".
    * @param string $type       The file type / extension (usually gif or png). For
        example, for the icon "help.gif", $type would be set to "gif" or left out.
    * @param string $iconfolder The iconfolder to use, defaults to the
    *                           one specified in the config file for KNG
    *
    */
    public function getUrlLinkedIcon($url, $name, $type = 'gif', $folder='icons/')
    {
        // Check if last character of folder is a slash, else add one
        if (substr($folder, -1) != '/') {
            $folder .= '/';
        }
        //$this->setIcon($name, $type, $iconfolder);
        $objLink = $this->newObject('link', 'htmlelements');
        $objLink->href=$url;
        $objLink->link = '<img src="'.$this->_objConfig->getSiteRoot().$this->_objConfig->getskinRoot()."_common/".$folder.$name.".".$type.'" />';
        return $objLink->show();
    }

    /**
    * Method to return an icon / text combination link - an icon with text below encapsulated in a div
    *
    * @author Megan Watson
    * @param  string $url        The uri generated path for the task to be performed
    * @param  string $name       The name of the icon file before the 'extension', but not including the extension. For example, for the icon "help.gif", $name would be set to "help".
    * @param  string $linkText   The text to display below the icon.
    * @param  string $type       The file type / extension (usually gif or png). For example, for the icon "help.gif", $type would be set to "gif" or left out.
    * @param  string $iconfolder The iconfolder to use, defaults to the one specified in the config file for KNG
    */
    public function getTextIcon($url, $name, $linkText, $type = 'gif', $iconfolder='icons/', $extra = NULL, $width = '40px', $height = '40px')
    {
        $objLayer = $this->newObject('layer', 'htmlelements');
        $this->loadClass('link', 'htmlelements');

        $this->setIcon($name, $type, $iconfolder);
        $this->extra = " height='$height' width='$width'";
        $this->title = $linkText;
        $icStr = $this->show();

        $icStr .= '<span>'.$linkText.'</span>';

        $objLink = new link($url);
        $objLink->link = $icStr;
        $objLink->extra = $extra;
        $linkStr = $objLink->show();

        $objLayer->str = $linkStr;
        $objLayer->cssClass = 'smallicon';

        return $objLayer->show();
    }

    /**
    * Method to return an icon / text combination link - an icon with text below encapsulated in a div
    *
    * @author Megan Watson, Charl Mert
    * @param  string $url        The uri generated path for the task to be performed
    * @param  string $name       The name of the icon file before the 'extension', but not including the extension. For example, for the icon "help.gif", $name would be set to "help".
    * @param  string $linkText   The text to display below the icon.
    * @param  string $type       The file type / extension (usually gif or png). For example, for the icon "help.gif", $type would be set to "gif" or left out.
    * @param  string $iconfolder The iconfolder to use, defaults to the one specified in the config file for KNG
    */
    public function getCleanTextIcon($divId, $url, $name, $linkText, $type = 'gif', $iconfolder='icons/', $extra = NULL, $width = '40px', $height = '40px')
    {
        $objLayer = $this->newObject('layer', 'htmlelements');
        $this->loadClass('link', 'htmlelements');

        $this->setIcon($name, $type, $iconfolder);
        //$this->extra = " height='$height' width='$width'";
        $this->title = $linkText;
        $icStr = $this->show();

        $icStr .= '<span>'.$linkText.'</span>';

        $objLink = new link($url);
        $objLink->link = $icStr;
        $objLink->extra = $extra;
        $linkStr = $objLink->show();

        $objLayer->str = $linkStr;
        $objLayer->cssClass = 'smallicon';
        $objLayer->id = $divId;

        return $objLayer->show();
    }


    /**
    * Method to return an icon / text combination link - an icon with text below encapsulated in a div
    * The icons must be encapsulated in a div with id='cpanel'
    *
    * @author Megan Watson
    * @param  string $url        The uri generated path for the task to be performed
    * @param  string $name       The name of the icon file before the 'extension', but not including the extension. For example, for the icon "help.gif", $name would be set to "help".
    * @param  string $linkText   The text to display below the icon.
    * @param  string $type       The file type / extension (usually gif or png). For example, for the icon "help.gif", $type would be set to "gif" or left out.
    * @param  string $iconfolder The iconfolder to use, defaults to the one specified in the config file for KNG
    */
    public function getBlockIcon($url, $name, $linkText, $type = 'gif', $iconfolder='icons/', $extra = NULL)
    {
        $objLayer = $this->newObject('layer', 'htmlelements');
        $this->loadClass('link', 'htmlelements');

        $this->setIcon($name, $type, $iconfolder);
        $this->extra = $extra." height='48px' width='48px'";
        $this->title = $linkText;
        $icStr = $this->show();

        $icStr .= '<span>'.$linkText.'</span>';

        $objLink = new link($url);
        $objLink->link = $icStr;
        $linkStr = $objLink->show();

        $objLayer->str = $linkStr;
        $objLayer->cssClass = 'icon';

        return $objLayer->show();
    }

/**
    * Same as above except no forced width
    *
    * @author Megan Watson, Charl Mert
    * @param  string $url        The uri generated path for the task to be performed
    * @param  string $name       The name of the icon file before the 'extension', but not including the extension. For example, for the icon "help.gif", $name would be set to "help".
    * @param  string $linkText   The text to display below the icon.
    * @param  string $type       The file type / extension (usually gif or png). For example, for the icon "help.gif", $type would be set to "gif" or left out.
    * @param  string $iconfolder The iconfolder to use, defaults to the one specified in the config file for KNG
    */
    public function getCleanBlockIcon($url, $name, $linkText, $type = 'gif', $iconfolder='icons/', $extra = NULL)
    {
        $objLayer = $this->newObject('layer', 'htmlelements');
        $this->loadClass('link', 'htmlelements');

        $this->setIcon($name, $type, $iconfolder);
        $this->extra = $extra;
        $this->title = $linkText;
        $icStr = $this->show();

        $icStr .= '<span>'.$linkText.'</span>';

        $objLink = new link($url);
        $objLink->link = $icStr;
        $linkStr = $objLink->show();

        $objLayer->str = $linkStr;
        $objLayer->cssClass = '_icon';

        return $objLayer->show();
    }


/**
    * Same as above except with id as mandatory param
    *
    * @author Megan Watson, Charl Mert
    * @param  string $url        The uri generated path for the task to be performed
    * @param  string $name       The name of the icon file before the 'extension', but not including the extension. For example, for the icon "help.gif", $name would be set to "help".
    * @param  string $linkText   The text to display below the icon.
    * @param  string $type       The file type / extension (usually gif or png). For example, for the icon "help.gif", $type would be set to "gif" or left out.
    * @param  string $iconfolder The iconfolder to use, defaults to the one specified in the config file for KNG
    */
    public function getCleanBlockIconId($divId, $url, $name, $linkText, $type = 'gif', $iconfolder='icons/', $extra = NULL)
    {
        $objLayer = $this->newObject('layer', 'htmlelements');
        $this->loadClass('link', 'htmlelements');

        $this->setIcon($name, $type, $iconfolder);
        $this->extra = $extra;
        $this->title = $linkText;
        $icStr = $this->show();

        $icStr .= '<span>'.$linkText.'</span>';

        $objLink = new link($url);
        $objLink->link = $icStr;
        $linkStr = $objLink->show();

        $objLayer->str = $linkStr;
		$objLayer->id = $divId;
        $objLayer->cssClass = '_icon';

        return $objLayer->show();
    }


    /**
    * Method to show the icon
    * @return the icon URL as a string
    */
    public function show()
    {
        $ret = "<img src=\"" . $this->iconfolder
            . $this->name . "." . $this->type
            . "\" border=\"0\"";
        if ($this->align) {
            $ret .= " align=\"".$this->align."\"";
        }
        //Try alt first, if not try title, and as a last resort use name
        // removed name as it conflicts with tooltips
        if ($this->alt) {
            $ret .= " alt=\"".$this->alt."\"";
        } else {
            if ($this->title) {
                $ret .= " alt=\"".$this->title."\"";
            } else {
                //$ret .= " alt=\"".$this->name."\"";
            }
        }
        // try title first, then alt, and as a last resort use name
        // removed name as it conflicts with tooltips
        if ($this->title) {
            $ret .= " title=\"".$this->title."\"";
        } else {
            if ($this->alt) {
                $ret .= " title=\"".$this->alt."\"";
            } else {
                //$ret .= " title=\"".$this->name."\"";
            }
        }
    // additional attributes
    if($this->extra) {
        $ret .= ' '.$this->extra;
    }
        $ret .=" />";
        return $ret;
    }


    /**
    * Method to get the src url of the image.
    * @return the icon URL as a string
    */
    public function getSrc()
    {
        $ret = $this->iconfolder
            . $this->name . "." . $this->type;

        return $ret;
    }

}
?>
