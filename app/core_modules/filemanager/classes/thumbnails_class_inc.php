<?php

/**
 * Class to Generate Thumbnails for the File Manager
 *
 * This class packages functionality to generate thumbnails for images that are uploaded.
 * It is separated from the image resize class as it here it checks for folder existence.
 *
 * PHP version 5
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the
 * Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 * @category  Chisimba
 * @package   filemanager
 * @author    Tohir Solomons <tsolomons@uwc.ac.za>
 * @copyright 2007 Tohir Solomons
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za
 * @see
 */


/**
 * Class to Generate Thumbnails for the File Manager
 *
 * This class packages functionality to generate thumbnails for images that are uploaded.
 * It is separated from the image resize class as it here it checks for folder existence.
 *
 * @category  Chisimba
 * @package   filemanager
 * @author    Tohir Solomons <tsolomons@uwc.ac.za>
 * @copyright 2007 Tohir Solomons
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   Release: @package_version@
 * @link      http://avoir.uwc.ac.za
 * @see
 */
class thumbnails extends object
{

    /**
    * Constructor
    */
    public function init()
    {
        $this->objConfig = $this->getObject('altconfig', 'config');
        $this->objMkdir = $this->getObject('mkdir', 'files');
        $this->objImageResize = $this->getObject('imageresize', 'files');
        $this->objFileParts = $this->getObject('fileparts', 'files');
        $this->objCleanUrl = $this->getObject('cleanurl');
    }

    /**
    * Method to check that the user folder for uploads, and subfolders exist
    * @param  string  $userId UserId of the User
    * @return boolean True if folder exists, else False
    */
    public function checkThumbnailFolder()
    {
        // Set Up Path
        $path = $this->objConfig->getcontentBasePath().'/filemanager_thumbnails';
        $path = $this->objCleanUrl->cleanUpUrl($path);

        // Check if Folder exists, else create it
        $result = $this->objMkdir->mkdirs($path);

        return $result;
    }

    /**
    * Method to create Thumbnail
    * @param string $filepath Path to File
    * @param string $fileId   Record Id of the path
    */
    public function createThumbailFromFile($filepath, $fileId)
    {
        // Do a check if a thumbnail exists. No need to overwrite
        $thumbJpg = $this->objConfig->getcontentBasePath().'/filemanager_thumbnails/'.$fileId.'.jpg';
        $thumbJpg = $this->objCleanUrl->cleanUpUrl($thumbJpg);
        $thumbPng = $this->objConfig->getcontentBasePath().'/filemanager_thumbnails/'.$fileId.'.png';
        $thumbPng = $this->objCleanUrl->cleanUpUrl($thumbPng);        
        
        // Dont do anything if thumb exists.
        if (file_exists($thumbJpg))
        {
            $ext = 'jpg';
            return TRUE;
        }
        elseif (file_exists($thumbPng))
        {
            $ext = 'png';
            return TRUE;
        }
        
        
        // Check if folder exists
        $this->checkThumbnailFolder();
        
        $filepath = $this->objCleanUrl->cleanUpUrl($filepath);
        
        // Send Image to Resize Class
        $this->objImageResize->setImg($filepath);
        
        // Resize to 100x100 Maintaining Aspect Ratio
        $this->objImageResize->resize(100, 100, TRUE);
        
        //$this->objImageResize->show(); // Uncomment for testing purposes

        
        // Determine filename for file
        // If thumbnail can be created, give it a unique file name
        // Else resort to [ext].jpg - prevents clutter, other files with same type can reference this one file
        if ($this->objImageResize->canCreateFromSouce)
        {
            if ($ext == 'jpg')
            {
                $img = $this->objConfig->getcontentBasePath().'/filemanager_thumbnails/'.$fileId.'.jpg';
            }
            else
            {
                $img = $this->objConfig->getcontentBasePath().'/filemanager_thumbnails/'.$fileId.'.png';
            }
        }
        else
        {
            if ($ext == 'jpg')
            {
                $img = $this->objConfig->getcontentBasePath().'/filemanager_thumbnails/'.$this->objImageResize->filetype.'.jpg';
            }
            else
            {
                $img = $this->objConfig->getcontentBasePath().'/filemanager_thumbnails/'.$this->objImageResize->filetype.'.png';
            }
        }
        
        // Save File
        return $this->objImageResize->store($img);
    }

    /**
    * Method to get the thumbnail for a file
    *
    * Second parameter is optional. If provided and thumbnail does not exist, it will return an image
    * saying "unable to create thumbnail for {filetype}
    *
    * Otherwise it will just return FALSE
    * 
    * @param  string $fileId   Record Id of the File
    * @param  string $filename Filename of the file
    * @return string Path to the thumbnail or False
    */
    public function getThumbnail($fileId, $filename=NULL, $filePath=NULL)
    {
        // Create thumbnail. If it exists, it will not be recreated.
        if ($filePath != NULL) {
            $this->createThumbailFromFile($this->objConfig->getcontentBasePath().'/'.$filePath, $fileId);
        }
        
        // Check if thumbnail exist
        if (file_exists($this->objConfig->getcontentPath().'/filemanager_thumbnails/'.$fileId.'.jpg'))
        {
            $url = $this->objConfig->getcontentPath().'/filemanager_thumbnails/'.$fileId.'.jpg';
            $url = $this->objCleanUrl->cleanUpUrl($url);
            return $url;
        } 
        elseif (file_exists($this->objConfig->getcontentPath().'/filemanager_thumbnails/'.$fileId.'.png'))
        {
            $url = $this->objConfig->getcontentPath().'/filemanager_thumbnails/'.$fileId.'.png';
            $url = $this->objCleanUrl->cleanUpUrl($url);
            return $url;
        } 
        
        if ($filename == NULL)
        {
            return FALSE;
        }
        else
        {
            $extension = $this->objFileParts->getExtension($filename);
            
            if (file_exists($this->objConfig->getcontentPath().'/filemanager_thumbnails/'.$extension.'.jpg'))
            {
                $url = $this->objConfig->getcontentPath().'/filemanager_thumbnails/'.$extension.'.jpg';
                $url = $this->objCleanUrl->cleanUpUrl($url);
                return $url;
            }
            elseif (file_exists($this->objConfig->getcontentPath().'/filemanager_thumbnails/'.$extension.'.png'))
            {
                $url = $this->objConfig->getcontentPath().'/filemanager_thumbnails/'.$extension.'.png';
                $url = $this->objCleanUrl->cleanUpUrl($url);
                return $url;
            }
            else
            {
                return FALSE;
            }
        }
    }
}
?>